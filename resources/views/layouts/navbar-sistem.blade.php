<header>
    <nav class="navbar navbar-expand navbar-light navbar-top">
        <div class="container-fluid">
            <!-- <a href="#" class="burger-btn d-block">
                <i class="bi bi-justify fs-3"></i>
            </a> -->
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-lg-0">
                    <li class="nav-item dropdown me-1">
                    </li>
                </ul>
                @if(Auth::check() && Auth::user()->level->kode == 'ADM')
                            <div class="dropdown">
                                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-menu d-flex">
                                        <div class="user-name text-end me-3">
                                            <h6 class="mb-0 text-gray-600">{{ Auth::user()->admin->nama ?? 'Nama Admin' }}</h6>
                                            <p class="mb-0 text-sm text-gray-600">{{ Auth::user()->level->role ?? 'Role' }}</p>
                                        </div>
                                        <div class="user-img d-flex align-items-center">
                                            <div class="avatar avatar-md">
                                                <img src="{{ Storage::exists('public/profil/akun/' . Auth::user()->foto_path)
                    ? asset('storage/profil/akun/' . Auth::user()->foto_path)
                    : asset('template/assets/images/mhs.jpeg') }}" alt="Foto Profil"  />
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"
                                    style="min-width: 11rem;">
                                    <li>
                                        <h6 class="dropdown-header">Halo, {{ Auth::user()->admin->nama ?? 'Nama Admin' }}</h6>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ url('/admin/profil') }}"><i
                                                class="icon-mid bi bi-person me-2"></i>Profil Saya</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ url('/logout') }}">
                                            <i class="icon-mid bi bi-box-arrow-left me-2"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                @elseif(Auth::check() && Auth::user()->level->kode == 'MHS')
                    <div class="dropdown">
                        <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-menu d-flex">
                                <div class="user-name text-end me-3">
                                    <h6 class="mb-0 text-gray-600">{{ Auth::user()->mahasiswa->nama ?? 'Nama Mahasiswa' }}
                                    </h6>
                                    <p class="mb-0 text-sm text-gray-600">{{ Auth::user()->level->role ?? 'Role' }}</p>
                                </div>
                                <div class="user-img d-flex align-items-center">
                                    <div class="avatar avatar-md">
                                    <img src="{{ Storage::exists('public/profil/akun/' . Auth::user()->foto_path)
                    ? asset('storage/profil/akun/' . Auth::user()->foto_path)
                    : asset('template/assets/images/mhs.jpeg') }}" alt="Foto Profil"  />
                                    </div>
                                </div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"
                            style="min-width: 11rem;">
                            <li>
                                <h6 class="dropdown-header">Halo, {{ Auth::user()->mahasiswa->nama ?? 'Nama Mahasiswa' }}
                                </h6>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('mahasiswa.profil') }}"><i
                                        class="icon-mid bi bi-person me-2"></i>Profil Saya</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ url('/logout') }}">
                                    <i class="icon-mid bi bi-box-arrow-left me-2"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                @elseif(Auth::check() && Auth::user()->level->kode == 'DSN')
                    <div class="dropdown">
                        <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-menu d-flex">
                                <div class="user-name text-end me-3">
                                    <h6 class="mb-0 text-gray-600">{{ Auth::user()->dosen->nama ?? 'Nama Dosen' }}</h6>
                                    <p class="mb-0 text-sm text-gray-600">{{ Auth::user()->level->role ?? 'Role' }}</p>
                                </div>
                                <div class="user-img d-flex align-items-center">
                                    <div class="avatar avatar-md">
                                    <img src="{{ Storage::exists('public/profil/akun/' . Auth::user()->foto_path)
                    ? asset('storage/profil/akun/' . Auth::user()->foto_path)
                    : asset('template/assets/images/mhs.jpeg') }}" alt="Foto Profil"  />
                                    </div>
                                </div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"
                            style="min-width: 11rem;">
                            <li>
                                <h6 class="dropdown-header">Halo, {{ Auth::user()->dosen->nama ?? 'Nama Dosen' }}</h6>
                            </li>
                            <li><a class="dropdown-item" href="{{ url('/dosen/profil') }}"><i
                                class="icon-mid bi bi-person me-2"></i>Profil Saya</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ url('/logout') }}">
                                    <i class="icon-mid bi bi-box-arrow-left me-2"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </nav>
</header>