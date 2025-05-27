<!-- Meta CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2 @11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2 @11"></script>

<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" data-status="{{ $magang->status }}">
        <div class="modal-header">
            <h4 class="modal-title" id="modalLabel">Detail Aktivitas Mahasiswa</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <!-- Info Mahasiswa -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>NIM:</strong> <span id="nim-mahasiswa">{{ $magang->mahasiswa->akun->id_user ?? '-' }}</span>
                </div>
                <div class="col-md-6">
                    <strong>Nama:</strong> <span id="nama-mahasiswa">{{ $magang->mahasiswa->nama ?? '-' }}</span>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-12">
                    <strong>Perusahaan:</strong> <span id="perusahaan">{{ $magang->periode_magang->lowongan_magang->perusahaan->nama ?? '-' }}</span>
                </div>
            </div>
            <hr>

            <!-- Tab Navigation -->
            <ul class="nav nav-tabs" id="detailTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="aktivitas-tab" data-bs-toggle="tab" data-bs-target="#aktivitas" type="button" role="tab">
                        Log Aktivitas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="evaluasi-tab" data-bs-toggle="tab" data-bs-target="#evaluasi" type="button" role="tab">
                        Evaluasi
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content mt-3" id="detailTabContent">

                <!-- Tab Aktivitas -->
                <div class="tab-pane fade show active" id="aktivitas" role="tabpanel">
                    <div id="loading-aktivitas" class="text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat data aktivitas...</p>
                    </div>
                    <div id="aktivitas-content" style="display: none;">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-aktivitas">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Deskripsi</th>
                                        <th>Foto Bukti</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody id="aktivitas-tbody">
                                    <!-- Data akan dimuat via AJAX -->
                                </tbody>
                            </table>
                        </div>
                        <div id="no-aktivitas" class="text-center py-4" style="display: none;">
                            <p class="text-muted">Belum ada aktivitas yang tercatat</p>
                        </div>
                    </div>
                </div>

                <!-- Tab Evaluasi -->
                <div class="tab-pane fade" id="evaluasi" role="tabpanel">
                    <div id="loading-evaluasi" class="text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat data evaluasi...</p>
                    </div>
                    <div id="evaluasi-content" style="display: none;">

                        <!-- Tombol Tambah Evaluasi -->
                        <div class="mb-3">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-inline">
                                <i class="bi bi-plus"></i> Tambah Evaluasi
                            </button>
                        </div>

                        <!-- Form Inline untuk Tambah/Edit Evaluasi -->
                        <div class="card mb-3" id="form-inline-evaluasi" style="display: none;">
                            <div class="card-body">
                                <form id="form-inline-evaluasi-input">
                                    <input type="hidden" id="id-evaluasi-edit">
                                    <div class="mb-3">
                                        <label for="feedback-inline" class="form-label">Feedback / Evaluasi</label>
                                        <textarea class="form-control" id="feedback-inline" rows="4" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-sm" id="btn-simpan-inline">Simpan</button>
                                    <button type="button" class="btn btn-secondary btn-sm" id="btn-batal-inline">Batal</button>
                                </form>
                            </div>
                        </div>

                        <!-- Daftar Evaluasi -->
                        <div id="evaluasi-list">
                            <!-- Data evaluasi akan dimuat via AJAX -->
                        </div>
                        <div id="no-evaluasi" class="text-center py-4" style="display: none;">
                            <p class="text-muted">Belum ada evaluasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview Foto -->
<div class="modal fade" id="fotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" data-status="{{ $magang->status }}">
            <div class="modal-header">
                <h5 class="modal-title">Foto Bukti Aktivitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="preview-foto" src="" class="img-fluid" alt="Foto Bukti">
            </div>
        </div>
    </div>
</div>

<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css ">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js "></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js "></script>

<script>
$(document).ready(function () {
    const idMagang = '{{ $magang->id_magang }}';

    // Load aktivitas saat tab diklik
    loadAktivitas();
    function loadAktivitas() {
        if ($('#aktivitas-content').is(':visible')) return;

        $.ajax({
            url: `/dosen/aktivitas/${idMagang}`,
            method: 'GET',
            success: function (response) {
                $('#loading-aktivitas').hide();
                $('#aktivitas-content').show();

                if (response.length > 0) {
                    let html = '';
                    response.sort((a, b) => new Date(b.tanggal || b.created_at) - new Date(a.tanggal || a.created_at))
                        .forEach((item, index) => {
                            const tanggal = item.tanggal ? new Date(item.tanggal).toLocaleDateString('id-ID') : '-';
                            const foto = item.foto_path
                                ? `<button class="btn btn-sm btn-outline-primary" onclick="previewFoto('${item.foto_path}')"><i class="bi bi-eye"></i> Lihat</button>`
                                : '<span class="text-muted">Tidak ada</span>';
                            html += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.keterangan || '-'}</td>
                                    <td>${foto}</td>
                                    <td>${tanggal}</td>
                                </tr>
                            `;
                        });
                    $('#aktivitas-tbody').html(html);
                    if (!$.fn.dataTable.isDataTable('#table-aktivitas')) {
                        $('#table-aktivitas').DataTable();
                    }
                } else {
                    $('#no-aktivitas').show();
                }
            },
            error: function () {
                $('#loading-aktivitas').hide();
                $('#aktivitas-content').show();
                $('#aktivitas-tbody').html('<tr><td colspan="4" class="text-center text-danger">Gagal memuat data aktivitas</td></tr>');
            }
        });
    }

    // Load Evaluasi
    function loadEvaluasi() {
        $('#loading-evaluasi').show();
        $('#evaluasi-content').hide();
        $('#evaluasi-list').empty();
        $('#no-evaluasi').hide();

        $.ajax({
            url: `/dosen/aktivitas/${idMagang}/evaluasi`,
            method: 'GET',
            success: function (response) {
                $('#loading-evaluasi').hide();
                $('#evaluasi-content').show();

                if (response.length > 0) {
                    response.forEach(function (item) {
                        const tanggal = item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID') : '-';
                        let html = `
                            <div class="card mb-2" id="evaluasi-item-${item.id_evaluasi}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <p class="mb-1">${item.feedback}</p>
                                            <small class="text-muted">Tanggal: ${tanggal}</small>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="editInline(${item.id_evaluasi}, '${item.feedback.replace(/'/g, "\\'")}')">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="deleteEvaluasi(${item.id_evaluasi})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        $('#evaluasi-list').append(html);
                    });
                } else {
                    $('#no-evaluasi').show();
                }
            },
            error: function () {
                $('#loading-evaluasi').hide();
                $('#evaluasi-list').html('<div class="alert alert-danger">Gagal memuat data evaluasi</div>');
            }
        });
    }

    // Cek status magang sebelum izinkan akses tab Evaluasi
    $('#evaluasi-tab').on('click', function (e) {
        const statusMagang = $('.modal-content').data('status'); // Pastikan data-status sudah ada di modal

        if (statusMagang !== 'lulus') {
            e.preventDefault(); // Cegah tab aktif

            Swal.fire({
                icon: 'info',
                title: 'Akses Dibatasi',
                text: 'Tab Evaluasi hanya bisa diakses jika status magang adalah "lulus".',
                confirmButtonText: 'OK'
            }).then(() => {
                // Kembali ke tab Log Aktivitas
                $('#aktivitas-tab').tab('show');
            });
        } else {
            loadEvaluasi(); // Izinkan akses & muat data evaluasi
        }
    });

    // Tombol Tambah Evaluasi (inline)
    $('#btn-tambah-inline').on('click', function () {
        const statusMagang = $('.modal-content').data('status');
        if (statusMagang !== 'lulus') {
            Swal.fire({
                icon: 'warning',
                title: 'Akses Ditolak',
                text: 'Anda tidak diizinkan menambah evaluasi karena status magang belum lulus.',
                confirmButtonText: 'OK'
            });
            return;
        }
        $('#form-inline-evaluasi').show();
        $('#feedback-inline').val('');
        $('#id-evaluasi-edit').val('');
    });

    // Submit Evaluasi
    $('#form-inline-evaluasi-input').on('submit', function (e) {
        e.preventDefault();
        const idEvaluasi = $('#id-evaluasi-edit').val();
        const feedback = $('#feedback-inline').val();
        let url = `/dosen/aktivitas/${idMagang}/evaluasi`;
        let method = 'POST';

        if (idEvaluasi) {
            url += `/${idEvaluasi}`;
            method = 'PUT';
        }

        $.ajax({
            url: url,
            method: method,
            data: {
                feedback: feedback,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.success) {
                    $('#form-inline-evaluasi').hide();
                    loadEvaluasi();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: idEvaluasi ? 'Evaluasi berhasil diperbarui' : 'Evaluasi berhasil ditambahkan',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal menyimpan evaluasi.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Edit Inline
    window.editInline = function (idEvaluasi, feedback) {
        const statusMagang = $('.modal-content').data('status');
        if (statusMagang !== 'lulus') {
            Swal.fire({
                icon: 'warning',
                title: 'Akses Ditolak',
                text: 'Anda tidak diizinkan mengedit evaluasi karena status magang belum lulus.',
                confirmButtonText: 'OK'
            });
            return;
        }
        $('#id-evaluasi-edit').val(idEvaluasi);
        $('#feedback-inline').val(feedback);
        $('#form-inline-evaluasi').show();
    };

    // Hapus Evaluasi
    window.deleteEvaluasi = function (idEvaluasi) {
        const statusMagang = $('.modal-content').data('status');
        if (statusMagang !== 'lulus') {
            Swal.fire({
                icon: 'warning',
                title: 'Akses Ditolak',
                text: 'Anda tidak diizinkan menghapus evaluasi karena status magang belum lulus.',
                confirmButtonText: 'OK'
            });
            return;
        }

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data evaluasi ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/dosen/aktivitas/${idMagang}/evaluasi/${idEvaluasi}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            loadEvaluasi();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Evaluasi berhasil dihapus.',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal menghapus evaluasi.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    };

    // Preview Foto
    window.previewFoto = function (fotoPath) {
        $('#preview-foto').attr('src', `/storage/aktivitas/${fotoPath}`);
        $('#fotoModal').modal('show');
    };

    $('#btn-batal-inline').on('click', function () {
        $('#form-inline-evaluasi').hide();         // Sembunyi form
        $('#feedback-inline').val('');             // Kosongkan textarea
        $('#id-evaluasi-edit').val('');           // Reset ID edit
    });
});
</script>
