<form id="editForm" enctype="multipart/form-data" class="mt-4">
    @csrf

    <div class="mb-3">
        <label for="file" class="form-label">Unggah File</label>
        <input class="form-control" type="file" id="file" name="file" accept=".jpg,.jpeg,.png>
    </div>

    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea class="form-control" id="deskripsi" name="keterangan" required rows="5">{{ $keterangan }}</textarea>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-upload"></i> Kirim
            </button>
        </div>
        <div id="result" class="text-muted small"></div>
    </div>
</form>