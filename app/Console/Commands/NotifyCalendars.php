<?php

namespace App\Console\Commands;

use App\Http\Ximmio\Collection;
use App\Http\Ximmio\Ximmio;
use App\Models\Calendar;
use App\Notifications\PutGarbageOut;
use Illuminate\Console\Command;

class NotifyCalendars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calendars:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify all Pushover calendars';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $calendars = Calendar::whereNotNull('pushover_key')->get();

        foreach ($calendars as $calendar) {
            $this->notify($calendar);
        }
    }

    private function notify(Calendar $calendar) {
        if (!$calendar->remind_me_at->isBetween(now()->subMinutes(30), now()->addMinutes(30))) {
            return;
        }

        /** @var Collection $next */
        $next = collect(Ximmio::getCollections($calendar->address))
            ->filter(fn (Collection $collection): bool => $calendar->remind_me_on === 'same' ? $collection->pickupDate->isToday() : $collection->pickupDate->isTomorrow())
            ->sortBy(fn (Collection $collection): int => $collection->pickupDate->timestamp)
            ->first();

        if (is_null($next)) {
            return;
        }

        if (!is_null($calendar->pushover_pushed_at) && $calendar->pushover_pushed_at->eq($next->pickupDate)) {
            return;
        }

        $calendar->pushover_pushed_at = $next->pickupDate;
        $calendar->save();

        $calendar->notify(new PutGarbageOut(__('calendar.summary', ['type' => __('ximmio.types.' . $next->type)])));
    }
}
