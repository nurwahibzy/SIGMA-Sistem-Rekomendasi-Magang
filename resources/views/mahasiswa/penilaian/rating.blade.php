@extends('layouts.tamplate')

@section('content')
    <div class="page-heading">
        <h3>Rating</h3>
    </div>

    <div id="page-content">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Rating</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ url('mahasiswa/penilaian/' . $id_magang) }}" id="ratingForm">
                    @csrf
                    <div class="form-group mb-4">
                        <label class="form-label">Fasilitas</label>
                        <div class="rating rating-fasilitas">

                            <input type="radio" required class="form-check-input" name="fasilitas" value="1"
                                id="fasilitas-1">
                            <input type="radio" class="form-check-input" name="fasilitas" value="2" id="fasilitas-2">
                            <input type="radio" class="form-check-input" name="fasilitas" value="3" id="fasilitas-3">
                            <input type="radio" class="form-check-input" name="fasilitas" value="4" id="fasilitas-4">
                            <input type="radio" class="form-check-input" name="fasilitas" value="5" id="fasilitas-5">
                        </div>
                        <div class="mt-2">
                            <span id="fasilitas-rating-value">1</span> Sampai 5
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Kedisiplinan</label>
                        <div class="rating rating-kedisiplinan">
                            <input type="radio" required class="form-check-input" name="kedisiplinan" value="1"
                                id="kedisiplinan-1">
                            <input type="radio" class="form-check-input" name="kedisiplinan" value="2" id="kedisiplinan-2">
                            <input type="radio" class="form-check-input" name="kedisiplinan" value="3" id="kedisiplinan-3">
                            <input type="radio" class="form-check-input" name="kedisiplinan" value="4" id="kedisiplinan-4">
                            <input type="radio" class="form-check-input" name="kedisiplinan" value="5" id="kedisiplinan-5">
                        </div>
                        <div class="mt-2">
                            <span id="kedisiplinan-rating-value">1</span> Sampai 5
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Tugas</label>
                        <div class="rating rating-tugas">
                            <input type="radio" required class="form-check-input" name="tugas" value="1" id="tugas-1">
                            <input type="radio" class="form-check-input" name="tugas" value="2" id="tugas-2">
                            <input type="radio" class="form-check-input" name="tugas" value="3" id="tugas-3">
                            <input type="radio" class="form-check-input" name="tugas" value="4" id="tugas-4">
                            <input type="radio" class="form-check-input" name="tugas" value="5" id="tugas-5">
                        </div>
                        <div class="mt-2">
                            <span id="tugas-rating-value">1</span> Sampai 5
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Kirim Rating</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#ratingForm").on('submit', function (e) {
                e.preventDefault();
                const form = this;
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
@endsection
<!-- @push('scripts')
<script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
@endpush -->