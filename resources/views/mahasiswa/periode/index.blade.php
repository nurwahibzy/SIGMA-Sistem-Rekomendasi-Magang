@extends('layouts.tamplate')
@section('content')
    <div class="row">
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
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
            <div class="card">
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
            <div class="card">
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
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Lowongan Magang</h5>
        </div>
        <div class="card-body">
            @if (count($periode))
                <div class="table-responsive">
                    <table class="table" id="table1">
                        <colgroup>
                            <col style="width: 100px;">
                            <col style="width: 100px;">
                            <col style="width: 100px;">
                            <col style="width: 100px;">
                            <col style="width: 100px;">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>Nama Lowongan</th>
                                <th>Perusahaan</th>
                                <th>Bidang</th>
                                <th>Periode</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($periode as $item)
                                <tr>
                                    <td>{{ $item->lowongan_magang->nama ?? '-' }}</td>
                                    <td>{{ $item->lowongan_magang->perusahaan->nama ?? '-' }}</td>
                                    <td>{{ $item->lowongan_magang->bidang->nama ?? '-' }}</td>
                                    <td>{{ $item->tanggal_mulai->format('d M Y') }} - {{ $item->tanggal_selesai->format('d M Y') }}
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
@endsection

@push('css')
@endpush

@push('js')
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }
    </script>
@endpush