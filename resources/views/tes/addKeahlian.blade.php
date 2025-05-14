<!DOCTYPE html>
<html>
<head>
    <title>Tambah Keahlian (AJAX)</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    <h2>Form Tambah Keahlian</h2>

    <form id="formKeahlian" method="POST">
        <!-- Dropdown Bidang -->
        <div>
            <label for="bidang">Pilih Bidang:</label>
            <select name="id_bidang" id="bidang" required>
                <option value="" disabled selected>-- Pilih Bidang --</option>
                @foreach ($data['bidang'] as $bidang)
                    <option value="{{ $bidang['id_bidang'] }}">{{ $bidang['nama'] }}</option>
                @endforeach
            </select>
        </div>

        <br>

        <!-- Dropdown Prioritas -->
        <div>
            <label for="prioritas">prioritas Keahlian:</label>
            <select name="prioritas" id="prioritas" required>
                <option value="" disabled selected>-- Pilih Prioritas --</option>
                @for ($i = 1; $i <= $data['prioritas']; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>

        <br>

        <!-- Input Text -->
        <div>
            <label for="keahlian">Keahlian (opsional):</label><br>
            <input type="text" name="keahlian" id="keahlian" placeholder="Tuliskan keahlian jika perlu" style="width: 300px;">
        </div>

        <br>

        <button type="submit">Simpan</button>
    </form>

    <div id="pesan" style="margin-top: 20px;"></div>

    

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // CSRF Token for Laravel
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        // Handle form submit via AJAX
        $('#formKeahlian').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ url('/mahasiswa/profil/edit/keahlian') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $('#pesan').html('<p style="color: green;">' + response.message + '</p>');
                    $('#formKeahlian')[0].reset();
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON?.message || 'Terjadi kesalahan';
                    $('#pesan').html('<p style="color: red;">' + msg + '</p>');
                }
            });
        });
    </script>

</body>
</html>
