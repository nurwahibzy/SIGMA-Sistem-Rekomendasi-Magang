@extends('layouts.tamplate')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
           <h5 class="card-title mb-0">Dosen Pembimbing : {{ $magang->dosen->nama }}</h5>
            @if ($hasActivityToday == 0)
            <button onclick="modalAction('{{ url('/mahasiswa/aktivitas/' . $id_magang. '/tambah') }}')" class="btn btn-primary">
                <i class="bi bi-plus"></i> Tambah Aktivitas
            </button>
            @endif
        </div>
        <div class="card-body">

            @if (count($aktivitas))
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
                                <th>No</th>
                                <th>Deskripsi</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($aktivitas as $i => $item)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $item->keterangan }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-info btn-detail"
                                            onclick="modalAction('{{ url('/mahasiswa/aktivitas/'. $id_magang. '/detail/' . $item->id_aktivitas) }}')">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center">
                    <p class="mt-4">Belum ada aktivitas tersedia</p>
                </div>
            @endif
        </div>

        <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
            data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    </div>
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