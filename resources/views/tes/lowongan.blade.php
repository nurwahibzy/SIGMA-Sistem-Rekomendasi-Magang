<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Lowongan Magang</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Tambah Lowongan Magang</h2>

    <form id="formLowongan">
        <div class="mb-3">
            <label for="id_perusahaan" class="form-label">Perusahaan</label>
            <select id="id_perusahaan" name="id_perusahaan" class="form-control" required>
                <option value="">Pilih perusahaan</option>
                @foreach ($data['perusahaan'] as $p)
                    <option value="{{ $p->id_perusahaan }}">{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="id_bidang" class="form-label">Bidang</label>
            <select id="id_bidang" name="id_bidang" class="form-control" required>
                <option value="">Pilih bidang</option>
                @foreach ($data['bidang'] as $b)
                    <option value="{{ $b->id_bidang }}">{{ $b->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Lowongan</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
        </div>

        <div class="mb-3">
            <label for="persyaratan" class="form-label">Persyaratan</label>
            <textarea class="form-control" id="persyaratan" name="persyaratan" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
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

    $('#formLowongan').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: "{{ url('/admin/lowongan/edit/2') }}",
            method: "DELETE",
            data: $(this).serialize(),
            success: function (response) {
                $('#response-message').html('<div class="alert alert-success">Lowongan berhasil ditambahkan!</div>');
                $('#formLowongan')[0].reset();
            },
            error: function (xhr) {
                $('#response-message').html('<div class="alert alert-danger">Gagal menambahkan lowongan.</div>');
                console.error(xhr.responseText);
            }
        });
    });
</script>
</body>
</html>
