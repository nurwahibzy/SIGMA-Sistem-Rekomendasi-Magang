<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header bg-primary text-white rounded-top">
            <h5 class="text-light">Detail Aktivitas</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            @if (Storage::exists('public/aktivitas/' . $aktivitas->foto_path))
                <div class="container mt-4">
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <div class="avatar avatar-2xl mb-3">
                            <img id="preview" src="{{ asset('storage/aktivitas/' . $aktivitas->foto_path) }}"
                                alt="Profile Picture" class="img-fluid rounded w-50 h-50"
                                style="width: 120px; height: 120px; border: 5px solid blue; object-fit: cover; cursor: pointer;"
                                onclick="showDosenImagePopup(this.src)" />
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning text-center">
                    Tidak ada foto.
                </div>
            @endif

            <div class="container mt-4">
                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3" disabled required>{{ $aktivitas->keterangan }}</textarea>
                </div>
            </div>
        </div>
        @if ($aktivitas->tanggal != date('Y-m-d'))
            <div class="alert alert-warning text-center">
                Tidak bisa menghapus atau mengedit aktivitas.
            </div>
        @else
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btn-hapus">
                    <i class="bi bi-trash"></i> Hapus
                </button>
                <button type="button" class="btn btn-primary"
                    onclick="modalAction('{{ url('/mahasiswa/aktivitas/' . $aktivitas->id_magang . '/edit/' . $aktivitas->id_aktivitas) }}')">
                     <i class="bi bi-pencil-square"></i> Edit
                </button>
            </div>
        @endif
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
    function showDosenImagePopup(src) {
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
                        url: "{{ url('/mahasiswa/aktivitas/' . $aktivitas->id_magang . '/edit/' . $aktivitas->id_aktivitas) }}",
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