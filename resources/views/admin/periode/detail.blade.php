<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Perusahaan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="container mt-4">
            <div class="mb-3">
                    <label class="form-label fw-bold">Lowongan</label>
                    <p class="form-control-plaintext">{{ $periode->lowongan_magang->perusahaan->nama . ' - ' . $periode->lowongan_magang->nama . ' - ' . $periode->lowongan_magang->bidang->nama }}</p>
                </div>
            <div class="mb-3">
                    <label class="form-label fw-bold">Nama</label>
                    <p class="form-control-plaintext">{{ $periode->nama }}</p>
                </div>
            <div class="mb-3">
                    <label class="form-label fw-bold">Tanggal Mulai</label>
                    <p class="form-control-plaintext">{{ $periode->tanggal_mulai->format('d M Y') }}</p>
                </div>
            <div class="mb-3">
                    <label class="form-label fw-bold">Tanggal Selesai</label>
                    <p class="form-control-plaintext">{{ $periode->tanggal_selesai->format('d M Y') }}</p>
                </div>
                
            </div>
        </div>

        <div class="modal-footer">
        <!-- d-flex justify-content-between -->
            <button type="button" class="btn btn-danger" id="btn-hapus">
                <i class="bi bi-trash"></i> Hapus
            </button>
            <button type="button" class="btn btn-primary"
                onclick="modalAction('{{ url('/admin/periode/edit/' . $periode->id_periode) }}')">
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
                        url: "{{ url('admin/periode/edit/' . $periode->id_periode) }}",
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