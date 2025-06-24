<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SIGMA</title>

    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/iconly.css') }}">

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", sans-serif;
        }

        #auth {
            display: flex;
            align-items: center;
            /* Vertikal center */
            justify-content: center;
            /* Horizontal center */
            height: 100vh;
            /* Full viewport height */
            background: linear-gradient(90deg, #2d499d, #3f5491);
        }

        #auth-left {
            max-width: 1000px;
            width: 100%;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            background-color: white;
        }

        .auth-title {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .auth-subtitle {
            font-size: 0.95rem;
            color: #6c757d;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .btn-lg {
            padding: 0.6rem;
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            #auth-left {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div id="auth">
        <div id="auth-left">
            <div class="auth-logo text-center mb-4">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('template/assets/compiled/svg/logo.svg') }}" alt="Logo"
                        style="width: 170px; height: auto;">
                </a>
                <p>Sistem Rekomendasi Magang</p>
            </div>
            <h5 class="auth-title text-right">Register</h5>

            <form action="{{ url('register') }}" method="POST" id="form-register">
                @csrf
                <div class="container mt-4">
                    <div class="d-flex justify-content-between">
                        <div class="w-50 me-2">
                            <div>
                                <label for="id_prodi" class="form-label">Program Studi</label>
                                <select name="id_prodi" class="form-select" id="id_prodi"
                                    data-placeholder="Pilih Program Studi" required>
                                    <option value="">Pilih Prodi</option>
                                    @foreach ($prodi as $item)
                                        <option value="{{ $item->id_prodi }}">{{ $item->nama_prodi }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="w-50 ms-2">
                            <div>
                                      <label for=" nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="w-50 me-2">
                            <div class="mt-4">
                                <label for="id_user" class="form-label">NIM</label>
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
                                <label for="password" class="form-label" >Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mt-4">
                                <label for="telepon" class="form-label">Telepon</label>
                                <input type="text" class="form-control" id="telepon" name="telepon" required>
                            </div>
                            <div class="mt-4">
                                <label for="gender" class="form-label">Gender</label>
                                <select name="gender" id="gender" class="form-select" required>
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
                <div class="text-end">
                    <button class="btn btn-primary shadow-lg mt-4">Daftar</button>   
                </div>
            </form>
        </div>
    </div>


    <!-- Scripts -->
    <script src="{{ asset('template/assets/static/js/initTheme.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function () {
            $("#form-register").validate({
                rules: {
                    id_user: { required: true, digits: true },
                    password: { required: true, minlength: 6 },
                    status: { required: true },
                    id_prodi: { required: true },
                    nama: { required: true },
                    alamat: { required: true },
                    telepon: {
                        required: true,
                        digits: true,
                        minlength: 8
                    },
                    tanggal_lahir: { required: true, date: true },
                    email: { required: true, email: true },
                    gender: { required: true }
                },
                messages: {
                    id_user: "NIM wajib diisi dan numerik",
                    password: "Password wajib diisi dan minimal 6 karakter",
                    status: "Status wajib diisi",
                    id_prodi: "Prodi wajib diisi",
                    nama: "Nama wajib diisi",
                    alamat: "Alamat wajib diisi",
                    telepon: {
                        required: "Telepon wajib diisi",
                        digits: "Hanya angka yang diperbolehkan",
                        minlength: "Minimal 8 digit"
                    },
                    tanggal_lahir: "Tanggal lahir wajib diisi",
                    email: "Email wajib diisi dan harus valid",
                    gender: "Gender wajib diisi",
                },
                errorElement: 'div',
                errorClass: 'invalid-feedback',
                validClass: 'is-valid',
                errorClass: 'is-invalid',
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
                                    text: 'Tunggu Admin untuk mengaktifkan Anda.'
                                }).then(() => {
                                    window.location.href = '{{ url("/") }}';
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
</body>

</html>