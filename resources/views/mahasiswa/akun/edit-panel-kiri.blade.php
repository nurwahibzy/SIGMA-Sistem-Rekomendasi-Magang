<div class="col-md-4">
    <div class="position-sticky" style="top: 90px;">
        <div class="card p-4 text-center shadow">

            <form id="form-tambah" action="{{ url('/mahasiswa/profil/edit/') }}" method="POST" class="text-start mt-3">
                @csrf
                <div class="container mt-4">
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <div class="avatar avatar-2xl mb-3">
                            <label for="file" style="cursor: pointer;">
                                <img id="preview" src="{{ Storage::exists('public/profil/akun/' . Auth::user()->foto_path)
    ? asset('storage/profil/akun/' . Auth::user()->foto_path)
    : asset('template/assets/images/mhs.jpeg') }}" alt="Profile Picture" class="rounded-circle"
                                    style="width: 120px; height: 120px; border: 5px solid blue; object-fit: cover;">
                            </label>
                        </div>
                        <small class="text-muted text-center">Tekan gambar untuk mengganti foto profil</small>
                        <input type="file" id="file" name="file" accept="image/*" onchange="previewImage(event)"
                            style="display: none;">
                        <button type="button" id="tombolBatal" class="btn btn-sm btn-primary mt-2"
                            style="visibility: hidden;" onclick="batalkanPreview()">Batalkan</button>
                    </div>
                </div>
                <input type="text" class="form-control" id="id_user" name="id_user" required
                    value="{{ Auth::user()->id_user ?? '-' }}" hidden>
                <div class="mb-2">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" required
                        value="{{ Auth::user()->mahasiswa->nama ?? '-' }}">
                </div>
                <div class="mb-2">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" value="">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah.</small>
                </div>
                <div class="mb-2">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3"
                        required>{{ Auth::user()->mahasiswa->alamat ?? '-' }}</textarea>
                </div>
                <div class="mb-2">
                    <label for="telepon" class="form-label">Telepon</label>
                    <input type="text" class="form-control" id="telepon" name="telepon" required
                        value="{{ Auth::user()->mahasiswa->telepon ?? '-' }}">
                </div>
                <div class="mb-2">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required
                        value="{{ Auth::user()->mahasiswa->tanggal_lahir ?? '-' }}"  max="{{ now()->format('Y-m-d') }}">
                </div>
                <div class="mb-2">

                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required
                        value="{{ Auth::user()->mahasiswa->email }}">
                </div>
                <button type="submit" id="btn-edit-profile" class="btn btn-primary mt-2 w-100">
                    <i class="bi bi-pencil-square"></i> Simpan
                </button>
            </form>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                telepon: { required: true, digits: true },
                tanggal_lahir: { required: true, date: true },
                email: { required: true, email: true },
                password: { minlength: "Password minimal 6 karakter"}
            },
            messages: {
                id_user: "ID User wajib diisi",
                nama: "Nama wajib diisi",
                alamat: "Alamat wajib diisi",
                telepon: "Nomor telepon wajib diisi dan numerik",
                tanggal_lahir: "Tanggal lahir wajib diisi",
                email: "Email wajib diisi dan harus valid",
                password: { minlength: "Password minimal 6 karakter"}
            },
            submitHandler: function (form) {
                const formData = new FormData(form);
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Sedang memproses data',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
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
                                location.reload()
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

                return false;
            }
        });
    });
</script>