<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="{{ url('/mahasiswa/dashboard') }}">
                        <img src="{{ asset('template/assets/compiled/svg/logo.svg') }}" alt="Logo">
                    </a>
                </div>
                <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                    {{-- toggle theme here --}}
                </div>
                <div class="sidebar-toggler x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>
                <li class="sidebar-item {{ (isset($activeMenu) && $activeMenu == 'dashboard') ? 'active' : '' }}">
                    <a href="{{ url('/mahasiswa/index') }}" class="sidebar-link">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>


                <li class="sidebar-item {{ request()->is('mahasiswa/aktivitas*') ? 'active' : '' }}">
                    <a href="{{ url('/mahasiswa/aktivitas') }}" class="sidebar-link">
                        <i class="bi bi-journal-check"></i>
                        <span>Log Aktivitas</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->is('mahasiswa/riwayat') ? 'active' : '' }}">
                    <a href="{{ url('/mahasiswa/riwayat') }}" class="sidebar-link">
                        <i class="bi bi-clock-history"></i>
                        <span>Riwayat</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->is('mahasiswa/penilaian*') ? 'active' : '' }}">
                    <a href="{{ url('/mahasiswa/penilaian') }}" class="sidebar-link">
                        <i class="bi bi-star-fill"></i>
                        <span>Feedback</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>