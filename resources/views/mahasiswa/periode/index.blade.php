@extends('layouts.tamplate')
@section('content')
    <div class="row">
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card shadow">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2 d-flex align-items-center justify-content-center">
                                <img src="{{ asset('template/assets/compiled/jpg/1.jpg') }}" alt="Profile Icon"
                                    class="img-fluid rounded">
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Perusahaan</h6>
                            <h6 class="font-extrabold mb-0">{{$jumlahPerusahaan ?? '0'}}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card shadow">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon blue mb-2">
                                <i class="iconly-boldProfile"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Jenis Perusahaan</h6>
                            <h6 class="font-extrabold mb-0">{{$jumlahJenisPerusahaan ?? '0'}}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card shadow">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon green mb-2">
                                <i class="iconly-boldAdd-User"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Bidang</h6>
                            <h6 class="font-extrabold mb-0">{{$jumlahBidang ?? '0'}}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Lowongan Magang</h5>
            <div> 
                @if (!empty($perhitungan))
                <button id="btn-perhitungan" class="btn btn-warning">Perhitungan</button> {{-- Pertahankan kelas btn btn-primary --}}
                @endif
                <button id="btn-rekomendasi" class="btn btn-primary me-2"> 
                    <span id="text-rekomendasi">Rekomendasi</span>
                    <span id="spinner-rekomendasi" class="spinner-border spinner-border-sm d-none" role="status"
                        aria-hidden="true"></span>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ url('/mahasiswa/periode/') }}" class="row g-3 mb-4">
                <div class="col-md-2">
                    <label for="tanggal_mulai_filter" class="form-label">Tanggal Mulai</label>
                    <input type="date" id="tanggal_mulai_filter" name="tanggal_mulai_filter" class="form-control"
                        value="{{ $tanggal_mulai ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label for="tanggal_selesai_filter" class="form-label">Tanggal Selesai</label>
                    <input type="date" id="tanggal_selesai_filter" name="tanggal_selesai_filter" class="form-control"
                        value="{{ $tanggal_selesai ?? '' }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ url('/mahasiswa/periode') }}" class="btn btn-secondary me-2"><i class="bi bi-x-circle"></i>
                        Riset</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Filter</button>
                </div>
            </form>
            @if (count($periode))
                <div class="table-responsive">
                    <table class="table" id="table1">
                        <colgroup>
                            <col style="width: 10px;">
                            <col style="width: 100px;">
                            <col style="width: 100px;">
                            <col style="width: 100px;">
                            <col style="width: 100px;">
                            <col style="width: 100px;">
                            <col style="width: 100px;">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Lowongan</th>
                                <th>Perusahaan</th>
                                <th>Bidang</th>
                                <th>Periode</th>
                                <th>Daerah</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($periode as $i => $item)
                                <tr>
                                    <td>{{ $i+1 ?? '-' }}</td>
                                    <td>{{ $item->lowongan_magang->nama ?? '-' }}</td>
                                    <td>{{ $item->lowongan_magang->perusahaan->nama ?? '-' }}</td>
                                    <td>{{ $item->lowongan_magang->bidang->nama ?? '-' }}</td>
                                    <td>{{ $item->tanggal_mulai->format('d M Y') }} - {{ $item->tanggal_selesai->format('d M Y') }}
                                    </td>
                                    <td>{{ $item->lowongan_magang->perusahaan->provinsi . ' - ' . $item->lowongan_magang->perusahaan->daerah }}
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-info btn-detail"
                                            onclick="modalAction('{{ url('/mahasiswa/periode/detail/' . $item->id_periode) }}')">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center">
                    <p class="mt-4">Tidak ada Lowongan tersedia</p>
                </div>
            @endif
        </div>

        <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
            data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
    <script>
        $('#tanggal_mulai_filter').on('change', function () {
            const tanggalMulai = new Date($(this).val());
            tanggalMulai.setDate(tanggalMulai.getDate() + 1);

            const minTanggalSelesai = tanggalMulai.toISOString().split('T')[0];
            $('#tanggal_selesai_filter').attr('min', minTanggalSelesai);
        });

        $('#tanggal_selesai_filter').on('change', function () {
            const tanggalSelesai = new Date($(this).val());
            tanggalSelesai.setDate(tanggalSelesai.getDate() - 1);

            const maxTanggalMulai = tanggalSelesai.toISOString().split('T')[0];
            $('#tanggal_mulai_filter').attr('max', maxTanggalMulai);
        });
    </script>
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }

        document.getElementById('btn-rekomendasi').addEventListener('click', function () {
            const button = this;
            const text = document.getElementById('text-rekomendasi');
            const spinner = document.getElementById('spinner-rekomendasi');
            
            // Tampilkan spinner dan disable tombol
            button.disabled = true;
            text.textContent = 'Memproses...';
            spinner.classList.remove('d-none');
            
            setTimeout(() => {
                window.location.href = "{{ url('/mahasiswa/rekomendasi') }}";
            }, 1500);
        });
        document.getElementById('btn-perhitungan').addEventListener('click', function () {
            window.location.href = "{{ url('/mahasiswa/perhitungan') }}";
            });
    </script>
@endpush