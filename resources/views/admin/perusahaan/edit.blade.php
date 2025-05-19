<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form action="{{ url('/admin/perusahaan/update/' . $perusahaan->id_perusahaan) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Perusahaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Perusahaan</label>
                        <input type="text" class="form-control" name="nama" value="{{ $perusahaan->nama }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="telepon" class="form-label">Telepon</label>
                        <input type="text" class="form-control" name="telepon" value="{{ $perusahaan->telepon }}">
                    </div>

                    <div class="mb-3">
                        <label for="provinsi" class="form-label">Provinsi</label>
                        <input type="text" class="form-control" name="provinsi" value="{{ $perusahaan->provinsi }}">
                    </div>

                    <div class="mb-3">
                        <label for="daerah" class="form-label">Daerah</label>
                        <input type="text" class="form-control" name="daerah" value="{{ $perusahaan->daerah }}">
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ $perusahaan->deskripsi }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto (opsional)</label>
                        <input type="file" class="form-control" name="foto">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    $('#modal-edit').modal('show');
    
    $('#modal-edit').on('hidden.bs.modal', function () {
        $('.modal-backdrop').remove(); 
    });
</script>