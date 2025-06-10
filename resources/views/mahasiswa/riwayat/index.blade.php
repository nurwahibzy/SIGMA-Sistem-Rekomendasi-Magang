@extends('layouts.tamplate')

@section('content')
    <div class="page-heading">
        <h3>Riwayat Magang</h3>
    </div>

    <div class="page-content">
        <div class="row">
            @if(count($magang))
                <div class="row d-flex align-items-stretch">
                    @foreach($magang as $item)
                            <div class="col-md-6 col-xl-4">
                                <div class="card shadow border-0 mb-4 h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar bg-light-primary me-3">
                                                <i class="bi bi-building fs-4 text-primary"></i>
                                            </div>
                                            <div>
                                                <h5 class="card-title mb-0">
                                                    {{ optional(optional($item->periode_magang)->lowongan_magang)->nama ?? '-' }}
                                                </h5>
                                                <small class="text-muted">
                                                    {{ optional(optional($item->periode_magang)->lowongan_magang->perusahaan)->nama ?? '-' }}
                                                </small>
                                            </div>
                                        </div>

                                        <p class="mb-1">
                                            <strong>Bidang:</strong>
                                            {{ optional(optional($item->periode_magang)->lowongan_magang->bidang)->nama ?? '-' }}
                                        </p>
                                        <p class="mb-1">
                                            <strong>Periode:</strong>
                                            {{ \Carbon\Carbon::parse(optional($item->periode_magang)->tanggal_mulai)->format('d M Y') ?? '-' }}
                                            s/d
                                            {{ \Carbon\Carbon::parse(optional($item->periode_magang)->tanggal_selesai)->format('d M Y') ?? '-' }}
                                        </p>
                                        <p class="mb-1">
                                            <strong>Status:</strong>
                                            <td>
                                                <span class="badge 
                                                                                                @if($item->status == 'diterima') bg-warning
                                                                                                @elseif($item->status == 'lulus') bg-success
                                                                                                @elseif($item->status == 'ditolak') bg-danger
                                                                                                    @else bg-secondary
                                                                                                @endif">
                                                    {{ ucfirst($item->status ?? '-') }}
                                                </span>
                                            </td>
                                        </p>
                                        <p class="mb-1">
                                            @if ($item->status == 'ditolak')
                                                <strong>Alasan Penolakan:</strong>
                                                {{ $item->alasan_penolakan ?? '-' }}
                                            @endif
                                        </p>

                                        <div class="d-flex justify-content-between align-items-center mt-4">
                                            @if ($item->status == 'lulus')
                                                <a href="{{ url('mahasiswa/riwayat/aktivitas/' . $item->id_magang) }}"
                                                    class="btn btn-primary">
                                                    <i class="bi bi-eye-fill"></i> Detail
                                                </a>
                                            @elseif ($item->status == 'diterima')
                                            <a href="{{ url('mahasiswa/aktivitas/' . $item->id_magang) }}"
                                                    class="btn btn-primary">
                                                    <i class="bi bi-pencil-square"></i> Aktivitas
                                                </a>
                                            @endif

                                            @php
                                                $sudahDinilai = \App\Models\PenilaianModel::where('id_magang', $item->id_magang)->exists();
                                            @endphp

                                            {{-- Jika sudah dinilai, tampilkan tombol download sertifikat --}}
                                            @if($sudahDinilai && $item->status == 'lulus')
                                                <a href="{{ route('penilaian.download', $item->id_magang) }}" class="btn btn-success">
                                                    <i class="bi bi-file-earmark-pdf-fill"></i> Sertifikat
                                                </a>
                                            @else
                                                {{-- Jika belum dinilai, tampilkan tombol untuk mengisi penilaian --}}
                                                @if ($item->status == 'lulus')
                                                    <a href="{{ route('penilaian.get', $item->id_magang) }}" class="btn btn-warning">
                                                        <i class="bi bi-pencil-square"></i> Isi Penilaian
                                                    </a>
                                                @endif
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                    @endforeach
                </div>

            @else
                <div class="alert alert-warning text-center">
                    Belum ada riwayat magang.
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
@endpush