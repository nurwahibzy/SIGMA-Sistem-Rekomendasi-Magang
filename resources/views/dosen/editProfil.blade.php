@extends('layouts.tamplate')
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Profile</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('dosen/profil') }}">Profile</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <div class="avatar avatar-2xl mb-3">
                                <label for="file" style="cursor: pointer;">
                                    <img id="preview" 
                                        src="{{ Storage::exists('public/profil/akun/' . Auth::user()->foto_path)
                                            ? asset('storage/profil/akun/' . Auth::user()->foto_path)
                                            : asset('template/assets/images/mhs.jpeg') }}" 
                                        alt="Profile Picture"
                                        class="rounded-circle"
                                        style="width: 120px; height: 120px; border: 5px solid blue; object-fit: cover;">
                                </label>
                                <input type="file" id="file" name="file" accept="image/*" 
                                    onchange="previewImage(event)" style="display: none;" form="form-edit-profile">
                            </div>
                            <small class="text-muted text-center">Click image to change profile picture</small>
                            <h4 class="mt-2 text-center">{{ Auth::user()->dosen->nama ?? 'N/A' }}</h4>
                            <p class="text-small">NIP: {{ Auth::user()->id_user ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form id="form-edit-profile" action="{{ url('/dosen/profil/edit/') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_user" class="form-label">NIP</label>
                                        <input type="text" name="id_user" id="id_user" class="form-control" 
                                            value="{{ Auth::user()->id_user ?? '-' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama" class="form-label">Nama Lengkap</label>
                                        <input type="text" name="nama" id="nama" class="form-control" 
                                            value="{{ Auth::user()->dosen->nama ?? '-' }}" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" 
                                            value="{{ Auth::user()->dosen->email ?? '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telepon" class="form-label">Telepon</label>
                                        <input type="text" name="telepon" id="telepon" class="form-control" 
                                            value="{{ Auth::user()->dosen->telepon ?? '-' }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea name="alamat" id="alamat" class="form-control" rows="3" 
                                    required>{{ Auth::user()->dosen->alamat ?? '-' }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" 
                                            value="{{ Auth::user()->dosen->tanggal_lahir ?? '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" id="password" class="form-control" 
                                            placeholder="Leave blank to keep current password">
                                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Simpan Perubahan
                                </button>
                                <a href="{{ url('dosen/profil') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const preview = document.getElementById('preview');
            preview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    $(document).ready(function () {
        $("#form-edit-profile").validate({
            rules: {
                id_user: { 
                    required: true, 
                    digits: true 
                },
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
                    minlength: 10
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
                id_user: {
                    required: "NIP wajib diisi",
                    digits: "NIP harus berupa angka"
                },
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
                    minlength: "Nomor telepon minimal 10 digit"
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
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).addClass('is-valid').removeClass('is-invalid');
            },
            submitHandler: function (form) {
                const formData = new FormData(form);
                
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
                                window.location.href = '{{ url("dosen/profil") }}';
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

                return false;
            }
        });
    });
</script>
@endsection