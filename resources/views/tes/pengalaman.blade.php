<!DOCTYPE html>
<html>

<head>
    <title>Form Deskripsi AJAX</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <h3>Form Deskripsi</h3>

    <form id="formDeskripsi">
        <label for="deskripsi">Deskripsi:</label><br>
        <textarea name="deskripsi" id="deskripsi" rows="4" cols="50"></textarea><br><br>

        <button type="submit">Kirim</button>
    </form>

    <div id="response"></div>

    <script>
        $(document).ready(function () {
            $('#formDeskripsi').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ url('/mahasiswa/profil/edit/pengalaman') }}', // Sesuaikan route-nya
                    type: 'POST',
                    data: {
                        deskripsi: $('#deskripsi').val(),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#pesan').html('<p style="color: green;">' + response.status + '</p>');
                    },
                    error: function (xhr) {
                        $('#pesan').html('<p style="color: red;">' + xhr.responseJSON.status + '</p>');
                    }
                });
            });
        });
    </script>

</body>

</html>