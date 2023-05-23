@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <h2>Afval-iCal</h2>
    <p>Deze website kan voor een beperkt aantal afvalbedrijven herinneringen geven bij
        ophaalmomenten. Je krijgt dan een melding als je een countainer aan de straat moet
        zetten:</p>

    <div class="row mb-4">
        <div class="col-8 mx-auto bg-light shadow-sm p-3 d-flex align-items-center">
            <img src="{{ asset('img/calendar.svg') }}" class="flex-shrink-0 me-3"
                 style="width: 2em; height: 2em" alt="Agenda-icoon">
            <div>
                Restafval aan de straat zetten<br>
                {{ now()->setTime(20, 0)->isoFormat('lll') }}
            </div>
        </div>
    </div>

    <form method="post" action="{{ route('calendars.store') }}">
        @csrf

        <div class="row">
            <div class="col-sm-6">
                <h2>Gegevens</h2>

                <div class="mb-3">
                    <label for="company" class="form-label">Afvalbedrijf</label>
                    <select id="company" name="company"
                            class="form-select @error('postal_code') is-invalid @enderror">
                        @foreach($companies as $company)
                            <option value="{{ $company->code }}">{{ $company->name }}</option>
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
                               type="text">
                        @error('postal_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm">
                        <label for="house_number" class="form-label">Huisnummer</label>
                        <input class="form-control @error('house_number') is-invalid @enderror"
                               id="house_number"
                               name="house_number" placeholder="5"
                               type="text">
                        @error('house_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4 row">
                    <div class="col-sm">
                        <label for="remind_me_on">Herinner mij op</label>
                        <select id="remind_me_on" name="remind_me_on"
                                class="form-select @error('remind_me_on') is-invalid @enderror">
                            <option value="before">De dag ervoor</option>
                            <option value="same">De dag zelf</option>
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
                               value="20:00">
                        @error('remind_me_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <h3>Pushover</h3>
                <p>Als je een <a href="https://pushover.net/" target="_blank">Pushover-account</a>
                    hebt, kun je notificaties laten sturen naar al je Pushover-apparaten.</p>

                <div class="mb-3">
                    <label for="pushover_key" class="form-label">Pushover user key</label>
                    <input id="pushover_key" name="pushover_key" class="form-control @error('pushover_key') is-invalid @enderror" placeholder="f7tzsxda0pkyuw83qpmblmgfn6vib1">
                    <div class="form-text">
                        Je vindt hem op <a href="https://pushover.net/" target="_blank">de website</a>.
                    </div>
                    @error('pushover_key')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <h3>iCal</h3>
                <p>Je kunt ook een iCal-URL met ophaalmomenten genereren. Je krijgt dan afspraken in
                    je digitale agenda voor alle ophaalmomenten. Het nadeel aan deze methode is dat
                    veel kalender-apps de melding zelf weghalen.</p>
            </div>
        </div>

        <button type="submit" class="btn btn-primary d-block w-100">
            Verder
        </button>
    </form>
@endsection
