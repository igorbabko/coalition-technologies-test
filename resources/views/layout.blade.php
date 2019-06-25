<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Coalition Technologies Skills Test') }}</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link href="{{ mix('/css/app.css') }}" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div id="app" class="container mt-5">
            @yield ('content')
        </div>

        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
