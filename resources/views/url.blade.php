@extends('layouts.app')

@section('title', 'Home')

@section('content')
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
            De dag ervoor, om 20:00
        </dd>
    </dl>

    <label for="url" class="form-label">iCal-URL</label>
    <div class="input-group">
        <input class="form-control" id="url" type="text" readonly="readonly" value="{{ $url }}">
        <button class="btn btn-primary" type="button">Kopieer</button>
    </div>
@endsection
