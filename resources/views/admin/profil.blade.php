@extends('layouts.tamplate')
@section('content')
    <div class="page-heading">
        <h3>Profil Saya</h3>
    </div>

    <section class="section">
        <div class="row">
            <!-- Kolom Foto -->
            <div class="col-12 col-lg-4">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <div class="avatar avatar-2xl mb-3">
                                <label for="file">
                                    <img id="preview" src="{{ Storage::exists('public/profil/akun/' . Auth::user()->foto_path)
        ? asset('storage/profil/akun/' . Auth::user()->foto_path)
        : asset('template/assets/images/mhs.jpeg') }}" alt="Profile Picture"
                                        class="rounded-circle"
                                        style="width: 120px; height: 120px; border: 5px solid blue; object-fit: cover;"
                                        onclick="showImagePopup(this.src)">
                                </label>
                            </div>
                            <h4 class="mt-2 text-center">{{ Auth::user()->admin->nama ?? 'N/A' }}</h4>
                            <p class="text-small">{{ Auth::user()->id_user ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Form -->
            <div class="col-md-8">
                <div class="card p-4 shadow">
                    <form id="form-profile" class="row g-3">
                        @csrf

                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <input type="role" class="form-control" id="role"
                                value="{{ Auth::user()->admin->akun->level->role }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ID</label>
                            <input type="text" class="form-control" id="nim" value="{{ Auth::user()->id_user ?? '-' }}"
                                disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" value="{{ Auth::user()->admin->nama ?? '-' }}"
                                disabled>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir"
                                value="{{ Auth::user()->admin->tanggal_lahir ?? '-' }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" value="{{ Auth::user()->admin->email }}"
                                disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telepon</label>
                            <input type="text" class="form-control" id="no_telepon"
                                value="{{ Auth::user()->admin->telepon ?? '-' }}" disabled>
                        </div>



                        <div class="col-md-12">
                            <label class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="alamat"
                                value="{{ Auth::user()->admin->alamat ?? '-' }}" disabled>
                        </div>

                        <div class="col-12 text-end">
                            <a href="{{ url('admin/profil/edit') }}" class="btn btn-primary">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <div id="image-popup" style="
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100vw; height: 100vh;
        background-color: rgba(0,0,0,0.8);
        z-index: 1050;
        justify-content: center;
        align-items: center;
    ">
        <span id="close-popup" style="
            position: absolute;
            top: 20px; right: 30px;
            font-size: 30px;
            color: white;
            cursor: pointer;
            z-index: 1060;
        ">&times;</span>
        <img id="popup-img" src="" alt="Full Image" style="
            max-width: 90vw;
            max-height: 90vh;
            border-radius: 10px;
            box-shadow: 0 0 10px #000;
            object-fit: contain;
        ">
    </div>
    <script>
        function showImagePopup(src) {
            const popup = document.getElementById('image-popup');
            const popupImg = document.getElementById('popup-img');
            popupImg.src = src;
            popup.style.display = 'flex';
        }

        document.getElementById('close-popup').addEventListener('click', function () {
            document.getElementById('image-popup').style.display = 'none';
        });

        document.getElementById('image-popup').addEventListener('click', function (e) {
            if (e.target.id === 'image-popup') {
                document.getElementById('image-popup').style.display = 'none';
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === "Escape") {
                document.getElementById('image-popup').style.display = 'none';
            }
        });
    </script>
@endsection