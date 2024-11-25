<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateURLRequest;
use App\Http\Ximmio\Ximmio;
use App\Models\Calendar;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $companies = Company::orderBy('name')->get();

        return view('index', compact('companies'));
    }

    public function create(CreateURLRequest $request): RedirectResponse
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
                'duration' => $request->validated('duration'),
            ],
            [],
        );

        return redirect()
            ->route('home.show', $calendar);
    }

    public function show(Calendar $calendar): View
    {
        return view('url', [
            'calendar' => $calendar,
            'address' => $calendar->address,
            'url' => route('ical.render', $calendar)
        ]);
    }
}
