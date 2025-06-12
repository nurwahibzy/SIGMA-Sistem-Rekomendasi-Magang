@extends('layouts.tamplate')
@section('content')
    <div class="page-heading">
        <h3>Edit Profile</h3>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="card shadow">
                    <div class="card-body">
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
                            <button type="button" id="tombolBatal" class="btn btn-sm btn-primary mt-2"
                                style="visibility: hidden;" onclick="batalkanPreview()">Batalkan</button>
                            <h4 class="mt-2 text-center">{{ Auth::user()->admin->nama ?? 'N/A' }}</h4>
                            <p class="text-small">{{ Auth::user()->id_user ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Form -->
            <div class="col-md-8">
                <div class="card p-4 shadow">
                    <form id="form-edit-profile" action="{{ url('/admin/profil/edit/') }}" method="POST"
                        enctype="multipart/form-data" class="row g-3">
                        @csrf
                        <!-- Hidden file input inside form -->
                        <input type="file" id="file" name="file" accept="image/*" onchange="previewImage(event)"
                            style="display: none;">

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
                            <input type="text" class="form-control" name="nama"
                                value="{{ Auth::user()->admin->nama ?? '-' }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" name="tanggal_lahir"
                                value="{{ Auth::user()->admin->tanggal_lahir }}" max="{{ now()->format('Y-m-d') }}"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ Auth::user()->admin->email }}"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telepon</label>
                            <input type="text" class="form-control" name="telepon"
                                value="{{ Auth::user()->admin->telepon }}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" value="">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="2"
                                required>{{ Auth::user()->admin->alamat }}</textarea>
                        </div>

                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-warning me-2" id="btn-batal">
                                 Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            $("#form-edit-profile").validate({
                rules: {
                    nama: {
                        required: true,
                        minlength: 2
                    },
                    alamat: {
                        required: true,
                        minlength: 5
                    },
                    telepon: {
                        required: true,
                        digits: true,
                        minlength: 8
                    },
                    tanggal_lahir: {
                        required: true,
                        date: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        minlength: 6
                    }
                },
                messages: {
                    nama: {
                        required: "Nama wajib diisi",
                        minlength: "Nama minimal 2 karakter"
                    },
                    alamat: {
                        required: "Alamat wajib diisi",
                        minlength: "Alamat minimal 5 karakter"
                    },
                    telepon: {
                        required: "Nomor telepon wajib diisi",
                        digits: "Nomor telepon harus berupa angka",
                        minlength: "Nomor telepon minimal 8 digit"
                    },
                    tanggal_lahir: {
                        required: "Tanggal lahir wajib diisi",
                        date: "Format tanggal tidak valid"
                    },
                    email: {
                        required: "Email wajib diisi",
                        email: "Format email tidak valid"
                    },
                    password: {
                        minlength: "Password minimal 6 karakter"
                    }
                },
                errorElement: 'div',
                errorClass: 'invalid-feedback',
                validClass: 'is-valid',
                errorClass: 'is-invalid',
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-valid').removeClass('is-invalid');
                },
                submitHandler: function (form) {
                    const formData = new FormData(form);

                    // Debug: Check if file is included
                    console.log('FormData contents:');
                    for (let pair of formData.entries()) {
                        console.log(pair[0] + ': ' + pair[1]);
                    }

                    // Show loading
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
                            Swal.close();
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Profile berhasil diperbarui.',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.href = '{{ url("admin/profil") }}';
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message || 'Terjadi kesalahan saat menyimpan data.',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function (xhr) {
                            Swal.close();
                            let errorMessage = 'Terjadi kesalahan saat menyimpan data.';

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                const errors = xhr.responseJSON.errors;
                                errorMessage = Object.values(errors).flat().join('\n');
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: errorMessage,
                                confirmButtonText: 'OK'
                            });
                        }
                    });

                    // return false;
                }
            });

            $('#btn-batal').click(function () {
            Swal.fire({
                title: 'Yakin ingin membatalkan perubahan data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, batalkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ url("admin/profil") }}';
                }
            });
        });
        });
    </script>
@endsection