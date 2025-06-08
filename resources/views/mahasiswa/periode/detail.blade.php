<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header bg-primary text-white rounded-top">
            <h5 class="text-light">Detail Lowongan</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="container mt-4">
                <div class="d-flex justify-content-center align-items-center flex-column">
                    <div class="avatar avatar-2xl mb-3">
                        <img id="preview" src="{{ Storage::exists('public/profil/perusahaan/' . $periode->lowongan_magang->perusahaan->foto_path)
    ? asset('storage/profil/perusahaan/' . $periode->lowongan_magang->perusahaan->foto_path)
    : asset('template/assets/images/mhs.jpeg') }}" alt="Profile Picture" class="rounded-circle"
                            style="width: 120px; height: 120px; border: 5px solid blue; object-fit: cover; cursor: pointer;"
                            onclick="showImagePopup(this.src)" />

                    </div>
                    <h4 class="mt-2 text-center">{{ $periode->lowongan_magang->perusahaan->nama }}</h4>
                    <p class="text-small">{{ $periode->lowongan_magang->perusahaan->telepon }}</p>
                </div>
            </div>
            @if ($lowongan->status == 'baru')
                <div class="container mt-2 mb-3">
                    <div class="text-center">
                    <i class="fas fa-info-circle me-2"></i> Lowngan Baru
                    </div>
                </div>
            @else
                <div class="container mt-2 mb-3">
                    <div class="d-flex justify-content-between align-items-center gap-0">
                        <div class="text-center ps-5">
                            <h5>Tugas</h5>
                            @for ($i = 0; $i < round($lowongan->tugas); $i++)
                                <span class="text-warning">★</span>
                            @endfor
                        </div>
                        <div class="text-center">
                            <h5>Pembinaan</h5>
                            @for ($i = 0; $i < round($lowongan->pembinaan); $i++)
                                <span class="text-warning">★</span>
                            @endfor
                        </div>
                        <div class="text-center pe-5">
                            <h5>Fasilitas</h5>
                            @for ($i = 0; $i < round($lowongan->fasilitas); $i++)
                                <span class="text-warning">★</span>
                            @endfor
                        </div>
                    </div>
                </div>
            @endif
            <div class="d-flex justify-content-between align-items-center mb-3">
                <ul class="nav nav-tabs w-100" id="detailTab" role="tablist">
                    <li class="nav-item flex-fill text-center">
                        <a class="nav-link active" data-bs-toggle="tab" href="#lowongan" role="tab">Lowongan</a>
                    </li>
                    <li class="nav-item flex-fill text-center">
                        <a class="nav-link" data-bs-toggle="tab" href="#perusahaan" role="tab">Perusahaan</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content" id="detailTabContent">
                <div class="tab-pane fade show active" id="lowongan" role="tabpanel">
                    <div class="container mt-4">
                        <div class="d-flex justify-content-between">
                            <div class="w-50 me-2">
                                <div>
                                    <label class="form-label fw-bold">Nama</label>
                                    <div class="border rounded p-2">
                                        <p class="form-control-plaintext mb-0">
                                            {{ $periode->lowongan_magang->nama . ' ' . $periode->nama }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="form-label fw-bold">Tanggal Mulai</label>
                                    <div class="border rounded p-2">
                                        <p class="form-control-plaintext mb-0">
                                            {{ \Carbon\Carbon::parse($periode->tanggal_mulai)->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="w-50 ms-2">
                                <div>
                                    <label class="form-label fw-bold">Bidang</label>
                                    <div class="border rounded p-2">
                                        <p class="form-control-plaintext mb-0">
                                            {{ $periode->lowongan_magang->bidang->nama}}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="form-label fw-bold">Tanggal Selesai</label>
                                    <div class="border rounded p-2">
                                        <p class="form-control-plaintext mb-0">
                                            {{ \Carbon\Carbon::parse($periode->tanggal_selesai)->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container mt-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Persyaratan</label>
                            <div class="border rounded p-2">
                                <p class="form-control-plaintext mb-0">
                                    {!! htmlspecialchars_decode($periode->lowongan_magang->persyaratan) !!}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="container mt-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi</label>
                            <div class="border rounded p-2">
                                <p class="form-control-plaintext mb-0">{{ $periode->lowongan_magang->deskripsi }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="perusahaan" role="tabpanel">
                    <div class="container mt-4">
                        <div class="d-flex justify-content-between">
                            <div class="w-50 me-2">
                                <div class="mt-4">
                                    <label class="form-label fw-bold">Jenis Perusahaan</label>
                                    <div class="border rounded p-2">
                                        <p class="form-control-plaintext mb-0">
                                            {{ $periode->lowongan_magang->perusahaan->jenis_perusahaan->jenis }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="w-50 ms-2"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="w-50 me-2">
                                <div class="mt-4">
                                    <label class="form-label fw-bold">Provinsi</label>
                                    <div class="border rounded p-2">
                                        <p class="form-control-plaintext mb-0">
                                            {{ $periode->lowongan_magang->perusahaan->provinsi }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="w-50 ms-2">
                                <div class="mt-4">
                                    <label class="form-label fw-bold">Daerah</label>
                                    <div class="border rounded p-2">
                                        <p class="form-control-plaintext mb-0">
                                            {{ $periode->lowongan_magang->perusahaan->daerah }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container mt-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi</label>
                            <div class="border rounded p-2">
                                <p class="form-control-plaintext mb-0">
                                    {{ $periode->lowongan_magang->perusahaan->deskripsi }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            @if ($status == 0 && $periode->tanggal_mulai >= now())
                <button type="button" class="btn btn-primary" id="btn-daftar">
                    <i class="bi bi-journal-plus"></i> Daftar
                </button>
            @endif
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
<script>
    $(document).ready(function () {
        $('#btn-daftar').click(function () {
            Swal.fire({
                title: 'Yakin ingin daftar lowongan ini?',
                text: "Tindakan ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, daftar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url(path: 'mahasiswa/periode/' . $periode->id_periode) }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Berhasil daftar.'
                            }).then(() => {
                                window.location.href = '{{ url("/mahasiswa/riwayat") }}';
                            });
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal daftar. Silakan coba lagi.'
                            });
                        }
                    });
                }
            });
        });
    })
</script>