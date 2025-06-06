@extends('layouts.tamplate')

@section('content')
    <div class="row">
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card shadow">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon green mb-2">
                                <i class="bi-clock-fill"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Segera</h6>
                            <h6 class="font-extrabold mb-0">{{ $segera }}</h6>
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
                                <i class="bi-play-circle-fill"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Berlangsung</h6>
                            <h6 class="font-extrabold mb-0">{{ $berlangsung }}</h6>
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
                                <i class="bi-check-circle-fill"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Selesai</h6>
                            <h6 class="font-extrabold mb-0">{{ $selesai }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Periode</h5>
            <button onclick="modalAction('{{ url('/admin/periode/tambah') }}')" class="btn btn-primary"><i
                    class="bi bi-plus"></i>Tambah
                Periode</button>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ url('/admin/periode/') }}" class="row g-3 mb-4">
                <div class="col-md-2">
                    <label for="tanggal_mulai_filter" class="form-label">Tanggal Mulai</label>
                    <input type="date" id="tanggal_mulai_filter" name="tanggal_mulai_filter" class="form-control" value="{{ $tanggal_mulai ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label for="tanggal_selesai_filter" class="form-label">Tanggal Selesai</label>
                    <input type="date" id="tanggal_selesai_filter" name="tanggal_selesai_filter" class="form-control"
                        value="{{ $tanggal_selesai ?? '' }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ url('/admin/periode') }}" class="btn btn-secondary me-2"><i class="bi bi-x-circle"></i> Riset</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Filter</button>
                </div>
            </form>
            @if (count($periode))
                <div class="table-responsive">
                    <table class="table" id="table1">
                        <colgroup>
                            <!-- <col style="width: 10px;"> -->
                            <col style="width: 100px;">
                            <col style="width: 100px;">
                            <col style="width: 100px;">
                            <col style="width: 100px;">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="d-none">No</th>
                                <th>Perusahaan</th>
                                <th>Lowongan</th>
                                <th>Waktu</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($periode as $i => $item)
                                <tr>
                                    <td class="d-none">{{ $i + 1 }}</td>
                                    <td>{{ $item->lowongan_magang->perusahaan->nama ?? '-' }}</td>
                                    <td>{{ $item->lowongan_magang->nama . ' - ' . $item->nama ?? '-' }}</td>
                                    <td>{{ $item->tanggal_mulai->format('d M Y') }} - {{ $item->tanggal_selesai->format('d M Y') }}
                                    <td class="text-center"> <button class="btn btn-sm btn-info btn-detail"
                                            onclick="modalAction('{{ url('/admin/periode/detail/' . $item->id_periode) }}')">
                                            Detail
                                        </button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center">
                    <p class="mt-4">Tidak ada periode tersedia</p>
                </div>
            @endif
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    </div>
@endsection
@push('css')
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

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


        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');

                $('#myModal').find('#id_lowongan').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#myModal'),
                    placeholder: $('#id_lowongan').data('placeholder'),
                    width: '100%'
                });
            });
        }
    </script>
@endpush