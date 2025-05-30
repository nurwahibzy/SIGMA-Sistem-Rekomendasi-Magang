@extends('layouts.tamplate')

@section('content')
    <div class="card">
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
                        </colgroup>
                        <thead>
                            <tr>
                                <th>Perusahaan</th>
                                <th>Nama</th>
                                <th>Persyaratan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lowongan as $item)
                                <tr>
                                    <td>{{ $item->perusahaan->nama ?? '-' }}</td>
                                    <td>{{ $item->nama ?? '-' }}</td>
                                    <td>{{ $item->persyaratan ?? '-' }}</td>
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
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }
    </script>
@endpush