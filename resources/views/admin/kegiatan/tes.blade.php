@extends('layouts.tamplate')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <ul class="nav nav-tabs w-100" id="detailTab" role="tablist">
            <li class="nav-item flex-fill text-center">
                <a class="nav-link active" data-bs-toggle="tab" href="#info" role="tab">Info Magang</a>
            </li>
            <li class="nav-item flex-fill text-center">
                <a class="nav-link" data-bs-toggle="tab" href="#profil" role="tab">Profil Mahasiswa</a>
            </li>
        </ul>
    </div>
    <div class="tab-content" id="detailTabContent">
        <div class="tab-pane fade show active" id="info" role="tabpanel">
            <form id="form-status" method="POST" action="{{ url('admin/kegiatan/edit/' . $magang->id_magang) }}">
                @csrf
                <div class="row">
                    <div class="col-md-7">
                        <div class="card shadow p-4">
                            <div class="row">
                                <div class="col-md-3 text-center mb-3 mb-md-0">
                                    <img src="{{ Storage::exists('public/profil/perusahaan/' . $magang->periode_magang->lowongan_magang->perusahaan->foto_path)
        ? asset('storage/profil/perusahaan/' . $magang->periode_magang->lowongan_magang->perusahaan->foto_path)
        : asset('template/assets/images/mhs.jpeg') }}" alt="Profile Picture" class="img-fluid rounded"
                                        style="width: 200px; height: 200px; border: 2px solid blue; object-fit: cover; cursor: pointer;"
                                        onclick="showImagePopup(this.src)" />
                                </div>

                                <div class="col-md-9">
                                    <div class="company-info">
                                        <table style="width: 100%; border-collapse: collapse;">
                                            <tr>
                                                <td style="padding-bottom: 8px; vertical-align: top;">
                                                    <h4 class="fw-bold mb-1">
                                                        <i class="fas fa-building me-2"></i>
                                                    </h4>
                                                </td>
                                                <td style="padding-bottom: 8px; vertical-align: top;">
                                                    <h4 class="fw-bold mb-1">
                                                        {{ $magang->periode_magang->lowongan_magang->perusahaan->nama }}
                                                    </h4>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 8px; vertical-align: top;">
                                                    <p class="fw-bold mb-1">
                                                        <i class="fas fa-map-marker-alt me-2"></i>
                                                    </p>
                                                </td>
                                                <td style="padding-bottom: 8px; vertical-align: top;">
                                                    <p class="fw-bold mb-1">
                                                        {{ $magang->periode_magang->lowongan_magang->perusahaan->daerah }},
                                                        {{ $magang->periode_magang->lowongan_magang->perusahaan->provinsi }}
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 8px; vertical-align: top;">
                                                    <p class="fw-bold mb-1">
                                                        <i class="fas fa-industry me-2"></i>
                                                    </p>
                                                </td>
                                                <td style="padding-bottom: 8px; vertical-align: top;">
                                                    <p class="fw-bold mb-1">
                                                        {{ $magang->periode_magang->lowongan_magang->perusahaan->jenis_perusahaan->jenis }}
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 8px; vertical-align: top;">
                                                    <p class="fw-bold mb-0">
                                                        <i class="fas fa-info-circle me-2"></i>
                                                    </p>
                                                </td>
                                                <td style="padding-bottom: 8px; vertical-align: top;">
                                                    <p class="fw-bold mb-0">
                                                        {{ $magang->periode_magang->lowongan_magang->perusahaan->deskripsi }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card shadow p-4">
                                @if(count($activeButton))
                                    <div class="w-100">
                                        <label class="form-label fw-bold mb-2">Status:</label>
                                        <div class="d-flex flex-wrap gap-3 ms-2">
                                            @foreach ($activeButton as $status)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="status" value="{{ $status }}"
                                                        id="status_{{ $status }}">
                                                    <label class="form-check-label"
                                                        for="status_{{ $status }}">{{ ucfirst($status) }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div id="status-error" class="text-danger mt-2 d-none">Silakan pilih salah satu status.
                                        </div>
                                    </div>
                                @endif

                                @if($magang->dosen)
                                    {{ $magang->dosen->nama }}
                                @elseif($magang->status == 'ditolak')
                                    -
                                @else
                                    <select name="id_dosen" class="form-select" id="input-dosen" required>
                                        <option value="">Pilih Dosen Pembimbing</option>
                                        @foreach($dosen as $d)
                                            <option value="{{ $d['id_dosen'] }}">{{ $d['nama'] }}</option>
                                        @endforeach
                                    </select>
                                @endif

                                <div id="alasan_penolakan_row" class="d-none">
                                    <textarea name="alasan_penolakan" id="alasan_penolakan" class="form-control" rows="3"
                                        placeholder="Masukkan alasan penolakan..."></textarea>
                                    <div class="invalid-feedback">Harap masukkan alasan penolakan.</div>

                                </div>


                                <div class="w-100 d-flex justify-content-end gap-2 mt-2">
                                    <button type="button" class="btn btn-danger" id="btn-hapus">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                    <button type="submit" class="btn btn-info"><i class="bi bi-save"></i> Simpan</button>
                                </div>
                            </div>
                        </div>  
                </div>
                <div class="card">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3 mb-md-0">
                            <img src="{{ Storage::exists('public/profil/perusahaan/' . $magang->periode_magang->lowongan_magang->perusahaan->foto_path)
        ? asset('storage/profil/perusahaan/' . $magang->periode_magang->lowongan_magang->perusahaan->foto_path)
        : asset('template/assets/images/mhs.jpeg') }}" alt="Profile Picture" class="img-fluid rounded"
                                style="width: 200px; height: 200px; border: 2px solid blue; object-fit: cover; cursor: pointer;"
                                onclick="showImagePopup(this.src)" />
                        </div>

                        <div class="col-md-9">
                            <div class="company-info">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="padding-bottom: 8px; vertical-align: top;">
                                            <h4 class="fw-bold mb-1">
                                                <i class="fas fa-building me-2"></i>
                                            </h4>
                                        </td>
                                        <td style="padding-bottom: 8px; vertical-align: top;">
                                            <h4 class="fw-bold mb-1">
                                                {{ $magang->periode_magang->lowongan_magang->perusahaan->nama }}
                                            </h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 8px; vertical-align: top;">
                                            <p class="fw-bold mb-1">
                                                <i class="fas fa-map-marker-alt me-2"></i>
                                            </p>
                                        </td>
                                        <td style="padding-bottom: 8px; vertical-align: top;">
                                            <p class="fw-bold mb-1">
                                                {{ $magang->periode_magang->lowongan_magang->perusahaan->daerah }},
                                                {{ $magang->periode_magang->lowongan_magang->perusahaan->provinsi }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 8px; vertical-align: top;">
                                            <p class="fw-bold mb-1">
                                                <i class="fas fa-industry me-2"></i>
                                            </p>
                                        </td>
                                        <td style="padding-bottom: 8px; vertical-align: top;">
                                            <p class="fw-bold mb-1">
                                                {{ $magang->periode_magang->lowongan_magang->perusahaan->jenis_perusahaan->jenis }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 8px; vertical-align: top;">
                                            <p class="fw-bold mb-0">
                                                <i class="fas fa-info-circle me-2"></i>
                                            </p>
                                        </td>
                                        <td style="padding-bottom: 8px; vertical-align: top;">
                                            <p class="fw-bold mb-0">
                                                {{ $magang->periode_magang->lowongan_magang->perusahaan->deskripsi }}
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <table class="table table-borderless">
                                                                                <tr>
                                                                                    <th>Status</th>
                                                                                    <td>
                                                                                        <span class="badge 
                                                                                                                        @if($magang->status == 'diterima') bg-warning
                                                                                                                        @elseif($magang->status == 'lulus') bg-success
                                                                                                                        @elseif($magang->status == 'ditolak') bg-danger
                                                                                                                            @else bg-secondary
                                                                                                                        @endif">
                                                                                            {{ ucfirst($magang->status ?? '-') }}
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Tanggal Pengajuan</th>
                                                                                    <td>{{ $magang->tanggal_pengajuan }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Dosen Pembimbing</th>
                                                                                    <td>
                                                                                        @if($magang->dosen)
                                                                                            {{ $magang->dosen->nama }}
                                                                                        @elseif($magang->status == 'ditolak')
                                                                                            -
                                                                                        @else
                                                                                            <select name="id_dosen" class="form-select" id="input-dosen" required>
                                                                                                <option value="">Pilih Dosen Pembimbing</option>
                                                                                                @foreach($dosen as $d)
                                                                                                    <option value="{{ $d['id_dosen'] }}">{{ $d['nama'] }}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        @endif
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Alasan Penolakan</th>
                                                                                    <td>{{ $magang->alasan_penolakan }}</td>
                                                                                </tr>

                                                                                <tr id="alasan_penolakan_row" class="d-none">
                                                                                    <td>
                                                                                        <textarea name="alasan_penolakan" id="alasan_penolakan" class="form-control" rows="3"
                                                                                            placeholder="Masukkan alasan penolakan..."></textarea>
                                                                                        <div class="invalid-feedback">Harap masukkan alasan penolakan.</div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table> -->

            </form>
        </div>
        <div class="tab-pane fade" id="profil" role="tabpanel">
            <div class="row">
                <div class="col-md-4">
                    <div class="position-sticky" style="top: 90px;">
                        <div class="card p-4 text-center shadow">
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <div class="avatar avatar-2xl mb-3">
                                    <img id="preview" src="{{ Storage::exists('public/profil/akun/' . $mahasiswa->akun->foto_path)
        ? asset('storage/profil/akun/' . $mahasiswa->akun->foto_path)
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
                                        value="{{ $mahasiswa->akun->id_user ?? '-' }}" disabled>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="nama" value="{{ $mahasiswa->nama ?? '-' }}"
                                        disabled>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Alamat</label>
                                    <input type="text" class="form-control" id="alamat"
                                        value="{{ $mahasiswa->alamat ?? '-' }}" disabled>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Telepon</label>
                                    <input type="text" class="form-control" id="no_telepon"
                                        value="{{ $mahasiswa->telepon ?? '-' }}" disabled>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control" id="tanggal_lahir"
                                        value="{{ $mahasiswa->tanggal_lahir ?? '-' }}" disabled>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" value="{{ $mahasiswa->email }}"
                                        disabled>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <ul class="nav nav-tabs" id="detailTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#keahlian" role="tab">Keahlian</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#perusahaan" role="tab">Preferensi
                                    Perusahaan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#lokasi" role="tab">Preferensi Lokasi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#pengalaman" role="tab">Pengalaman</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#dokumen" role="tab">Dokumen</a>
                            </li>
                        </ul>
                    </div>


                    <div class="tab-content" id="detailTabContent">
                        <div class="tab-pane fade show active" id="keahlian" role="tabpanel">

                            <div class="section-wrapper mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="fw-bold mb-0">Keahlian</h5>
                                </div>

                                <p class="text-muted mb-3">Keahlian yang dimiliki beserta skala prioritasnya</p>

                                @if (count($keahlian))
                                    @foreach ($keahlian as $item)
                                        <div class="card mb-4">
                                            <div class="card-body shadow">
                                                <div class="d-flex mb-3">
                                                    <div class="d-inline-block me-2 fw-bold">
                                                        <h5>{{ $item->prioritas }}</h5>
                                                    </div>
                                                    <div class="d-inline-block fw-bold">
                                                        <h5>{{ $item->bidang->nama }}</h5>
                                                    </div>
                                                </div>

                                                <div>
                                                    <div class="px-3 py-1 rounded d-inline-block">
                                                        {{ $item->keahlian }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="perusahaan" role="tabpanel">
                            <!-- PREFERENSI PERUSAHAAN SECTION -->
                            <div class="section-wrapper mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="fw-bold mb-0">Preferensi Perusahaan</h5>
                                </div>

                                <p class="text-muted mb-3">
                                    Jenis perusahaan.
                                </p>
                                @if (count($preferensi_perusahaan))
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                            @foreach ($preferensi_perusahaan as $item)
                                                <div class="bg-primary bg-opacity-10 px-3 py-1 rounded d-inline-block">
                                                    {{ $item->jenis_perusahaan->jenis }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                        <div class="tab-pane fade" id="lokasi" role="tabpanel">
                            <div class="section-wrapper mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="fw-bold mb-0">Preferensi Lokasi</h5>
                                </div>

                                <p class="text-muted mb-3">
                                    Preferensi lokasi magang.
                                </p>
                                @if (!empty($preferensi_lokasi))
                                    <div class="card mb-4 shadow">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3 mb-md-0">
                                                    <p class="mb-1 text-muted">Provinsi</p>
                                                    <div
                                                        class="bg-primary bg-opacity-10 text-body px-3 py-1 rounded d-inline-block">
                                                        {{ $preferensi_lokasi->provinsi}}
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="mb-1 text-muted">Daerah</p>
                                                    <div
                                                        class="bg-primary bg-opacity-10 text-body px-3 py-1 rounded d-inline-block">
                                                        {{ $preferensi_lokasi->daerah}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pengalaman" role="tabpanel">
                            <div class="section-wrapper mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="fw-bold mb-0">Pengalaman</h5>
                                </div>

                                <p class="text-muted mb-3">
                                    Pengalaman yang pernah dijalani.
                                </p>
                                @if (count($pengalaman))
                                    <fieldset class="form-section" id="section-pengalaman" disabled>
                                        <div id="pengalaman-container">
                                            @foreach ($pengalaman as $item)
                                                <div class="card mb-4 shadow">
                                                    <div class="card-body">
                                                        <div>
                                                            <div class="px-3 py-1 rounded d-inline-block">
                                                                {{ $item->deskripsi }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </fieldset>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="dokumen" role="tabpanel">
                            <div class="section-wrapper mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="fw-bold mb-0">Dokumen</h5>
                                </div>
                                @if (count($dokumen))
                                    <div id="dokumen-container">
                                        @foreach ($dokumen as $item)
                                            <div class="card mb-4 shadow">
                                                <div class="card-body">
                                                    <div class="bg-primary bg-opacity-10 px-3 py-1 rounded d-inline-block  mb-4">
                                                        {{  $item->nama }}
                                                    </div>
                                                    <div>
                                                        <embed src="{{ url('storage/dokumen/' . $item->file_path) }}"
                                                            type="application/pdf" width="100%" height="1000px" />
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
@push('css')
@endpush
@push('js')
    <script>
        $(document).ready(function () {
            $('input[name="status"]').on('change', function () {
                const selectedStatus = $(this).val();
                if (selectedStatus === 'ditolak') {
                    $('#input-dosen').prop('disabled', true).val('');
                    $('#alasan_penolakan_row').removeClass('d-none');
                } else {
                    $('#input-dosen').prop('disabled', false);
                    $('#alasan_penolakan_row').addClass('d-none');
                }
            });

            $('input[name="status"]:checked').trigger('change');

            $("#form-status").validate({
                rules: {
                    status: { required: true },
                    @if(!$magang->dosen)
                        id_dosen: { required: true },
                    @endif
                                                                                },
            messages: {
            status: { required: "Silakan pilih salah satu status." },
            @if(!$magang->dosen)
                id_dosen: { required: "Silakan pilih dosen pembimbing." },
            @endif
                                                                                },
            ignore: [],
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-check, .form-select').append(error);
            },
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function (form) {
                // Custom validation for alasan_penolakan
                const status = $('input[name="status"]:checked').val();
                const alasan = $('#alasan_penolakan').val().trim();

                if (status === 'ditolak' && alasan === '') {
                    $('#alasan_penolakan').addClass('is-invalid');
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Harap masukkan alasan penolakan.' });
                    return false;
                }

                // Submit form via AJAX
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Data berhasil disimpan.'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message || 'Terjadi kesalahan saat menyimpan.'
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan pada server.' });
                    }
                });
            }
                                                                            });

        // Delete handler
        $('#btn-hapus').click(function () {
            Swal.fire({
                title: 'Yakin ingin menghapus data ini?',
                text: "Tindakan ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('admin/kegiatan/edit/' . $magang->id_magang) }}",
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Data berhasil dihapus.'
                            }).then(() => {
                                window.location.href = '{{ url("admin/kegiatan") }}';
                            });
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal menghapus data. Silakan coba lagi.'
                            });
                        }
                    });
                }
            });
        });
                                                                        });
    </script>
@endpush