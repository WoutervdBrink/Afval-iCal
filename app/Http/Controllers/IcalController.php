<?php

namespace App\Http\Controllers;

use App\Http\Ximmio\Ximmio;
use App\Models\Calendar;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;
use Illuminate\Support\Facades\Cache;

class IcalController extends Controller
{
    public function render(Calendar $calendar)
    {
        $calendar->touch();

        $address = $calendar->address;
        $company = $address->company;

        $collection = Ximmio::getCollections($calendar->address);

        $ical = new \Eluceo\iCal\Domain\Entity\Calendar();

        foreach ($collection as $col) {
            $moment = $col->pickupDate;

            if ($calendar->remind_me_on === 'before') {
                $moment = $moment->subDay();
            }

            $moment->setTimeFromTimeString($calendar->remind_me_at);

            $from = new DateTime($moment, false);
            $to = new DateTime($moment->addMinutes(10), false);

            $ical->addEvent(
                (new Event())
                    ->setSummary(__('calendar.summary', ['type' => __('ximmio.types.' . $col->type)]))
                    ->setDescription(
                        __(
                            'calendar.description',
                            [
                                'company' => $company->name,
                                'date' => now()->format('Y-m-d H:i:s')
                            ]
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
