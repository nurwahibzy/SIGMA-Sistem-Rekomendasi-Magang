@extends('layouts.tamplate')

@section('content')

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Lowongan Magang</h5>
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
                        <col style="width: 100px;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Dosen</th>
                            <th>Perusahaan</th>
                            <th>Pengajuan</th>
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
                                <td>{{ $item->tanggal_pengajuan ?? '-' }}</td>
                                <td>{{ $item->status ?? '-' }}</td>
                                <td> <button class="btn btn-sm btn-info btn-detail" data-id="{{ $item->id_magang }}">
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
@endsection

@push('scripts')
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
@endpush