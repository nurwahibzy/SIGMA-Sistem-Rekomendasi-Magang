@extends('layouts.tamplate')

@section('content')
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Lowongan</h5>
            <button onclick="modalAction('{{ url('/admin/lowongan/tambah') }}')" class="btn btn-primary"><i
                    class="bi bi-plus"></i>Tambah
                Lowongan</button>
        </div>
        <div class="card-body">
            @if (count($lowongan))
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
                                <th>Lowongan</th>
                                <th>Perusahaan</th>
                                <th>Bidang</th>
                                <th>Provinsi</th>
                                <th>Daerah</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lowongan as $item)
                                <tr>
                                    <td>{{ $item->nama ?? '-' }}</td>
                                    <td>{{ $item->perusahaan->nama ?? '-' }}</td>
                                    <td>{{ $item->bidang->nama ?? '-' }}</td>
                                    <td>{{ $item->perusahaan->provinsi ?? '-' }}</td>
                                    <td>{{ $item->perusahaan->daerah ?? '-' }}</td>
                                    <td class="text-center"> <button class="btn btn-sm btn-info btn-detail"
                                            onclick="modalAction('{{ url('/admin/lowongan/detail/' . $item->id_lowongan) }}')">
                                            Detail
                                        </button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center">
                    <p class="mt-4">Tidak ada lowongan tersedia</p>
                </div>
            @endif
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection
@push('css')
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');

                $('#myModal').find('#id_perusahaan').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#myModal'),
                    placeholder: $('#id_perusahaan').data('placeholder'),
                    width: '100%'
                });

                $('#myModal').find('#id_bidang').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#myModal'),
                    placeholder: $('#id_bidang').data('placeholder'),
                    width: '100%'
                });
            });
        }
    </script>
@endpush