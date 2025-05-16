@extends('layouts.tamplate')
@section('content')
    <!-- Basic Tables start -->
    <link rel="stylesheet"
        href=" {{ asset('template/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}">
    <link rel="stylesheet" href=" {{asset('template/assets/compiled/css/table-datatable-jquery.css')}}">
    <link rel="stylesheet" href=" {{ asset('template/assets/compiled/css/app.css')}}">
    <link rel="stylesheet" href=" {{asset('template/assets/compiled/css/app-dark.css')}}">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                Lowongan Magang
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th>Nama Lowongan</th>
                            <th>Perusahaan</th>
                            <th>Bidang</th>
                            <th>Periode</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($magang as $item)
                            <tr>
                                <td>{{ $item->lowongan_magang->nama ?? '-' }}</td>
                                <td>{{ $item->lowongan_magang->perusahaan->nama ?? '-' }}</td>
                                <td>{{ $item->lowongan_magang->bidang->nama ?? '-' }}</td>
                                <td>{{ $item->tanggal_mulai->format('d M Y') }} - {{ $item->tanggal_selesai->format('d M Y') }}
                                </td>
                                <td>
                                    <span class="badge {{ now()->lt($item->tanggal_mulai) ? 'bg-success' : 'bg-secondary' }}">
                                        {{ now()->lt($item->tanggal_mulai) ? 'Detail' : 'Closed' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada lowongan tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <!-- Basic Tables end -->
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('template/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>


    <script src="{{ asset('template/assets/compiled/js/app.js') }}"></script>



    <script src="{{ asset('template/assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('template/assets/static/js/pages/datatables.js') }}"></script>

@endsection