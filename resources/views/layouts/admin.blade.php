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

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,400;0,600;0,800;1,200;1,400;1,600;1,800&display=swap" rel="stylesheet">

        <!-- Custom styles for this page -->
        @hasSection ('dataTables')
            <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
        @endif

        <!-- Scripts -->
        @vite(['resources/admin/scss/admin.scss', 'resources/admin/js/admin.js'])
    </head>
    <body id="page-top">
        <div id="wrapper">
            <x-admin.sidebar />

            <div id="content-wrapper" class="d-flex flex-column">
                <div id="content">
                    <x-admin.topbar />

                    <div class="container-fluid">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="h3 text-gray-800">
                                @hasSection ('header')
                                    @yield('header')
                                @else
                                    @yield('title')
                                @endif
                            </h1>

                            @hasSection ('action')
                                <div class="d-block">@yield('action')</div>
                            @endif
                        </div>

                        @hasSection ('content')
                            <div class="mb-4">@yield('content')</div>
                        @endif

                        <div class="d-block">{{ $slot }}</div>
                    </div>

                </div> <!-- #content -->

                <x-admin.footer />
            </div> <!-- #content-wrapper -->
        </div> <!-- #wrapper -->

        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <livewire:auth.logout />

        <!-- Bootstrap core JS -->
        <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap-4/js/bootstrap.bundle.min.js') }}"></script>

        <!-- Core plugin JS -->
        <script src="{{ asset('vendor/jquery.easing/jquery.easing.min.js') }}"></script>

        <!-- Page level plugins -->
        @hasSection ('dataTables')
            <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
            <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
        @endif
    </body>
</html>
