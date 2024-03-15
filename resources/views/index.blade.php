@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <h2>Afval-iCal</h2>
    <p>Deze website kan voor een beperkt aantal afvalbedrijven een iCal-URL met ophaalmomenten genereren. Je krijgt dan
        afspraken in je digitale agenda voor alle ophaalmomenten. Door herinneringen in te stellen krijg je meldingen
        wanneer je een container aan de straat moet zetten:</p>

    <div class="row mb-4">
        <div class="col-8 mx-auto bg-light shadow-sm p-3 d-flex align-items-center">
            <img src="{{ asset('img/calendar.svg') }}" class="flex-shrink-0 me-3" style="width: 2em; height: 2em"
                 alt="Agenda-icoon">
            <div>
                Restafval aan de straat zetten<br>
                <span id="example_time">
                    {{ now()->setTime(20, 0)->subDay()->isoFormat('lll') }}
                    -
                    {{ now()->subDay()->setTime(22, 0)->format('H:i') }}
                </span>
            </div>
        </div>
    </div>

    <h2>URL genereren</h2>
    <form method="post">
        @csrf

        <div class="mb-3">
            <label for="company" class="form-label">Afvalbedrijf</label>
            <select id="company" name="company" class="form-select @error('postal_code') is-invalid @enderror">
                @foreach($companies as $company)
                    <option
                        value="{{ $company->code }}"
                        @if(old('company') === $company->code) selected="selected" @endif>
                        {{ $company->name }}
                    </option>
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
                <input class="form-control @error('postal_code') is-invalid @enderror" id="postal_code"
                       name="postal_code" placeholder="1234AB" value="{{ old('postal_code') }}"
                       type="text">
                @error('postal_code')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-sm">
                <label for="house_number" class="form-label">Huisnummer</label>
                <input class="form-control @error('house_number') is-invalid @enderror" id="house_number"
                       name="house_number" placeholder="5" value="{{ old('house_number') }}"
                       type="text">
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
                    @foreach(['before', 'same'] as $option)
                        <option
                            value="{{ $option }}"
                            @if(old('remind_me_on') === $option) selected="selected" @endif>
                            {{ __('calendar.remind_me_on.'.$option) }}
                        </option>
                    @endforeach
                </select>
                @error('remind_me_on')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-sm">
                <label for="remind_me_at">Om</label>
                <input id="remind_me_at" name="remind_me_at"
                       class="form-select @error('remind_me_at') is-invalid @enderror" type="time" value="{{ old('remind_me_at', '20:00') }}">
                @error('remind_me_at')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label for="duration" class="form-label">Duur van agenda-item</label>
            <div class="input-group has-validation">
                <input class="form-control @error('duration') is-invalid @enderror" id="duration"
                       name="duration" placeholder="120"
                       min="10" max="240" step="5" value="{{ old('duration', 120) }}"
                       type="number">
                <span class="input-group-text">minuten</span>
                @error('duration')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary d-block w-100">
            Maak URL
        </button>
    </form>

    @vite('resources/js/form.js')
@endsection
