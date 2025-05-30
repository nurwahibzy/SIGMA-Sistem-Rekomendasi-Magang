<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header bg-primary text-white rounded-top">
            <h5 class="modal-title">Detail Lowongan</h5>
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
                                <p class="form-control-plaintext mb-0">{{ $periode->lowongan_magang->persyaratan }}</p>
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
            @if ($status == 0)
                <button type="button" class="btn btn-primary" id="btn-daftar">
                    <i class="bi bi-journal-plus"></i> Daftar
                </button>
            @endif
        </div>

    </div>
</div>
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