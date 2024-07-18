<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="@yield('description')">
    
        <title>@yield('title') | {{ option('site_title') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        <!-- Font Awesome -->
        <link href="{{ asset('vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Roboto+Slab:wght@100;300;400;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/agency/scss/app.scss', 'resources/agency/js/app.js'])
    </head>
    <body id="page-top">
        <x-navbar />
        <x-masthead />

        {{ $slot }}

        <x-footer />

        <!-- Bootstrap core JS -->
        <script src="{{ asset('vendor/jquery/jquery.slim.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap-5/js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>
