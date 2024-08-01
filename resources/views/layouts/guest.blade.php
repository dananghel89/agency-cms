<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="@yield('description')">

        <title>@yield('title') | {{ config('sb.admin_title') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

        <!-- Font Awesome -->
        <link href="{{ asset('vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,400;0,600;0,800;1,200;1,400;1,600;1,800&display=swap" rel="stylesheet">

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
