<!DOCTYPE html>
<html>
<head>
    <title>Edit Pengalaman</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    <h2>Edit Deskripsi Pengalaman</h2>

    <form id="formPengalaman">
        <input type="hidden" name="id_pengalaman" value="{{ $pengalaman->id_pengalaman }}">
        <div>
            <label for="deskripsi">Deskripsi:</label><br>
            <textarea name="deskripsi" id="deskripsi" rows="6" cols="80">{{ $pengalaman->deskripsi }}</textarea>
        </div>
        <br>
        <button type="submit">Simpan</button>
        <button type="button" id="hapusBtn" style="margin-left: 10px; background-color: red; color: white;">Hapus</button>
    </form>

    <div id="pesan"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        // Tombol Simpan (Update)
        $('#formPengalaman').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ url('/mahasiswa/profil/edit/pengalaman') }}/" + {{ $pengalaman->id_pengalaman }},
                method: "PUT",
                data: $(this).serialize(),
                success: function(response) {
                    $('#pesan').html('<p style="color: green;">' + response.status + '</p>');
                },
                error: function(xhr) {
                    $('#pesan').html('<p style="color: red;">' + xhr.responseJSON.status + '</p>');
                }
            });
        });

        // Tombol Hapus
        $('#hapusBtn').on('click', function () {
            if (confirm('Yakin ingin menghapus pengalaman ini?')) {
                $.ajax({
                    url: "{{ url('/mahasiswa/profil/edit/pengalaman/') }}/" + {{ $pengalaman->id_pengalaman }},
                    method: "DELETE",
                    success: function(response) {
                        $('#pesan').html('<p style="color: green;">' + response.message + '</p>');
                        $('#formPengalaman').hide();
                    },
                    error: function(xhr) {
                        $('#pesan').html('<p style="color: red;">' + xhr.responseJSON.message + '</p>');
                    }
                });
            }
        });
    </script>

</body>
</html>
