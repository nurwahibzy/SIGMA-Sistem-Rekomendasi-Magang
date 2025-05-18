@extends('layouts.tamplate')

@section('content')
    <!-- <link rel="stylesheet"
        href="{{ asset('template/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/table-datatable-jquery.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app-dark.css') }}"> -->

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Lowongan Magang</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th>Nama Lowongan</th>
                            <th>Perusahaan</th>
                            <th>Bidang</th>
                            <th>Periode</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($magang as $item)
                            <tr>
                                <td>{{ $item->lowongan_magang->nama ?? '-' }}</td>
                                <td>{{ $item->lowongan_magang->perusahaan->nama ?? '-' }}</td>
                                <td>{{ $item->lowongan_magang->bidang->nama ?? '-' }}</td>
                                <td>{{ $item->tanggal_mulai->format('d M Y') }} - {{ $item->tanggal_selesai->format('d M Y') }}
                                </td>
                                <td>
                                    @if (now()->lt($item->tanggal_mulai))
                                        <button class="btn btn-sm btn-info btn-detail" data-id="{{ $item->id_periode }}">
                                            Detail
                                        </button>
                                    @else
                                        <span class="badge bg-secondary">Closed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada lowongan tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Modal -->
                <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detailModalLabel">Detail Lowongan Magang</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>Nama Lowongan</th>
                                            <td id="namaLowongan"></td>
                                        </tr>
                                        <tr>
                                            <th>Perusahaan</th>
                                            <td id="namaPerusahaan"></td>
                                        </tr>
                                        <tr>
                                            <th>Jenis Perusahaan</th>
                                            <td id="jenisPerusahaan"></td>
                                        </tr>
                                        <tr>
                                            <th>Bidang</th>
                                            <td id="bidang"></td>
                                        </tr>
                                        <tr>
                                            <th>Persyaratan</th>
                                            <td id="persyaratan"></td>
                                        </tr>
                                        <tr>
                                            <th>Deskripsi</th>
                                            <td id="deskripsi"></td>
                                        </tr>
                                        <tr>
                                            <th>Periode</th>
                                            <td id="periode"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="mt-3 d-flex justify-content-end gap-2">
                                    <form id="formLamar" method="POST" action="">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Lamar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- <script src="{{ asset('template/assets/extensions/jquery/jquery.min.js') }}"></script> -->
    <script src="{{ asset('template/assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('template/assets/static/js/pages/datatables.js') }}"></script>
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
    <!-- <script src="{{ asset('template/assets/compiled/js/app.js') }}"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->

    <script>
        $(document).on('click', '.btn-detail', function () {
            let idPeriode = $(this).data('id');
            let url = "{{ url('mahasiswa/periode/detail') }}/" + idPeriode;

            $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    if (response.length > 0) {
                        let magang = response[0];

                        $('#namaLowongan').text(magang.lowongan_magang.nama || '-');
                        $('#namaPerusahaan').text(magang.lowongan_magang.perusahaan.nama || '-');
                        $('#jenisPerusahaan').text(magang.lowongan_magang.perusahaan.jenis_perusahaan?.jenis || '-');
                        $('#bidang').text(magang.lowongan_magang.bidang.nama || '-');
                        $('#persyaratan').text(magang.lowongan_magang.persyaratan || '-');
                        $('#deskripsi').text(magang.lowongan_magang.deskripsi || '-');

                        let periodeText = '-';
                        if (magang.tanggal_mulai && magang.tanggal_selesai) {
                            let start = new Date(magang.tanggal_mulai).toLocaleDateString('en-GB', {
                                day: '2-digit', month: 'short', year: 'numeric'
                            });
                            let end = new Date(magang.tanggal_selesai).toLocaleDateString('en-GB', {
                                day: '2-digit', month: 'short', year: 'numeric'
                            });
                            periodeText = `${start} - ${end}`;
                        }
                        $('#periode').text(periodeText);

                        // atur routenya belum benar
                        $('#formLamar').attr('action', `/mahasiswa/lamar/${idPeriode}`);

                        $('#detailModal').modal('show');
                    } else {
                        alert('Data tidak ditemukan.');
                    }
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                    console.log('Status:', status);
                    console.log('Error:', error);
                    alert('Terjadi kesalahan saat mengambil data.');
                }
            });
        });
    </script>
@endpush