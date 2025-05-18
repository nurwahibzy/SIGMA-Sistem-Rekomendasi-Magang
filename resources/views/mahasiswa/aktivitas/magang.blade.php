@extends('layouts.tamplate')

@section('content')
    <div class="page-heading">
        <h3>Log Aktivitas Magang</h3>
    </div>

    <div class="page-content">
        <div class="row">
            @if($magang->isEmpty())
                <div class="alert alert-warning">
                    Belum ada magang yang diterima.
                </div>
            @else
                @foreach($magang as $item)
                    <div class="col-md-6 col-xl-4">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar bg-light-primary me-3">
                                        <i class="bi bi-building fs-4 text-primary"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0">{{ $item->periode_magang->lowongan_magang->nama ?? '-' }}</h5>
                                        <small
                                            class="text-muted">{{ $item->periode_magang->lowongan_magang->perusahaan->nama ?? '-' }}</small>
                                    </div>
                                </div>

                                <p class="mb-1"><strong>Bidang:</strong>
                                    {{ $item->periode_magang->lowongan_magang->bidang->nama ?? '-' }}</p>
                                <p class="mb-1">
                                    <strong>Periode:</strong>
                                    {{ \Carbon\Carbon::parse($item->periode_magang->tanggal_mulai)->format('d M Y') ?? '-' }} s/d
                                    {{ \Carbon\Carbon::parse($item->periode_magang->tanggal_selesai)->format('d M Y') ?? '-' }}
                                </p>

                                <a href="{{ url('mahasiswa/aktivitas/' . $item->id_magang) }}" class="btn btn-primary">
                                    Lihat Aktivitas
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
@push('scripts')
<script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
@endpush