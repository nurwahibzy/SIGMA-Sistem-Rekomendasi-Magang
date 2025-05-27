@extends('layouts.tamplate')

@section('content')
    <div class="page-heading">
        <h3>Feedback Perusahaan</h3>
    </div>

    <div class="page-content">
        <div class="row">
            @if(empty($magang))
                <div class="alert alert-warning">
                    Belum ada magang yang diterima atau lulus.
                </div>
            @else
                <div class="row">
                    @foreach($magang as $item)
                        <div class="col-md-6 col-xl-4">
                            <div class="card shadow-sm border-0 mb-4">
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


                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <a href="{{ url('mahasiswa/aktivitas/' . $item->id_magang) }}"
                                            class="btn-edit-section btn btn-outline-primary me-2">
                                            <i class="bi bi-pencil-square"></i> Detail
                                        </a>

                                        @php
                                            $sudahDinilai = \App\Models\PenilaianModel::where('id_magang', $item->id_magang)->exists();
                                        @endphp

                                        @if($sudahDinilai)
                                            <a href="{{ route('penilaian.get', $item->id_magang) }}" class="btn-edit-section btn btn-outline-primary me-2">
                                                <i class="bi bi-pencil-square"></i> Sertifikat
                                            </a>
                                        @endif

                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
@endpush