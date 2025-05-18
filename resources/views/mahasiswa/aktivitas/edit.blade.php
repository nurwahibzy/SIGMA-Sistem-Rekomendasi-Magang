<form id="editForm" enctype="multipart/form-data" class="mt-4">
    @csrf

    <div class="mb-3">
        <label for="file" class="form-label">Unggah File</label>
        <input class="form-control" type="file" id="file" name="file">
    </div>

    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea class="form-control" id="deskripsi" name="keterangan" rows="5">{{ $keterangan }}</textarea>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-upload"></i> Kirim
            </button>
            <button type="button" id="deleteBtn" class="btn btn-outline-danger ms-2">
                <i class="bi bi-trash"></i> Hapus Dokumen
            </button>
        </div>
        <div id="result" class="text-muted small"></div>
    </div>
</form>

<script>
    $(function () {
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $('#editForm').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: `/mahasiswa/aktivitas/{{ $id_magang }}/edit/{{ $id_aktivitas }}`,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (res) {
                    $('#result').html(`<p style="color:green;">${res.message}</p>`);
                },
                error: function (xhr) {
                    $('#result').html(`<p style="color:red;">Terjadi kesalahan: ${xhr.responseText}</p>`);
                }
            });
        });

        $('#deleteBtn').on('click', function () {
            if (confirm('Yakin ingin menghapus dokumen ini?')) {
                $.ajax({
                    url: `/mahasiswa/aktivitas/{{ $id_magang }}/edit/{{ $id_aktivitas }}`,
                    type: 'DELETE',
                    success: function (res) {
                        if (res.status) {
                            $('#result').html(`<p style="color:green;">${res.message}</p>`);
                            $('#editForm')[0].reset();
                        } else {
                            $('#result').html(`<p style="color:red;">${res.message}</p>`);
                        }
                    },
                    error: function (xhr) {
                        $('#result').html(`<p style="color:red;">Terjadi kesalahan: ${xhr.responseText}</p>`);
                    }
                });
            }
        });
    });
</script>
