@extends('layouts.app')

@section('title', 'Privacy')

@section('content')
    <h2>Privacy</h2>

    <p>Maak je gebruik van de diensten op deze website, dan is het noodzakelijk dat de volgende
        persoonsgegevens worden verzameld:</p>

    <ul>
        <li>Adres, bestaande uit postcode, huisnummer, en stad;</li>
        <li>Het afvalbedrijf dat bij jou het afval ophaalt;</li>
        <li>Een uniek identificatienummer dat het afvalbedrijf gebruikt om jouw adres op te slaan.
        </li>
    </ul>

    <p>Deze persoonsgegevens worden alleen verwerkt om jouw agenda te voorzien van de juiste
        informatie.</p>

    <p>Verder is het noodzakelijk dat bovenstaande gegevens worden gedeeld met het afvalbedrijf,
        zodat de ophaalmomenten die bij jouw adres horen kunnen worden opghaald.</p>

    <p>Deze website maakt gebruik van functionele cookies, die niet voor analytische doeleinden
        worden ingezet.</p>

    <p>Persoonsgegevens worden automatisch verwijderd als jouw URL een maand lang niet wordt
        gebruikt. Verwijder je de URL uit je agenda, dan zijn je persoonsgegevens dus een maand
        later verwijderd, mits je de URL niet ergens anders gebruikt.</p>

    <p>Bovenstaande geldt niet als je gebruik maakt van Pushover. In dit geval kun je zelf de
        gegevens verwijderen op de pagina waar je ook de instellingen kunt wijzigen. Heb je hier
        geen toegang meer toe, neem dan contact op.</p>

    <p>De GDPR geeft jou verschillende rechten ten aanzien van je persoonsgegevens, zoals recht op
        inzage en verwijdering. Wil je gebruik maken van deze rechten, neem dan contact op via <a
            href="mailto:contact@dutch1.nl">contact@dutch1.nl</a>.</p>

    <a href="{{ route('home.index') }}" class="btn btn-small btn-secondary d-block">Home</a>
@endsection
