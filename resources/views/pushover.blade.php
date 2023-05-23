@extends('layouts.app')

@section('title', 'Pushover')

@section('content')
    <h2>Pushover</h2>
    <p>Deze website stuurt Pushover-meldingen volgens onderstaande instellingen.</p>

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

    <h2>Instellingen aanpassen</h2>

    <form method="post" action="{{ route('calendars.update', $calendar) }}">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="company" class="form-label">Afvalbedrijf</label>
            <select id="company" name="company"
                    class="form-select @error('postal_code') is-invalid @enderror">
                @foreach($companies as $company)
                    <option value="{{ $company->code }}" @if(old('company', $address->company->code) === $company->code) selected="selected" @endif>{{ $company->name }}</option>
                @endforeach
            </select>
            <div class="form-text">
                Selecteer hier het bedrijf dat afval ophaalt in jouw buurt.
            </div>
            @error('company')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 row">
            <div class="col-sm">
                <label for="postal_code" class="form-label">Postcode</label>
                <input class="form-control @error('postal_code') is-invalid @enderror"
                       id="postal_code"
                       name="postal_code" placeholder="1234AB"
                       type="text"
                       value="{{ old('postal_code', $address->postal_code) }}">
                @error('postal_code')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-sm">
                <label for="house_number" class="form-label">Huisnummer</label>
                <input class="form-control @error('house_number') is-invalid @enderror"
                       id="house_number"
                       name="house_number" placeholder="5"
                       type="text"
                       value="{{ old('house_number', $address->house_number) }}">
                @error('house_number')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3 row">
            <div class="col-sm">
                <label for="remind_me_on">Herinner mij op</label>
                <select id="remind_me_on" name="remind_me_on"
                        class="form-select @error('remind_me_on') is-invalid @enderror">
                    <option value="before" @if(old('remind_me_on', $calendar->remind_me_on) === 'before') selected="selected" @endif>De dag ervoor</option>
                    <option value="same" @if(old('remind_me_on', $calendar->remind_me_on) === 'same') selected="selected" @endif>De dag zelf</option>
                </select>
                @error('remind_me_on')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-sm">
                <label for="remind_me_at">Om</label>
                <input id="remind_me_at" name="remind_me_at"
                       class="form-select @error('remind_me_at') is-invalid @enderror"
                       type="time"
                       value="{{ old('time', $calendar->remind_me_at->format('H:i')) }}">
                @error('remind_me_at')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="pushover_key" class="form-label">Pushover user key</label>
            <input id="pushover_key" name="pushover_key"
                   class="form-control @error('pushover_key') is-invalid @enderror"
                   placeholder="f7tzsxda0pkyuw83qpmblmgfn6vib1"
                   value="{{ old('pushover_key', $calendar->pushover_key) }}">
            <div class="form-text">
                Je vindt hem op <a href="https://pushover.net/" target="_blank">de website</a>.
            </div>
            @error('pushover_key')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary d-block w-100">
            Wijzigingen opslaan
        </button>
    </form>

    <h2 class="mt-3">Verwijderen</h2>

    <p>Wil je geen herinneringen meer ontvangen, dan kun je de instellingen verwijderen.</p>

    <form method="post" action="{{ route('calendars.destroy', $calendar) }}">
        @csrf
        @method('delete')

        <button type="submit" class="btn btn-danger d-block w-100">
            Instellingen verwijderen
        </button>
    </form>

    @vite('resources/js/app.js')
@endsection
