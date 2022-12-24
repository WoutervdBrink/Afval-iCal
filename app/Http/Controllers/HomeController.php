<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateURLRequest;
use App\Http\Ximmio\Ximmio;
use App\Models\Calendar;
use App\Models\Company;

class HomeController extends Controller
{
    public function index()
    {
        $companies = Company::orderBy('name')->get();

        return view('index', compact('companies'));
    }

    public function create(CreateURLRequest $request)
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
            ],
            [],
        );

        $url = route('ical.render', $calendar);

        return view('url', [
            ...$request->validated(),
            'address' => $address,
            'url' => $url,
        ]);
    }
}
