<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="@yield('description')">

        <title>@yield('title') | {{ config('project.admin_title') }}</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="48x48">
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

        <!-- Font Awesome -->
        <link href="{{ asset('vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/admin/scss/admin.scss', 'resources/admin/js/admin.js'])
    </head>
    <body class="bg-gradient-primary">
        <div class="container">
            @hasSection ('outerRow')
                <div class="row justify-content-center">
                    <div class="col-xl-10 col-lg-12 col-md-9">
                        <div class="card o-hidden border-0 shadow-lg my-5">
                            <div class="card-body p-0">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        {{ $slot }}
                    </div>
                </div>
            @endif
        </div> <!-- .container -->

        <!-- Bootstrap core JS -->
        <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap-4/js/bootstrap.bundle.min.js') }}"></script>

        <!-- Core plugin JS -->
        <script src="{{ asset('vendor/jquery.easing/jquery.easing.min.js') }}"></script>
    </body>
</html>
