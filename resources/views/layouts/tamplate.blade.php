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
    <link rel="stylesheet"
        href="{{ asset('template/assets/extensions/choices.js/public/assets/styles/choices.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css ">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />

    <style>
        .checkbox-label {
            display: inline-block;
            padding: 10px 15px;
            margin: 5px;
            border-radius: 10px;
            border: 2px solid var(--bs-primary);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .checkbox-input {
            display: none;
        }

        .checkbox-input:checked+.checkbox-label {
            background-color: var(--bs-primary);
            color: white;
            border: 2px solid var(--bs-primary);
        }
    </style>
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
    <!-- <script src="{{ asset('template/assets/extensions/jquery/jquery.min.js') }}"></script> -->
    <script src="{{ asset('template/assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('template/assets/static/js/pages/datatables.js') }}"></script>

    <!-- Load DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script src="{{ asset('template/assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('template/assets/extensions/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <script src="{{ asset('template/assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
    <script src="{{ asset('template/assets/static/js/pages/form-element-select.js') }}"></script>

    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>

    @stack('scripts')
    @stack('js')
</body>

</html>