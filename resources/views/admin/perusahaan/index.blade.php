@extends('layouts.tamplate')

@section('content')

    <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Perusahaan</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPerusahaan">
                <i class="bi bi-plus"></i> Tambah Perusahaan
            </button>
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
                        @forelse ($perusahaan as $item)  
                            <tr>
                                <td>{{ $item->nama ?? '-' }}</td>
                                <td>{{ $item->telepon ?? '-' }}</td>
                                <td>{{ $item->provinsi ?? '-' }}</td>
                                <td>{{ $item->daerah }}</td>
                                <td class="text-center"> <button class="btn btn-sm btn-info btn-detail" data-id="{{ $item->id_perusahaan }}">
                                        Detail
                                    </button></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada perusahaan tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    @include('admin.perusahaan.tambah')
@endsection

@push('scripts')
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
@endpush