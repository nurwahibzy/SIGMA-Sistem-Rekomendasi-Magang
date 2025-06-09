<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIGMA</title>

    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/iconly.css') }}">

<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        font-family: "Segoe UI", sans-serif;
    }

    #auth {
        display: flex;
        align-items: center;          /* Vertikal center */
        justify-content: center;      /* Horizontal center */
        height: 100vh;                /* Full viewport height */
        background: linear-gradient(90deg, #2d499d, #3f5491);
    }

    #auth-left {
        max-width: 400px;
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
                    <img src="{{ asset('template/assets/compiled/svg/logo.svg') }}" alt="Logo" style="width: 170px; height: auto;">
                </a>
            </div>
            <h5 class="auth-title text-right">Login</h5>

            <form action="{{ url('login') }}" method="POST" id="login">
                @csrf
                <div class="form-group position-relative has-icon-left mb-3">
                    <input type="text" id="id_user" name="id_user" class="form-control form-control-xl" placeholder="NIM/NIP">
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-3">
                    <input type="password" id="password" name="password" class="form-control form-control-xl" placeholder="Password">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>
                <button class="btn btn-primary btn-block btn-lg shadow-lg mt-4 w-100">Log in</button>
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
            $("#login").validate({
                rules: {
                    id_user: { required: true },
                    password: { required: true },
                },
                messages: {
                    id_user: "Masukkan ID",
                    password: "Masukkan Password",
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
                                    text: 'Berhasil Login.'
                                }).then(() => {
                                    window.location.href = '{{ url("/") }}/' + response.level + '/dashboard';
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message || 'Terjadi kesalahan saat Login.'
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
                }
            });
        });
    </script>
</body>

</html>