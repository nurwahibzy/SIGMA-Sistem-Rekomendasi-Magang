@extends('layouts.tamplate')

@section('content')

    <div class="card">
        <!-- <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-tools">
                    <button onclick="modalAction('{{ url('/admin/kegiatan/tambah') }}')"
                        class="btn btn-sm btn-success mt-1">Tambah Data</button>
                </div>
            </div>
        </div> -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Kegiatan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="table1">
                    <colgroup>
                        <col style="width: 100px;">
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
                            <th>Dosen</th>
                            <th>Perusahaan</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($magang as $item)
                            <tr>
                                <td>{{ $item->mahasiswa->akun->id_user ?? '-' }}</td>
                                <td>{{ $item->mahasiswa->nama ?? '-' }}</td>
                                <td>{{ $item->dosen->nama ?? '-' }}</td>
                                <td>{{ $item->periode_magang->lowongan_magang->perusahaan->nama ?? '-' }}</td>
                                <td>{{ $item->status ?? '-' }}</td>
                                <td class="text-center"> <button class="btn btn-sm btn-info btn-detail"
                                        onclick="modalAction('{{ url('/admin/kegiatan/detail/' . $item->id_magang) }}')">
                                        Detail
                                    </button></td>
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
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    </div>
@endsection
@push('css')
@endpush
@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }
    </script>
@endpush