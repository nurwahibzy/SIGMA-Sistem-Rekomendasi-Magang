@extends('layouts.tamplate')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <ul class="nav nav-tabs w-100" id="detailTab" role="tablist">
            <li class="nav-item flex-fill text-center">
                <a class="nav-link active" data-bs-toggle="tab" href="#aktivitas" role="tab">Aktivitas</a>
            </li>
            <li class="nav-item flex-fill text-center">
                <a class="nav-link" data-bs-toggle="tab" href="#evaluasi" role="tab">Evaluasi</a>
            </li>
        </ul>
    </div>
    <div class="tab-content" id="detailTabContent">
        <div class="tab-pane fade show active" id="aktivitas" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    @if (count($aktivitas))
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
                                        <th>No</th>
                                        <th>Deskripsi</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($aktivitas as $i => $item)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $item->keterangan }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                            </td>
                                            <td class="text-center"> <button class="btn btn-sm btn-info btn-detail"
                                                    onclick="modalAction('{{ url('/mahasiswa/riwayat/aktivitas/' . $item->id_magang . '/detail/' . $item->id_aktivitas) }}')">
                                                    Detail
                                                </button></td>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center">
                            <p class="mt-4">Tidak ada aktivitas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="evaluasi" role="tabpanel">
    @if (empty($evaluasi))
        <div class="alert alert-warning text-center">
            Belum ada evaluasi dari Dosen.
        </div>
    @else
        <div class="card-body d-flex justify-content-center mt-4">
            <div class="bg-primary bg-opacity-10 px-3 py-1 rounded text-center">
                {{ $evaluasi->feedback }}
            </div>
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