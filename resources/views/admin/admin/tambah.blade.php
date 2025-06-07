<form action="{{ url('/admin/admin/tambah') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white rounded-top">
                <h5 class="text-light">Tambah Admin</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mt-4">
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <div class="avatar avatar-2xl mb-3">
                            <label for="file" style="cursor: pointer;">
                                <img id="preview" src="{{ asset('template/assets/images/mhs.jpeg') }}"
                                    alt="Profile Picture" class="rounded-circle"
                                    style="width: 120px; height: 120px; border: 5px solid blue; object-fit: cover;">
                            </label>
                        </div>
                        <small class="text-muted text-center">Tekan gambar untuk memberikan foto profil</small>
                        <input type="file" id="file" name="file" accept="image/*" onchange="previewImage(event)"
                            style="display: none;">
                        <button type="button" id="tombolBatal" class="btn btn-sm btn-primary mt-2"
                            style="visibility: hidden;" onclick="batalkanPreview()">Batalkan</button>
                    </div>
                </div>
                <div class="container mt-4">
                    <div class="d-flex justify-content-between">
                        <div class="w-50 me-2">
                            <div class="mt-4">
                                <label for="id_user" class="form-label">NIP</label>
                                <input type="text" class="form-control" id="id_user" name="id_user" required>
                            </div>
                            <div class="mt-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mt-4">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                                    max="{{ now()->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="w-50 ms-2">
                            <div class="mt-4">
                                <label for=" nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="mt-4">
                                <label for="telepon" class="form-label">Telepon</label>
                                <input type="text" class="form-control" id="telepon" name="telepon" required>
                            </div>
                            <div class="mt-4">
                                <label for="gender" class="form-label">Gender</label>
                                <select name="gender" id="gender" class="form-select">
                                    <option value="">Pilih Gender</option>
                                    <option value="l">Laki-laki
                                    </option>
                                    <option value="p">
                                        Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container mt-4">
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-bs-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    const defaultPreview = document.getElementById('preview').src;

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            document.getElementById('preview').src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
        document.getElementById('tombolBatal').style.visibility = 'visible';
    }

    function batalkanPreview() {
        document.getElementById('preview').src = defaultPreview;
        document.getElementById('file').value = "";
        document.getElementById('tombolBatal').style.visibility = 'hidden';
    }
</script>
<script>
    $(document).ready(function () {
        $("#form-tambah").validate({
            rules: {
                id_user: { required: true, digits: true },
                nama: { required: true },
                alamat: { required: true },
                telepon: { required: true,
                    digits: true,
                    minlength: 8 },
                tanggal_lahir: { required: true, date: true },
                email: { required: true, email: true }
            },
            messages: {
                id_user: "ID User wajib diisi dan numerik",
                nama: "Nama wajib diisi",
                alamat: "Alamat wajib diisi",
                telepon: {
                    required: "Telepon wajib diisi",
                    digits: "Hanya angka yang diperbolehkan",
                    minlength: "Minimal 8 digit"
                },
                tanggal_lahir: "Tanggal lahir wajib diisi",
                email: "Email wajib diisi dan harus valid"
            },
            submitHandler: function (form) {
                const formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
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
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan.'
                        });
                    }
                });

                // return false;
            }
        });
    });
</script>