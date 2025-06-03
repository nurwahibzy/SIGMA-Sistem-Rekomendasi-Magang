<header>
    <div class="header-top">
        <div class="d-flex justify-content-between align-items-center">
            <div class="ms-5">
                <div class="logo">
                    <a href="index.html"><img src="{{  asset('template/assets/compiled/svg/logo.svg')}}" alt="Logo"
                            style="width: 120px; height: auto;"></a>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <ul class="nav nav-tabs w-100" id="detailTab" role="tablist">
                    <li class="menu-item me-3 ms-3">
                        <a class="nav-link active" data-bs-toggle="tab" href="#beranda" role="tab">
                            <span><i class="bi bi-stack"></i> Beranda</span></a>
                    </li>
                    <li class="menu-item me-3 ms-3">
                        <a class="nav-link" data-bs-toggle="tab" href="#program" role="tab">
                            <span><i class="bi bi-stack"></i> Program Magang</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="me-5 d-flex justify-content-between align-items-center">
                <div class="form-check form-switch fs-6 me-5">
                    <input class="form-check-input  me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                    <label class="form-check-label"></label>
                </div>
                @if (Auth::check())
                    <a class="btn btn-primary font-bold" href="{{ url('/login') }}">
                        Sistem
                    </a>
                @else
                    <a class="btn btn-primary font-bold" href="{{ url('/login') }}">
                        Login
                    </a>
                @endif
            </div>
        </div>
    </div>
</header>