<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Form Upload dengan AJAX</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <h2>Form Upload (Nama + File)</h2>
    <form id="uploadForm" enctype="multipart/form-data">

        <label>File:</label><br>
        <input type="file" name="file"><br><br>

        <label for="deskripsi">Deskripsi:</label><br>
        <textarea name="keterangan" id="deskripsi" rows="6" cols="80"></textarea>

        <button type="submit">Kirim</button>
    </form>

    <div id="result"></div>

    <script>
        $(document).ready(function () {
            // Sertakan CSRF token ke semua request AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#uploadForm').on('submit', function (e) {
                e.preventDefault(); // Hindari reload halaman

                var formData = new FormData(this);

                $.ajax({
                    url: '{{ url("/mahasiswa/aktivitas/$id_magang/tambah") }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#result').html('<p style="color:green;">' + response.message + '</p>');
                    },
                    error: function (xhr) {
                        $('#result').html('<p style="color:red;">Terjadi kesalahan: ' + xhr.responseText + '</p>');
                    }
                });
            });
        });
    </script>

</body>

</html>