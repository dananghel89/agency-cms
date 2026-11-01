<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="@yield('description')">
    
        <title>@yield('title') | {{ option('site_title') }}</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="48x48">
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

        <!-- Font Awesome -->
        <link href="{{ asset('vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/agency/scss/app.scss', 'resources/agency/js/app.js'])
    </head>
    <body id="page-top">
        <x-navbar />
        <x-masthead />

        {{ $slot }}

        <x-footer />

        <!-- Bootstrap core JS -->
        {{-- <script src="{{ asset('vendor/jquery/jquery.slim.min.js') }}"></script> --}}
        <script src="{{ asset('vendor/bootstrap-5/js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>
