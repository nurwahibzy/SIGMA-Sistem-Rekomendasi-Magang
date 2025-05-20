@extends('layouts.tamplate')

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Periode</h5>
            <button onclick="modalAction('{{ url('/admin/periode/tambah') }}')" class="btn btn-primary">Tambah
                Periode</button>
        </div>
        <div class="card-body">
        <div class="table-responsive">
                <table class="table" id="table1">
                    <colgroup>
                        <col style="width: 100px;">
                        <col style="width: 100px;"> 
                        <col style="width: 100px;">
                        <col style="width: 100px;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Perusahaan</th>
                            <th>Lowongan</th>
                            <th>Waktu</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($periode as $item)  
                            <tr>
                                <td>{{ $item->lowongan_magang->perusahaan->nama ?? '-' }}</td>
                                <td>{{ $item->lowongan_magang->nama . ' - ' . $item->nama ?? '-' }}</td>
                                <td>{{ $item->tanggal_mulai->format('d M Y') }} - {{ $item->tanggal_selesai->format('d M Y') }}
                                <td class="text-center"> <button class="btn btn-sm btn-info btn-detail"
                                        onclick="modalAction('{{ url('/admin/periode/detail/' . $item->id_periode) }}')">
                                        Detail
                                    </button></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada periode tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
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