@extends('layouts.tamplate')
@section('content')
    @if (empty($magang))
    @else
        <div class="page-heading">
            <h3>Aktivitas</h3>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-md-4">
                    <div class="position-sticky" style="top: 90px;">
                        <div class="card p-4 shadow">
                            <h5 class="card-title mb-5">Dosen Pembimbing</h5>
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <div class="avatar avatar-2xl mb-3">
                                    <img src="{{ Storage::exists('public/profil/akun/' . $magang->dosen->akun->foto_path)
                ? asset('storage/profil/akun/' . $magang->dosen->akun->foto_path)
                : asset('template/assets/images/mhs.jpeg') }}" alt="Profile Picture" class="rounded-circle"
                                        style="width: 120px; height: 120px; border: 5px solid blue; object-fit: cover; cursor: pointer;"
                                        onclick="showImagePopup(this.src)" />

                                </div>
                            </div>
                            <form id="form-left-panel" class="text-start mt-3">
                                @csrf
                                <div class="mb-2">
                                    <label class="form-label">NIM</label>
                                    <input type="text" class="form-control" id="nim"
                                        value="{{ $magang->dosen->akun->id_user ?? '-' }}" disabled>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="nama" value="{{ $magang->dosen->nama ?? '-' }}"
                                        disabled>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Telepon</label>
                                    <input type="text" class="form-control" id="no_telepon"
                                        value="{{ $magang->dosen->telepon ?? '-' }}" disabled>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            @if ($hasActivityToday == 0)
                                <button onclick="modalAction('{{ url('/mahasiswa/aktivitas/' . $id_magang . '/tambah') }}')"
                                    class="btn btn-primary">
                                    <i class="bi bi-plus"></i> Tambah Aktivitas
                                </button>
                            @endif
                        </div>
                        <div class="card-body">
                            @if (count($aktivitas))
                                <div class="table-responsive">
                                    <table class="table" id="table1">
                                        <colgroup>
                                            <col style="width: 100px;">
                                            <col style="width: 100px;">
                                            <col style="width: 100px;">
                                            <col style="width: 100px;">
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($aktivitas as $i => $item)
                                                <tr>
                                                    <td>{{ $i + 1 }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info btn-detail"
                                                            onclick="modalAction('{{ url('/mahasiswa/aktivitas/' . $id_magang . '/detail/' . $item->id_aktivitas) }}')">
                                                            Detail
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center">
                                    <p class="mt-4">Belum ada aktivitas tersedia</p>
                                </div>
                            @endif
                        </div>

                        <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
                            data-keyboard="false" data-width="75%" aria-hidden="true"></div>
                    </div>
                </div>
            </div>
        </section>
        <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
            data-keyboard="false" data-width="75%" aria-hidden="true"></div>
        </div>

        <div id="image-popup-dosen" style="
                            display: none;
                            position: fixed;
                            top: 0; left: 0;
                            width: 100vw; height: 100vh;
                            background-color: rgba(0,0,0,0.8);
                            z-index: 1050;
                            justify-content: center;
                            align-items: center;
                        ">
            <span id="close-popup-dosen" style="
                                position: absolute;
                                top: 20px; right: 30px;
                                font-size: 30px;
                                color: white;
                                cursor: pointer;
                                z-index: 1060;
                            ">&times;</span>
            <img id="popup-img-dosen" src="" alt="Full Image" style="
                                max-width: 90vw;
                                max-height: 90vh;
                                border-radius: 10px;
                                box-shadow: 0 0 10px #000;
                                object-fit: contain;
                            ">
        </div>
        <script>
            function showImagePopup(src) {
                const popup = document.getElementById('image-popup-dosen');
                const popupImg = document.getElementById('popup-img-dosen');
                popupImg.src = src;
                popup.style.display = 'flex';
            }

            document.getElementById('close-popup-dosen').addEventListener('click', function () {
                document.getElementById('image-popup-dosen').style.display = 'none';
            });

            document.getElementById('image-popup-dosen').addEventListener('click', function (e) {
                if (e.target.id === 'image-popup-dosen') {
                    document.getElementById('image-popup-dosen').style.display = 'none';
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === "Escape") {
                    document.getElementById('image-popup-dosen').style.display = 'none';
                }
            });
        </script>
    @endif
@endsection
@push('css')
@endpush
@push('js')
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }
    </script>
@endpush