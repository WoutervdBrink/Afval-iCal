@extends('layouts.app')

@section('title', 'Link')

@section('content')
    <h2>iCal-URL</h2>
    <p>Hieronder vind je de iCal-URL voor de volgende gegevens:</p>

    <dl class="row">
        <dt class="col-sm-4">Bedrijf</dt>
        <dd class="col-sm-8">{{ $address->company->name }}</dd>

        <dt class="col-sm-4">Adres</dt>
        <dd class="col-sm-8">
            {{ $address->street }} {{ $address->house_number }}<br>
            {{ $address->postal_code }}&nbsp;&nbsp;{{ $address->city }}
        </dd>

        <dt class="col-sm-4">Herinner mij</dt>
        <dd class="col-sm-8">
            {{ __('calendar.remind_me_on.'.$calendar->remind_me_on) }}, om
            <time datetime="{{ $calendar->remind_me_at->toTimeString() }}">
                {{ $calendar->remind_me_at->format('H:i') }}
            </time>
        </dd>
    </dl>

    <label for="url" class="form-label">iCal-URL</label>
    <div class="input-group mb-4">
        <input class="form-control" id="url" type="text" readonly="readonly" value="{{ $url }}">
        <button class="btn btn-primary" type="button" id="url-button">Kopieer</button>
    </div>

    <h2>Hoe gebruik ik dit?</h2>
    <p>Kopieer de link hierboven en importeer hem in je agenda. Zorg ervoor dat je zelf herinneringen instelt voor de
    afspraken in de link; deze website kan geen herinneringen voor je instellen.</p>

    @vite('resources/js/app.js')
@endsection
