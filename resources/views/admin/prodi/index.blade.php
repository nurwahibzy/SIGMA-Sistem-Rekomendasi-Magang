@extends('layouts.tamplate')

@section('content')

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Lowongan Magang</h5>
            <button class="btn btn-primary mb-3">
                <i class="bi bi-plus"></i> Tambah Aktivitas
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="table1">
                    <colgroup>
                        <col style="width: 100px;">
                        <col style="width: 100px;">
                        <col style="width: 100px;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Jurusan</th>
                            <th>Prodi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($prodi as $item)  
                            <tr>
                                <td>{{ $item->nama_jurusan ?? '-' }}</td>
                                <td>{{ $item->nama_prodi ?? '-' }}</td>
                                <td> <button class="btn btn-sm btn-info btn-detail" data-id="{{ $item->id_prodi }}">
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
@endsection

@push('scripts')
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
@endpush