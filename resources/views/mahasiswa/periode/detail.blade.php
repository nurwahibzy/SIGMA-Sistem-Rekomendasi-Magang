<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header bg-primary text-white rounded-top">
            <h5 class="modal-title">Detail Lowongan</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="container mt-4">

            </div>
        </div>

        <div class="modal-footer">
            <!-- d-flex justify-content-between -->
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
                                location.reload();
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