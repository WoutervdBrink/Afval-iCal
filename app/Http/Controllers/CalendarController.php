<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCalendarRequest;
use App\Http\Requests\UpdateCalendarRequest;
use App\Http\Ximmio\Ximmio;
use App\Models\Calendar;
use App\Models\Company;
use App\Notifications\PushoverRegistered;

class CalendarController extends Controller
{
    public function store(CreateCalendarRequest $request)
    {
        $company = Company::findOrFail($request->get('company'));

        $address = Ximmio::getAddress(
            $company,
            $request->validated('postal_code'),
            $request->validated('house_number'),
        );

        $calendar = Calendar::updateOrCreate(
            [
                'address_id' => $address->id,
                'remind_me_on' => $request->validated('remind_me_on'),
                'remind_me_at' => $request->validated('remind_me_at'),
                'pushover_key' => $request->validated('pushover_key', null),
            ],
            [],
        );

        if (!is_null($calendar->pushover_key)) {
            $calendar->notify(new PushoverRegistered());
        }

        return redirect()
            ->route('calendars.show', $calendar);
    }

    public function show(Calendar $calendar)
    {
        $companies = Company::orderBy('name')->get();

        return view(is_null($calendar->pushover_key) ? 'url' : 'pushover', [
            'companies' => $companies,
            'calendar' => $calendar,
            'address' => $calendar->address,
            'url' => route('ical.render', $calendar)
        ]);
    }

    public function update(Calendar $calendar, UpdateCalendarRequest $request)
    {
        abort_if(is_null($calendar->pushover_key), 403, 'Alleen Pushover-agenda\'s kunnen worden gewijzigd.');

        $company = Company::findOrFail($request->get('company'));

        $address = Ximmio::getAddress(
            $company,
            $request->validated('postal_code'),
            $request->validated('house_number'),
        );

        $calendar->update([
            'address_id' => $address->id,
            'remind_me_on' => $request->validated('remind_me_on'),
            'remind_me_at' => $request->validated('remind_me_at'),
            'pushover_key' => $request->validated('pushover_key', null),
        ]);

        return redirect()
            ->route('calendars.show', $calendar);
    }

    public function destroy(Calendar $calendar) {
        abort_if(is_null($calendar->pushover_key), 403, 'Alleen Pushover-agenda\'s kunnen worden gewijzigd.');

        $calendar->delete();

        return redirect()->route('home.index');
    }
}
