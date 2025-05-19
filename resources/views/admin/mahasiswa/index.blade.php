@extends('layouts.tamplate')

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Mahasiswa</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahMahasiswa">
                <i class="bi bi-plus"></i> Tambah Mahasiswa
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
                        <col style="width: 100px;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Telepon</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mahasiswa as $item)
                            <tr>
                                <td>{{ $item->akun->id_user ?? '-' }}</td>
                                <td>{{ $item->nama ?? '-' }}</td>
                                <td>{{ $item->telepon ?? '-' }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->akun->status }}</td>
                                <td class="text-center"> <button class="btn btn-sm btn-info btn-detail"
                                        data-id="{{ $item->akun->id_akun }}">
                                        Detail
                                    </button></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada mahasiswa tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    @include('admin.mahasiswa.tambah')
@endsection

@push('scripts')
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
@endpush