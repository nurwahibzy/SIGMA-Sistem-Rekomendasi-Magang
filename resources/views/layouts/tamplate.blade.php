<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SIGMA</title>

    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/iconly.css') }}" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
</head>

<body>
    <script src="{{ asset('template/assets/static/js/initTheme.js') }}"></script>

    <div id="app">
        {{-- Sidebar --}}
        @include('layouts.sidebar')

        <div id="main" class="layout-navbar navbar-fixed">
            {{-- Navbar --}}
            @include('layouts.navbar')

            <div id="main-content">
                <div class="page-content">
                    <div class="container">
                        @yield('content')
                    </div>
                </div>

                {{-- Footer --}}
                @include('layouts.footer')
            </div>
        </div>
    </div>

    <!-- Load jQuery dulu -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Load DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>

    <script src="{{ asset('template/assets/compiled/js/app.js') }}"></script>

    @stack('scripts')
</body>

</html>
