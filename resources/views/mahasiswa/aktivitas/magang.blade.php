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
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->periode_magang->lowongan_magang->nama ?? '-' }}</h5>
                            <p class="card-text">
                                Perusahaan: {{ $item->periode_magang->lowongan_magang->perusahaan->nama ?? '-' }}<br>
                                Bidang: {{ $item->periode_magang->lowongan_magang->bidang->nama ?? '-' }}<br>
                                Periode: {{ $item->periode_magang->tanggal_mulai ?? '-' }} s/d {{ $item->periode_magang->tanggal_selesai ?? '-' }}
                            </p>
                            <a href="{{ url('mahasiswa/aktivitas/' . $item->id_magang) }}" class="btn btn-primary">Lihat Aktivitas</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
