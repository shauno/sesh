<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="./favicon.ico">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sesh.co.za - Surf Better Waves') }}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>

<body>

<main role="main">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8" id="app">
                @yield('content')
                <router-view></router-view>
            </div>
        </div>
    </div>
</main>

</body>
</html>
