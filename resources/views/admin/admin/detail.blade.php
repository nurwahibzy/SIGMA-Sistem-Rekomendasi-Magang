<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content shadow-sm rounded">

        <div class="modal-header bg-primary text-white rounded-top">
            <h5 class="modal-title">Detail Admin</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="container mt-4">
                <div class="d-flex justify-content-center align-items-center flex-column">
                    <div class="avatar avatar-2xl mb-3">
                        <img id="preview" src="{{ Storage::exists('public/profil/akun/' . $admin->akun->foto_path)
    ? asset('storage/profil/akun/' . $admin->akun->foto_path)
    : asset('template/assets/images/mhs.jpeg') }}" alt="Profile Picture" class="rounded-circle"
                            style="width: 120px; height: 120px; border: 5px solid blue; object-fit: cover; cursor: pointer;"
                            onclick="showImagePopup(this.src)" />

                    </div>
                    <h4 class="mt-2 text-center">{{ $admin->nama }}</h4>
                    <p class="text-small">{{ $admin->akun->id_user }}</p>
                </div>

            </div>
            <div class="container mt-4">
                <div class="d-flex justify-content-between">
                    <div class="w-50 me-2">
                        <div>
                            <label class="form-label fw-bold">Email</label>
                            <div class="border rounded p-2">
                                <p class="form-control-plaintext mb-0">{{ $admin->email }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="form-label fw-bold">Tanggal Lahir</label>
                            <div class="border rounded p-2">
                                <p class="form-control-plaintext mb-0">{{ $admin->tanggal_lahir }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="w-50 ms-2">
                        <div>
                            <label class="form-label fw-bold">Telepon</label>
                            <div class="border rounded p-2">
                                <p class="form-control-plaintext mb-0">{{ $admin->telepon }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="form-label fw-bold">Gender</label>
                            <div class="border rounded p-2">
                                <p class="form-control-plaintext mb-0">{{ $admin->Gender ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container mt-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Alamat</label>
                    <div class="border rounded p-2">
                        <p class="form-control-plaintext mb-0">{{ $admin->alamat }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="btn-hapus">
                <i class="bi bi-trash"></i> Hapus
            </button>
            <button type="button" class="btn btn-primary"
                onclick="modalAction('{{ url('/admin/admin/edit/' . $admin->akun->id_akun) }}')">
                Edit
            </button>
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
                        url: "{{ url('admin/admin/edit/' . $admin->akun->id_akun) }}",
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