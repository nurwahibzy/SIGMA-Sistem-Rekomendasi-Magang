@extends('layouts.tamplate')
@section('content')
    @if (!empty($id_magang))

        <head>
            <style>
                .text-muted {
                    color: #6c757d;
                    font-style: italic;
                }
            </style>
            <script src="https://unpkg.com/lottie-web@5.7.4/build/player/lottie.min.js"></script>
        </head>

        <div class="page-heading">
            <h3>Rating</h3>
        </div>

        <div id="page-content">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Rating</h4>
                </div>
                <div class="row">
                    <!-- FORM -->
                    <div class="col-md-6">
                        <form method="POST" action="{{ url('mahasiswa/penilaian/' . $id_magang) }}" id="ratingForm">
                            @csrf

                            <!-- Fasilitas -->
                            <div class="form-group mb-4">
                                <label class="form-label">Fasilitas</label>
                                <div class="rating d-flex gap-3 align-items-center">
                                    <input type="radio" required name="fasilitas" value="1" id="fasilitas-1">
                                    <input type="radio" name="fasilitas" value="2" id="fasilitas-2">
                                    <input type="radio" name="fasilitas" value="3" id="fasilitas-3">
                                    <input type="radio" name="fasilitas" value="4" id="fasilitas-4">
                                    <input type="radio" name="fasilitas" value="5" id="fasilitas-5">
                                </div>
                                <div class="mt-2">
                                    <span id="fasilitas-rating-value">1</span> Sampai 5
                                </div>
                                <div class="mt-1 text-muted">
                                    <small>1 = Sangat Tidak Memadai, 5 = Sangat Memadai</small>
                                </div>
                            </div>

                            <!-- Pembinaan -->
                            <div class="form-group mb-4">
                                <label class="form-label">Pembinaan</label>
                                <div class="rating d-flex gap-3 align-items-center">
                                    <input type="radio" required name="pembinaan" value="1" id="pembinaan-1">
                                    <input type="radio" name="pembinaan" value="2" id="pembinaan-2">
                                    <input type="radio" name="pembinaan" value="3" id="pembinaan-3">
                                    <input type="radio" name="pembinaan" value="4" id="pembinaan-4">
                                    <input type="radio" name="pembinaan" value="5" id="pembinaan-5">
                                </div>
                                <div class="mt-2">
                                    <span id="pembinaan-rating-value">1</span> Sampai 5
                                </div>
                                <div class="mt-1 text-muted">
                                    <small>1 = Sangat Tidak Dibina, 5 = Sangat Dibina</small>
                                </div>
                            </div>

                            <!-- Tugas -->
                            <div class="form-group mb-4">
                                <label class="form-label">Tugas</label>
                                <div class="rating d-flex gap-3 align-items-center">
                                    <input type="radio" required name="tugas" value="1" id="tugas-1">
                                    <input type="radio" name="tugas" value="2" id="tugas-2">
                                    <input type="radio" name="tugas" value="3" id="tugas-3">
                                    <input type="radio" name="tugas" value="4" id="tugas-4">
                                    <input type="radio" name="tugas" value="5" id="tugas-5">
                                </div>
                                <div class="mt-2">
                                    <span id="tugas-rating-value">1</span> Sampai 5
                                </div>
                                <div class="mt-1 text-muted">
                                    <small>1 = Tidak Jelas / Tidak Sesuai, 5 = Jelas & Sesuai</small>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary rounded-pill px-4">Kirim Rating</button>
                            </div>
                        </form>
                    </div>
                    <!-- ANIMASI -->
                    <div class="col-md-6 d-flex justify-content-center align-items-center">
                        <div id="ratingAnimation" style="width: 100%; max-width: 500px; height: 500px; margin-right: 30px;">
                        </div>
                    </div>

                </div>
            </div>

            <!-- Styling -->
            <style>
                .rating input[type="radio"] {
                    appearance: none;
                    width: 25px;
                    height: 25px;
                    border: 2px solid #ccc;
                    border-radius: 50%;
                    margin: 0 4px;
                    position: relative;
                    cursor: pointer;
                    transition: 0.2s;
                }

                .rating input[type="radio"]:checked {
                    background-color: #0d6efd;
                    border-color: #0d6efd;
                }

                .rating input[type="radio"]::before {
                    content: attr(value);
                    font-size: 12px;
                    color: white;
                    position: absolute;
                    top: 3px;
                    left: 8px;
                    display: none;
                }

                .rating input[type="radio"]:checked::before {
                    display: block;
                }

                .card {
                    border-radius: 12px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
                    padding: 20px;
                }

                .card-header {
                    border-bottom: 1px solid #eee;
                    margin-bottom: 15px;
                }

                .form-label {
                    font-weight: 600;
                }

                .text-muted {
                    color: #6c757d;
                }


                #ratingAnimation {
                    margin-right: 30px;
                }
            </style>
            <script src="https://unpkg.com/lottie-web@5.7.4/build/player/lottie.min.js"></script>
            <script>
                lottie.loadAnimation({
                    container: document.getElementById('ratingAnimation'),
                    renderer: 'svg',
                    loop: true,
                    autoplay: true,
                    path: 'https://assets9.lottiefiles.com/packages/lf20_tfb3estd.json' // animasi bintang & rating
                });
            </script>



            <!-- jQuery & AJAX -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $(document).ready(function () {
                    $("#ratingForm").on('submit', function (e) {
                        e.preventDefault();
                        const form = this;
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
                                        window.location.href = '{{ url("mahasiswa/riwayat") }}';
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
                    });
                });
            </script>
    @endif
@endsection