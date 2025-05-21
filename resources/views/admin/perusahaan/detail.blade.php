<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content shadow-sm rounded">

        <div class="modal-header bg-primary text-white rounded-top">
            <h5 class="modal-title">Detail Perusahaan</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="container mt-4">

                <ul class="nav nav-tabs mb-3" id="detailTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#Profil" role="tab">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#Data" role="tab">Data</a>
                    </li>
                </ul>

                <div class="tab-content" id="detailTabContent">

                    <div class="tab-pane fade show active" id="Profil" role="tabpanel">
                        <div class="mb-3">
                            <img src="{{ asset('storage/profil/perusahaan/' . $perusahaan->foto_path) }}"
                                alt="Foto Perusahaan" class="img-thumbnail mt-2"
                                style="max-width: 300px; height: auto;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Perusahaan</label>
                            <p class="form-control-plaintext">{{ $perusahaan->nama }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Perusahaan</label>
                            <p class="form-control-plaintext">{{ $perusahaan->jenis_perusahaan->jenis }}</p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="Data" role="tabpanel">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Telepon</label>
                            <p class="form-control-plaintext">{{ $perusahaan->telepon }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi</label>
                            <p class="form-control-plaintext">{{ $perusahaan->deskripsi }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Provinsi</label>
                            <p class="form-control-plaintext">{{ $perusahaan->provinsi }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Daerah</label>
                            <p class="form-control-plaintext">{{ $perusahaan->daerah }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal-footer">
        <!-- d-flex justify-content-between -->
            <button type="button" class="btn btn-danger" id="btn-hapus">
                <i class="bi bi-trash"></i> Hapus
            </button>
            <button type="button" class="btn btn-primary"
                onclick="modalAction('{{ url('/admin/perusahaan/edit/' . $perusahaan->id_perusahaan) }}')">
                Edit
            </button>
        </div>

    </div>
</div>
<script>
    $(document).ready(function () {
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
                        url: "{{ url('admin/perusahaan/edit/' . $perusahaan->id_perusahaan) }}",
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
                                location.reload();
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
    })
</script>