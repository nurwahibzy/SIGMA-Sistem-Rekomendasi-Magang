@extends('layouts.tamplate')
@section('content')
    <div class="page-heading">
        <h3>My Profile</h3>
    </div>
    <section class="section">
        <div class="row">

            <div class="col-md-4">
                <div class="position-sticky" style="top: 90px;">
                    <div class="card p-4 text-center">

                        <form id="form-tambah" action="{{ url('/admin/profil/edit/') }}" method="POST"
                            class="text-start mt-3">
                            @csrf
                            <div class="text-center mb-3">
                                <label for="file" style="cursor: pointer;">

                                        <img id="preview" src="{{ Storage::exists('public/profil/akun/' . Auth::user()->foto_path)
                    ? asset('storage/profil/akun/' . Auth::user()->foto_path)
                    : asset('template/assets/images/mhs.jpeg') }}" alt="Foto Profil" alt="Profile Picture"
                            class="rounded-circle mx-auto d-block mb-3" width="100" height="100"
                            style="border: 5px solid blue;" />
                                </label>
                                <input type="file" id="file" name="file" accept="image/*" onchange="previewImage(event)"
                                    style="display: none;">
                            </div>
                            <div class="mb-2">
                                <label for="id_user" class="form-label">NIP</label>
                                <input type="text" class="form-control" id="id_user" name="id_user" required
                                    value="{{ Auth::user()->id_user ?? '-' }}">
                            </div>
                            <div class="mb-2">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" required
                                    value="{{ Auth::user()->admin->nama ?? '-' }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" value="">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah.</small>
                            </div>
                            <div class="mb-2">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3"
                                    required>{{ Auth::user()->admin->alamat ?? '-' }}</textarea>
                            </div>
                            <div class="mb-2">
                                <label for="telepon" class="form-label">Telepon</label>
                                <input type="text" class="form-control" id="telepon" name="telepon" required
                                    value="{{ Auth::user()->admin->telepon ?? '-' }}">
                            </div>
                            <div class="mb-2">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required
                                    value="{{ Auth::user()->admin->tanggal_lahir ?? '-' }}">
                            </div>
                            <div class="mb-2">

                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                    value="{{ Auth::user()->admin->email }}">
                            </div>
                            <button type="submit" id="btn-edit-profile" class="btn btn-outline-primary mt-2 w-100">
                                <i class="bi bi-pencil-square"></i> Simpan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const preview = document.getElementById('preview');
            preview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
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
                email: { required: true, email: true }
            },
            messages: {
                id_user: "ID User wajib diisi",
                nama: "Nama wajib diisi",
                alamat: "Alamat wajib diisi",
                telepon: "Nomor telepon wajib diisi dan numerik",
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
                                window.location.href = '{{ url('admin/profil') }}';
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