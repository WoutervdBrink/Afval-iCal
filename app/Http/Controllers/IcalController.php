<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\WasteType;
use App\Ximmio\Ximmio;
use DateInterval;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\ValueObject\Alarm;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;
use Illuminate\Http\Response;

class IcalController extends Controller
{
    public function __invoke(Calendar $calendar): Response
    {
        $calendar->touch();
        $calendar->address->touch();

        $address = $calendar->address;
        $company = $address->company;

        $collection = Ximmio::getCollections($company->code, $address->id);

        $ical = new \Eluceo\iCal\Domain\Entity\Calendar();
        $ical->setPublishedTTL(new DateInterval('P1D'));

        foreach ($collection as $col) {

            $moment = $col->pickupDate;

            if ($calendar->remind_me_on === 'before') {
                $moment = $moment->subDay();
            }

            $moment->setTimeFromTimeString($calendar->remind_me_at->format('H:i'));

            $from = new DateTime($moment, true);
            $to = new DateTime($moment->addMinutes($calendar->duration), true);

            $wasteType = $company->wasteTypes()->where('code', $col->type)->first();
            $type = $wasteType?->name ?? $col->type;

            $summary = __('calendar.summary', ['type' => $type]);
            $description = __(
                            'calendar.description',
                            [
                                'company' => $company->name,
                                'date' => now()->format('Y-m-d H:i:s')
                            ]
                        );

            $ical->addEvent(
                (new Event())
                    ->setSummary($summary)
                    ->setDescription($description)
                    ->addAlarm(
                        new Alarm(
                            new Alarm\DisplayAction($summary),
                            (new Alarm\RelativeTrigger(DateInterval::createFromDateString('-10 minutes')))->withRelationToEnd()
                        )
                    )
                    ->setOccurrence(new TimeSpan($from, $to))
            );
        }

        $factory = new CalendarFactory();
        $component = $factory->createCalendar($ical);

        return response($component)
            ->header('Content-Type', 'text/calendar; charset=utf-8');
    }
}
