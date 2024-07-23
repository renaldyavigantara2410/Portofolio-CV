<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <link href="{{ asset('/images/nila.svg')}}" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SISKUPALA') }}</title>

        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="{{ asset('/css/app.css')}}" />
        <!-- END: CSS Assets-->
    </head>
    <!-- END: Head -->
    <body>
        <div class="login">
            @yield('content')
        </div>
        <!-- BEGIN: JS Assets-->
        <script src="{{ asset('/js/app.js')}}"></script>
        <!-- END: JS Assets-->
    </body>
</html>
