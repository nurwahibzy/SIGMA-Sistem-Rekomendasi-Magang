<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGMA</title>

    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/iconly.css') }}">
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

    <script src="{{ asset('template/assets/compiled/js/app.js') }}"></script>
    @stack('scripts')
</body>

</html>