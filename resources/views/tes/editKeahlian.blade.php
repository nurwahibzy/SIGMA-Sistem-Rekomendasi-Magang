<!DOCTYPE html>
<html>
<head>
    <title>Edit Keahlian (AJAX)</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    <h2>Edit Keahlian</h2>

    <form id="formKeahlian">
        <!-- Hidden ID Keahlian -->
        <input type="hidden" name="id_keahlian_mahasiswa" value="{{ $data['pilihan_terakhir']['id_keahlian_mahasiswa'] }}">

        <!-- Dropdown Bidang -->
        <div>
            <label for="bidang">Pilih Bidang:</label>
            <select name="id_bidang" id="bidang" required>
                @foreach ($data['bidang'] as $bidang)
                    <option value="{{ $bidang['id_bidang'] }}"
                        {{ $bidang['id_bidang'] == $data['pilihan_terakhir']['id_bidang'] ? 'selected' : '' }}>
                        {{ $bidang['nama'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <br>

        <!-- Dropdown Prioritas -->
        <div>
            <label for="prioritas">Prioritas Keahlian:</label>
            <select name="prioritas" id="prioritas" required>
                @for ($i = 1; $i <= $data['prioritas']; $i++)
                    <option value="{{ $i }}"
                        {{ $i == $data['pilihan_terakhir']['prioritas'] ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                @endfor
            </select>
        </div>

        <br>

        <!-- Input Keahlian -->
        <div>
            <label for="keahlian">Keahlian (opsional):</label><br>
            <input type="text" name="keahlian" id="keahlian"
                value="{{ $data['pilihan_terakhir']['keahlian'] ?? '' }}"
                placeholder="Tuliskan keahlian jika perlu" style="width: 300px;">
        </div>

        <br>

        <button type="submit">Simpan Perubahan</button>
        <button type="button" id="hapusBtn" style="background-color: red; color: white;">Hapus</button>
    </form>

    <div id="pesan" style="margin-top: 20px;"></div>

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        // ID dari hidden input
        const keahlianId = $('input[name="id_keahlian_mahasiswa"]').val();

        // Update Keahlian
        $('#formKeahlian').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: "{{ url('/mahasiswa/profil/edit/keahlian') }}/" + {{ $data['pilihan_terakhir']['id_keahlian_mahasiswa'] }},
                method: "PUT",
                data: $(this).serialize(),
                success: function (response) {
                    $('#pesan').html('<p style="color: green;">' + response.message + '</p>');
                },
                error: function (xhr) {
                    let msg = xhr.responseJSON?.message || 'Terjadi kesalahan saat update.';
                    $('#pesan').html('<p style="color: red;">' + msg + '</p>');
                }
            });
        });

        // Hapus Keahlian
        $('#hapusBtn').on('click', function (e) {
            if (confirm('Yakin ingin menghapus keahlian ini?')) {
                $.ajax({
                    url: "{{ url('/mahasiswa/profil/edit/keahlian') }}/" + {{ $data['pilihan_terakhir']['id_keahlian_mahasiswa'] }} + "/"  + {{ $data['pilihan_terakhir']['prioritas'] }},
                    data: $(this).serialize(),
                    method: "DELETE",
                    success: function (response) {
                        $('#pesan').html('<p style="color: green;">' + response.message + '</p>');
                        $('#formKeahlian').hide();
                    },
                    error: function (xhr) {
                        let msg = xhr.responseJSON?.message || 'Terjadi kesalahan saat menghapus.';
                        $('#pesan').html('<p style="color: red;">' + msg + '</p>');
                    }
                });
            }
        });
    </script>

</body>
</html>
