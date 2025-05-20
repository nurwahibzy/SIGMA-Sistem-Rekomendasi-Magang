<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Lowongan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>  
        <div class="modal-body">
            <div class="container mt-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Perusahaan</label>
                    <p class="form-control-plaintext">{{ $lowongan->perusahaan->nama }}</p>
                </div>
                <br>
                <div class="mb-3">
                    <label class="form-label fw-bold">Bidang</label>
                    <p class="form-control-plaintext">{{ $lowongan->bidang->nama }}</p>
                </div>
                <br>
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Lowongan</label>
                    <p class="form-control-plaintext">{{ $lowongan->nama }}</p>
                </div>
                <br>
                <div class="mb-3">
                    <label class="form-label fw-bold">Persyaratan</label>
                    <p class="form-control-plaintext">{{ $lowongan->persyaratan }}</p>
                </div>
                <br>
                <div class="mb-3">
                    <label class="form-label fw-bold">Deskripsi</label>
                    <p class="form-control-plaintext">{{ $lowongan->deskripsi }}</p>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="btn-hapus">
                <i class="bi bi-trash"></i> Hapus
            </button>
            <button type="button" class="btn btn-primary"
                onclick="modalAction('{{ url('/admin/lowongan/edit/' . $lowongan->id_lowongan) }}')">
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
                        url: "{{ url('admin/lowongan/edit/' . $lowongan->id_lowongan) }}",
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