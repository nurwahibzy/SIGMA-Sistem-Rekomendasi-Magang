<div class="modal-header">
    <h5 class="modal-title">Konfirmasi Hapus Aktivitas</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
    
    <p>Yakin ingin menghapus aktivitas berikut?</p>

    <ul style="padding-left: 1.2rem;">
        <li>
            <strong>Deskripsi:</strong>
            <div style="white-space: pre-wrap;">{{ $aktivitas->keterangan }}</div>
        </li>
        <li>
            <strong>File:</strong><br>
            @if ($aktivitas->foto_path)
                <img src="{{ asset('storage/aktivitas/' . $aktivitas->foto_path) }}" alt="Preview File"
                    class="img-thumbnail mt-2" style="max-width: 200px; height: auto;">
            @else
                <span class="text-muted">Tidak ada file</span>
            @endif
        </li>
        <li>
            <strong>Tanggal:</strong>
            {{ \Carbon\Carbon::parse($aktivitas->tanggal)->format('d M Y') }}
        </li>
    </ul>

    <div class="text-end mt-3">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn" data-id="{{ $aktivitas->id_aktivitas }}"
            data-idmagang="{{ $id_magang }}">
            Ya, Hapus
        </button>
    </div>
</div>