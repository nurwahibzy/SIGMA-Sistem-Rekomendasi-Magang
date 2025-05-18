<div class="modal fade" id="modalPenilaian" tabindex="-1" aria-labelledby="modalPenilaianLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPenilaianLabel">Beri Penilaian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form id="penilaianForm">
                    @csrf
                    <div class="mb-3">
                        <label for="fasilitas" class="form-label">Fasilitas</label>
                        <input type="text" class="form-control" id="fasilitas" name="fasilitas" placeholder="Tulis pendapat tentang fasilitas">
                    </div>
                    <div class="mb-3">
                        <label for="tugas" class="form-label">Tugas</label>
                        <input type="text" class="form-control" id="tugas" name="tugas" placeholder="Tulis pendapat tentang tugas">
                    </div>
                    <div class="mb-3">
                        <label for="kedisiplinan" class="form-label">Kedisiplinan</label>
                        <input type="text" class="form-control" id="kedisiplinan" name="kedisiplinan" placeholder="Tulis pendapat tentang kedisiplinan">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="submitPenilaian">Kirim</button>
            </div>
        </div>
    </div>
</div>
