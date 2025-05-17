<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Perusahaan</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Tambah Perusahaan</h2>

        <form id="formPerusahaan" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="id_jenis" class="form-label">Jenis Perusahaan</label>
                <select name="id_jenis" id="id_jenis" class="form-control" required>
                    <option value="">Pilih jenis</option>
                    @foreach ($jenis as $item)
                        <option value="{{ $item->id_jenis }}">{{ $item->jenis }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Perusahaan</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>

            <div class="mb-3">
                <label for="telepon" class="form-label">Telepon</label>
                <input type="text" class="form-control" id="telepon" name="telepon" required>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label for="provinsi" class="form-label">Provinsi</label>
                <input type="text" class="form-control" id="provinsi" name="provinsi" required>
            </div>

            <div class="mb-3">
                <label for="daerah" class="form-label">Daerah</label>
                <input type="text" class="form-control" id="daerah" name="daerah" required>
            </div>

            <div class="mb-3">
                <label for="file" class="form-label">File (opsional)</label>
                <input type="file" class="form-control" id="file" name="file">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>

        <div id="response-message" class="mt-3"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#formPerusahaan').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "{{ url('/admin/perusahaan/tambah') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#response-message').html('<div class="alert alert-success">Perusahaan berhasil ditambahkan!</div>');
                    $('#formPerusahaan')[0].reset();
                    console.log(response);
                },
                error: function (xhr) {
                    $('#response-message').html('<div class="alert alert-danger">Gagal menambahkan perusahaan.</div>');
                    console.error(xhr.responseText);
                }
            });
        });
    </script>
</body>

</html>
