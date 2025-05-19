@extends('layouts.tamplate')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Program Studi</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahProdi">
                <i class="bi bi-plus"></i> Tambah Prodi
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th>Jurusan</th>
                            <th>Prodi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($prodi as $item)
                            <tr>
                                <td>{{ $item->nama_jurusan ?? '-' }}</td>
                                <td>{{ $item->nama_prodi ?? '-' }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info btn-detail" data-id="{{ $item->id_prodi }}">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada program studi tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('admin.prodi.tambah')
@endsection

@push('scripts')
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
@endpush
