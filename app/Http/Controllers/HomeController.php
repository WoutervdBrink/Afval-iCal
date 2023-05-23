<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCalendarRequest;
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
}
