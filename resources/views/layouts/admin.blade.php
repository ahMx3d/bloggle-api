<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="author" content="Ahmed Salah" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="admin-id" content="{{ auth()->check()? auth()->id(): '' }}">

    <title>{{ config('app.name', 'Laravel') }} - Dashboard</title>

	<!-- Favicons -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />

    <!-- Custom fonts for this template-->
    <link href="{{ asset('backend/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css" />
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet" />

    {{-- Bootstrap File Input Plugin --}}
    <link
        rel="stylesheet"
        type="text/css"
        media="all"
        href="{{ asset('backend/vendor/bootstrap-file-input/css/fileinput.min.css') }}" />

    <!-- Custom styles for this template-->
    <link href="{{ asset('backend/css/sb-admin-2.min.css') }}" rel="stylesheet" />

    <style>
        .bloggle-sidebar-brand{
            background: #3E3E3E !important;
        }
        .bloggle-navbar-nav{
            background: #DB714E !important;
        }
    </style>
    @yield('style')
    @livewireStyles
</head>

<body id="page-top">
    <div id="app">
        <!-- Page Wrapper -->
        <div id="wrapper">
            @include('partials.backend.sidebar')

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">
                <!-- Main Content -->
                <div id="content">
                    @include('partials.backend.header')
                    <!-- Begin Page Content -->
                    <div class="container-fluid">
                        @include('partials.flash')
                        @yield('content')
                    </div>
                    <!-- /.container-fluid -->
                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; {{ config('app.name') }} {{ now()->year }} All Rights Reserved</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->
            </div>
            <!-- End of Content Wrapper -->
        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('backend/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('backend/js/sb-admin-2.min.js') }}"></script>

    {{-- Bootstrap File Input Plugin --}}
    <script src="{{ asset('backend/vendor/bootstrap-file-input/js/plugins/piexif.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('backend/vendor/bootstrap-file-input/js/plugins/sortable.min.js') }}" type="text/javascript"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script> --}}
    {{-- <script src="{{ asset('backend/vendor/bootstrap-file-input/js/plugins/purify.min.js') }}"></script> --}}
    <script src="{{ asset('backend/vendor/bootstrap-file-input/js/fileinput.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/bootstrap-file-input/themes/fa/theme.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/bootstrap-file-input/themes/fas/theme.min.js') }}"></script>

    <script src="{{ asset('backend/js/custom.js') }}"></script>
    @yield('script')
    @livewireScripts
    @stack('scripts')
</body>

</html>
