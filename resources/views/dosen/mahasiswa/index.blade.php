@extends('layouts.tamplate')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Daftar Mahasiswa Bimbingan & Detail Magang</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Program Studi</th>
                            <th>Periode Magang</th>
                            <th>Perusahaan</th>
                            <th>Status</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($magang as $item)
                            <tr>
                                {{-- <td>{{ $item->mahasiswa->akun->id_user ?? '-' }}</td>
                                <td>{{ $item->mahasiswa->nama ?? '-' }}</td>
                                <td>{{ $item->mahasiswa->prodi->nama_prodi ?? '-' }}</td>
                                <td>{{ $item->periode_magang->tanggal_mulai->format('d M Y') }} - {{ $item->periode_magang->tanggal_selesai->format('d M Y') }}
                                </td>
                                <td>{{ $item->perusahaan->nama ?? '-' }}</td>
                                <td>{{ $item->status }}</td>
                                <td class="text-center"> <button class="btn btn-sm btn-info btn-detail"
                                        onclick="modalAction('{{ url('/admin/mahasiswa/detail/' . $item->id_magang) }}')">
                                        Detail
                                    </button></td>
                            </tr> --}}
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada mahasiswa bimbingan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection