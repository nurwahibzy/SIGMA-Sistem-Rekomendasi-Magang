@extends('layouts.tamplate')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Perusahaan</h5>
            <button onclick="modalAction('{{ url('/admin/perusahaan/tambah') }}')" class="btn btn-primary"><i
                    class="bi bi-plus"></i>Tambah
                Perusahaan</button>
        </div>
        <div class="card-body">
            @if (count($perusahaan))
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
                                <th>Nama</th>
                                <th>Telepon</th>
                                <th>Provinsi</th>
                                <th>Daerah</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($perusahaan as $item)
                                <tr>
                                    <td>{{ $item->nama ?? '-' }}</td>
                                    <td>{{ $item->telepon ?? '-' }}</td>
                                    <td>{{ $item->provinsi ?? '-' }}</td>
                                    <td>{{ $item->daerah }}</td>
                                    <td class="text-center"> <button class="btn btn-sm btn-info btn-detail"
                                            onclick="modalAction('{{ url('/admin/perusahaan/detail/' . $item->id_perusahaan) }}')">
                                            Detail
                                        </button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            @else
                <div class="text-center">
                    <p class="mt-4">Tidak ada perusahaan tersedia</p>
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