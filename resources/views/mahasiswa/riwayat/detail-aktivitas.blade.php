<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content shadow-sm rounded">

        <div class="modal-header">
            <h5 class="modal-title">Detail Admin</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="container mt-4">

                <div class="mb-3">
                    <h5>File:</h5>
                    @if($aktivitas->foto_path)
                        <img src="{{ asset('storage/aktivitas/' . $aktivitas->foto_path) }}" alt="Foto Aktivitas"
                            class="img-thumbnail mt-2" style="max-width: 300px; height: auto;">
                    @else
                        <span class="text-muted">Tidak ada file</span>
                    @endif

                </div>

                <div class="mb-3">
                    <h5>Tanggal:</h5>
                    <p>{{ \Carbon\Carbon::parse($aktivitas->tanggal)->format('d M Y') }}</p>
                </div>

                <div class="mb-3">
                    <h5>Deskripsi:</h5>
                    <p>{{ $aktivitas->keterangan }}</p>
                </div>
            </div>
        </div>
    </div>
</div>