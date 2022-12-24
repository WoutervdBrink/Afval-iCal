@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <form method="post">
        @csrf

        <div class="mb-3">
            <label for="company" class="form-label">Afvalbedrijf</label>
            <select id="company" name="company" class="form-select @error('postal_code') is-invalid @enderror">
                @foreach($companies as $company)
                    <option value="{{ $company->code }}">{{ $company->name }}</option>
                @endforeach
            </select>
            <div class="form-text">
                Selecteer hier het bedrijf verantwoordelijk voor het ophalen van afval in jouw buurt.
            </div>
            @error('company')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 row">
            <div class="col-sm">
                <label for="postal_code" class="form-label">Postcode</label>
                <input class="form-control @error('postal_code') is-invalid @enderror" id="postal_code"
                       name="postal_code" placeholder="1234AB"
                       type="text">
                @error('postal_code')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-sm">
                <label for="house_number" class="form-label">Huisnummer</label>
                <input class="form-control @error('house_number') is-invalid @enderror" id="house_number"
                       name="house_number" placeholder="5"
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
                       class="form-select @error('remind_me_at') is-invalid @enderror" type="time" value="20:00">
                @error('remind_me_at')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            Maak URL
        </button>
    </form>
@endsection
