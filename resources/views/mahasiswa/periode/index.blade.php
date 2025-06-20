@extends('layouts.tamplate')
@section('content')
    <div class="row">
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card shadow">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-4 d-flex justify-content-start">
                            <div class="stats-icon blue mb-2">
                                <i class="bi-building"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h6 class="text-muted font-semibold">Perusahaan</h6>
                            <h6 class="font-extrabold mb-0" id="jumlahPerusahaan"></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card shadow">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-4 d-flex justify-content-start">
                            <div class="stats-icon blue mb-2">
                                <i class="bi-briefcase"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h6 class="text-muted font-semibold">Jenis Perusahaan</h6>
                            <h6 class="font-extrabold mb-0" id="jumlahJenisPerusahaan"></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card shadow">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-4 d-flex justify-content-start">
                            <div class="stats-icon green mb-2">
                                <i class="bi-gear"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h6 class="text-muted font-semibold">Bidang</h6>
                            <h6 class="font-extrabold mb-0" id="jumlahBidang"></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (!empty($rekomendasi))
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 id="judul-rekomendasi">Top 3 Rekomendasi Lowongan Magang</h4>
            <div>
                <button id="btn-top-10" class="btn btn-info me-3">Tampilkan Top 10</button>
                <button id="btn-top-3" class="btn btn-info me-3 d-none">Tampilkan Top 3</button>
                <button id="btn-perhitungan" class="btn btn-warning">Detail Perhitungan</button>
            </div>
        </div>

        <div class="row g-4 mb-4 overflow-auto flex-nowrap">
            @foreach ($rekomendasi as $i => $item)
                @php
                    $isHidden = $i >= 3;
                @endphp

                <div class="col-md-6 col-xl-4 {{ $isHidden ? 'd-none extra-rekomendasi' : '' }}">
                    <div class="card h-100 shadow border-0 position-relative">
                        <div class="position-relative">
                            <img id="previewLogo"
                                src="{{ Storage::exists('public/profil/perusahaan/' . $item->lowongan_magang->perusahaan->foto_path)
                                    ? asset('storage/profil/perusahaan/' . $item->lowongan_magang->perusahaan->foto_path)
                                    : asset('template/assets/images/mhs.jpeg') }}"
                                alt="Logo Perusahaan" class="card-img-top"
                                style="height: 160px; object-fit: cover; cursor: pointer;"
                                onclick="showImagePopupLogo(this.src)" />

                            <div
                                class="position-absolute top-0 start-0 bg-primary text-white px-3 py-1 rounded-bottom-end fw-bold">
                                #{{ $i + 1 }}
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column justify-content-between border-top">
                            <ul class="list-unstyled mb-1">
                                <li><strong>Perusahaan:</strong> {{ $item->lowongan_magang->perusahaan->nama }}</li>
                                <li><strong>Jenis:</strong>
                                    {{ $item->lowongan_magang->perusahaan->jenis_perusahaan->jenis }}</li>
                                <li><strong>Bidang:</strong> {{ $item->lowongan_magang->bidang->nama }}</li>
                                <li><strong>Periode:</strong> {{ $item->tanggal_mulai->format('d M Y') }} -
                                    {{ $item->tanggal_selesai->format('d M Y') }}</li>
                            </ul>
                            <div class="text-end">
                                <button class="btn btn-info btn-sm"
                                    onclick="modalAction('{{ url('/mahasiswa/periode/detail/' . $item->id_periode) }}')">
                                    Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div
            class="alert alert-warning d-flex justify-content-between align-items-center flex-wrap gap-2 p-4 rounded shadow-sm">
            <div class="flex-grow-1">
                Maaf, saat ini belum ada rekomendasi lowongan magang yang sesuai dengan preferensi Anda.
            </div>
            <div>
                <button id="btn-profil" class="btn btn-primary">
                    <i class="bi bi-person-fill"></i> Ubah Preferensi
                </button>
            </div>
        </div>
    @endif
    <br>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Semua Lowongan Magang yang tersedia di JTI</h4>
    </div>
    <div class="card shadow">
        <div class="card-body">
            <form id="filterForm" class="row g-3 mb-4">
                <div class="col-md-2">
                    <label for="tanggal_mulai_filter" class="form-label">Tanggal Mulai</label>
                    <input type="date" id="tanggal_mulai_filter" name="tanggal_mulai_filter" class="form-control">
                </div>
                <div class="col-md-2">
                    <label for="tanggal_selesai_filter" class="form-label">Tanggal Selesai</label>
                    <input type="date" id="tanggal_selesai_filter" name="tanggal_selesai_filter" class="form-control">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" id="btnReset" class="btn btn-secondary me-2">
                        <i class="bi bi-x-circle"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered" id="tableID">
                    <thead>
                        <tr>
                            <th>Nama Lowongan</th>
                            <th>Perusahaan</th>
                            <th>Jenis Perusahaan</th>
                            <th>Bidang</th>
                            <th>Periode</th>
                            <th>Daerah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>

    <div id="image-popup-logo"
        style="
                display: none;
                position: fixed;
                top: 0; left: 0;
                width: 100vw; height: 100vh;
                background-color: rgba(0,0,0,0.8);
                z-index: 1050;
                justify-content: center;
                align-items: center;
            ">
        <span id="close-popup"
            style="
                    position: absolute;
                    top: 20px; right: 30px;
                    font-size: 30px;
                    color: white;
                    cursor: pointer;
                    z-index: 1060;
                ">&times;</span>
        <img id="popup-img-logo" src="" alt="Full Image"
            style="
                    max-width: 90vw;
                    max-height: 90vh;
                    border-radius: 10px;
                    box-shadow: 0 0 10px #000;
                    object-fit: contain;
                ">
    </div>
    <script>
        function showImagePopupLogo(src) {
            const popup = document.getElementById('image-popup-logo');
            const popupImg = document.getElementById('popup-img-logo');
            popupImg.src = src;
            popup.style.display = 'flex';
        }

        document.getElementById('close-popup').addEventListener('click', function() {
            document.getElementById('image-popup-logo').style.display = 'none';
        });

        document.getElementById('image-popup-logo').addEventListener('click', function(e) {
            if (e.target.id === 'image-popup-logo') {
                document.getElementById('image-popup-logo').style.display = 'none';
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape") {
                document.getElementById('image-popup-logo').style.display = 'none';
            }
        });
    </script>
@endsection

@push('js')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tanggal_mulai_filter').on('change', function() {
                const tanggalMulai = new Date($(this).val());
                tanggalMulai.setDate(tanggalMulai.getDate() + 1);
                const minTanggalSelesai = tanggalMulai.toISOString().split('T')[0];
                $('#tanggal_selesai_filter').attr('min', minTanggalSelesai);
            });

            $('#tanggal_selesai_filter').on('change', function() {
                const tanggalSelesai = new Date($(this).val());
                tanggalSelesai.setDate(tanggalSelesai.getDate() - 1);
                const maxTanggalMulai = tanggalSelesai.toISOString().split('T')[0];
                $('#tanggal_mulai_filter').attr('max', maxTanggalMulai);
            });

            let table = $('#tableID').DataTable({
                ajax: {
                    url: "{{ url('/mahasiswa/periode/data') }}",
                    data: function(d) {
                        d.tanggal_mulai_filter = $('#tanggal_mulai_filter').val();
                        d.tanggal_selesai_filter = $('#tanggal_selesai_filter').val();
                    },
                    dataSrc: function(json) {
                        $('#jumlahPerusahaan').text(json.jumlahPerusahaan);
                        $('#jumlahJenisPerusahaan').text(json.jumlahJenisPerusahaan);
                        $('#jumlahBidang').text(json.jumlahBidang);
                        return json.data;
                    }
                },
                columns: [{
                        data: 'lowongan_magang.nama',
                        defaultContent: '-'
                    },
                    {
                        data: 'lowongan_magang.perusahaan.nama',
                        defaultContent: '-'
                    },
                    {
                        data: 'lowongan_magang.perusahaan.jenis_perusahaan.jenis',
                        defaultContent: '-'
                    },
                    {
                        data: 'lowongan_magang.bidang.nama',
                        defaultContent: '-'
                    },
                    {
                        data: null,
                        render: function(data) {
                            const mulai = new Date(data.tanggal_mulai).toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric'
                            });
                            const selesai = new Date(data.tanggal_selesai).toLocaleDateString(
                                'id-ID', {
                                    day: '2-digit',
                                    month: 'short',
                                    year: 'numeric'
                                });
                            return `${mulai} - ${selesai}`;
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            return `${data.lowongan_magang.perusahaan.provinsi ?? '-'} - ${data.lowongan_magang.perusahaan.daerah ?? '-'}`;
                        }
                    },
                    {
                        data: 'id_periode',
                        render: function(id) {
                            return `<button class="btn btn-sm btn-info" onclick="modalAction('{{ url('/mahasiswa/periode/detail') }}/${id}')">Detail</button>`;
                        }
                    }
                ]
            });

            $('#btnReset').on('click', function() {
                $('#tanggal_mulai_filter').val('');
                $('#tanggal_selesai_filter').val('');

                $('#tanggal_selesai_filter').removeAttr('min');
                $('#tanggal_mulai_filter').removeAttr('max');

                table.ajax.reload();
            });

            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });

            $('#btn-perhitungan').on('click', function(e) {
                window.location.href = "{{ url('/mahasiswa/periode/rekomendasi/perhitungan') }}";
            });
            $('#btn-profil').on('click', function(e) {
                window.location.href = "{{ url('/mahasiswa/profil/edit') }}";
            });

            $('#btn-top-10').on('click', function() {
                $('.extra-rekomendasi').removeClass('d-none');
                $('#btn-top-10').addClass('d-none');
                $('#btn-top-3').removeClass('d-none');

                $('#judul-rekomendasi').text('Top 10 Rekomendasi Lowongan Magang');
            });

            $('#btn-top-3').on('click', function() {
                $('.extra-rekomendasi').addClass('d-none');
                $('#btn-top-3').addClass('d-none');
                $('#btn-top-10').removeClass('d-none');

                $('#judul-rekomendasi').text('Top 3 Rekomendasi Lowongan Magang');
            });


        });

        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
    </script>
@endpush
