<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengalaman</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- CSRF token --}}
    <style>
        .checkbox-label {
            display: inline-block;
            padding: 10px 15px;
            margin: 5px;
            border-radius: 10px;
            border: 2px solid #ccc;
            background-color: #f2f2f2;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .checkbox-input {
            display: none;
        }
        .checkbox-input:checked+.checkbox-label {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
    </style>
</head>
<body>
<h3>Form Perusahaan Mahasiswa</h3>
<form id="form-preferensi" method="POST">
    @csrf
    <div>
        @forelse($jenis as $item)
            @php
                $isChecked = collect($preferensi_perusahaan)->pluck('id_jenis')->contains($item->id_jenis);
            @endphp
            <input type="checkbox" id="jenis_{{ $item->id_jenis }}" name="id_jenis[]" value="{{ $item->id_jenis }}" class="checkbox-input" {{ $isChecked ? 'checked' : '' }}>
            <label for="jenis_{{ $item->id_jenis }}" class="checkbox-label">{{ $item->jenis }}</label>
        @empty
            <p>Bidang tidak tersedia</p>
        @endforelse
    </div>
    <br><br>
    <button type="submit">Kirim Form 4</button>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#form-preferensi').on('submit', function (e) {
            e.preventDefault(); // Mencegah reload halaman

            var formData = $(this).serialize(); // Mengambil semua data form

            $.ajax({
                url: "{{ url('mahasiswa/profil/edit/preferensi/perusahaan') }}", // Ganti dengan route yang sesuai
                type: "POST",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Tambah token CSRF
                },
                success: function (response) {
                    alert('Preferensi perusahaan berhasil disimpan!');
                    // Atau gunakan swal/toast
                },
                error: function (xhr, status, error) {
                    alert('Terjadi kesalahan saat menyimpan data.');
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>

</body>
</html>
