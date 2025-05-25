@extends('layouts.tamplate')
@section('content')
    <div class="page-heading">
        <h3>Edit Profile</h3>
    </div>

    <section class="section">
        <div class="row">
            <!-- Kolom Foto -->
            <div class="col-md-4">
                <div class="card text-center p-4">
                    <label for="file" style="cursor: pointer;">
                        <img id="preview" src="{{ Storage::exists('public/profil/akun/' . Auth::user()->foto_path)
                            ? asset('storage/profil/akun/' . Auth::user()->foto_path)
                            : asset('template/assets/images/mhs.jpeg') }}"
                            alt="Foto Profil"
                            class="rounded-circle mx-auto mb-3"
                            width="100" height="100"
                            style="border: 5px solid blue;">
                    </label>
                    <input type="file" id="file" name="file" accept="image/*" onchange="previewImage(event)" style="display: none;">
                    <h5 class="mb-0">{{ Auth::user()->admin->first_name ?? '-' }} {{ Auth::user()->admin->last_name ?? '' }}</h5>
                    <small class="text-muted">{{ Auth::user()->admin->email ?? '-' }}</small>
                </div>
            </div>

            <!-- Kolom Form -->
            <div class="col-md-8">
                <div class="card p-4">
                    <form id="form-tambah" action="{{ url('/admin/profil/edit/') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                        @csrf
                        <input type="hidden" name="id_user" value="{{ Auth::user()->id_user }}">

                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->level->role ?? '-' }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ID</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->id_user }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" name="nama" value="{{ Auth::user()->admin->nama ?? '-' }}" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" name="tanggal_lahir" value="{{ Auth::user()->admin->tanggal_lahir }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ Auth::user()->admin->email }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telepon</label>
                            <input type="text" class="form-control" name="telepon" value="{{ Auth::user()->admin->telepon }}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" value="">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="2" required>{{ Auth::user()->admin->alamat }}</textarea>
                        </div>

                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="bi bi-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Preview gambar saat dipilih
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const preview = document.getElementById('preview');
                preview.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        $(document).ready(function () {
            $("#form-tambah").validate({
                rules: {
                    nama: { required: true },
                    alamat: { required: true },
                    telepon: { required: true, digits: true },
                    tanggal_lahir: { required: true, date: true },
                    email: { required: true, email: true }
                },
                messages: {
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
                                    text: 'Profil berhasil diperbarui.'
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
                                text: xhr.responseJSON?.message || 'Terjadi kesalahan pada server.'
                            });
                        }
                    });

                    return false; // Mencegah submit default
                }
            });
        });
    </script>
@endpush
