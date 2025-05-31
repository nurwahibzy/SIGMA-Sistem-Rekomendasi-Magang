<div class="mb-3">
    <h5>File:</h5>
    @if($aktivitas->foto_path)
        <img src="{{ asset('storage/aktivitas/' . $aktivitas->foto_path) }}" alt="Foto Aktivitas" class="img-thumbnail mt-2"
            style="max-width: 300px; height: auto;">
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

@php
    $activityDate = \Carbon\Carbon::parse($aktivitas->tanggal)->startOfDay();
    $today = \Carbon\Carbon::now()->startOfDay();
    $isPastActivity = $activityDate->lt($today);
@endphp

@if($isPastActivity)
    <div class="alert alert-info mb-3">
        <i class="bi bi-info-circle"></i> Aktivitas tanggal sebelumnya tidak dapat diubah atau dihapus.
    </div>
@endif

<div class="d-flex justify-content-end">
    <button class="btn btn-danger me-2" id="btnHapus" data-id="{{ $aktivitas->id_aktivitas }}" {{ $isPastActivity ? 'disabled' : '' }}>
        <i class="bi bi-trash"></i> Hapus
    </button>
    <button class="btn btn-warning me-2" id="btnEdit" data-id="{{ $aktivitas->id_aktivitas }}" {{ $isPastActivity ? 'disabled' : '' }}>
        <i class="bi bi-pencil"></i> Edit
    </button>
</div>