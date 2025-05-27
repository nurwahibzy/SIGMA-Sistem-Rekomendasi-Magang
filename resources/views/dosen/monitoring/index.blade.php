@extends('layouts.tamplate')

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Monitoring Magang</h5>
        </div>
        <div class="card-body">
            @if (count($magang))
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
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Perusahaan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($magang as $item)
                                <tr>
                                    <td>{{ $item->mahasiswa->akun->id_user ?? '-' }}</td>
                                    <td>{{ $item->mahasiswa->nama ?? '-' }}</td>
                                    <td>{{ $item->periode_magang->lowongan_magang->perusahaan->nama ?? '-' }}</td>
                                    <td>                                    
                                        <span class="badge 
                                            @if($item->status == 'diterima') bg-warning
                                            @elseif($item->status == 'lulus') bg-success
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($item->status ?? '-') }}
                                        </span>
                                    </td>
                                    <td class="text-left"> <button class="btn btn-sm btn-info btn-detail"
                                        onclick="modalAction('{{ url('/dosen/aktivitas/detail/' . $item->id_magang) }}')">
                                    Detail
                                </button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center">
                    <p class="mt-4">Tidak ada kegiatan tersedia</p>
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
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }
    </script>
@endpush