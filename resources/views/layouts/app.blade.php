<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', '') - Afval-iCal.nl</title>
    @vite('resources/css/app.scss')
    <meta name="description" content="Genereer een iCal-URL met ophaalmomenten voor het afval in jouw buurt.">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-9 mx-auto">
            <h1 class="text-center mb-4 mt-4">Afval-iCal.nl</h1>

            <div class="bg-white p-4 mb-4 border shadow">
                @yield('content', '')
            </div>

            <div class="px-4 text-center">
                <a href="{{ route('privacy') }}" class="text-dark">Privacy</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
