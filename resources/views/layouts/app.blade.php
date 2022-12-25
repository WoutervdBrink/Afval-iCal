<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', '') - Afval-iCal.nl</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-6 mx-auto">
            <h1 class="text-center mb-4 mt-4">Afval-iCal.nl</h1>

            <div class="bg-white p-4 border shadow">
                @yield('content', '')
            </div>
        </div>
    </div>
</div>
</body>
</html>
