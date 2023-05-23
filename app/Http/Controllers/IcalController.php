<?php

namespace App\Http\Controllers;

use App\Http\Ximmio\Ximmio;
use App\Models\Calendar;
use DateInterval;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\ValueObject\Alarm;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;

class IcalController extends Controller
{
    public function render(Calendar $calendar)
    {
        $calendar->touch();
        $calendar->address->touch();

        $address = $calendar->address;
        $company = $address->company;

        $collection = Ximmio::getCollections($calendar->address);

        $ical = new \Eluceo\iCal\Domain\Entity\Calendar();
        $ical->setPublishedTTL(new DateInterval('P1D'));

        foreach ($collection as $col) {
            $moment = $col->pickupDate;

            if ($calendar->remind_me_on === 'before') {
                $moment = $moment->subDay();
            }

            $moment->setTimeFromTimeString($calendar->remind_me_at->format('H:i'));

            $from = new DateTime($moment, true);
            $to = new DateTime($moment->addHours(2), true);
            $summary = __('calendar.summary', ['type' => __('ximmio.types.' . $col->type)]);
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
