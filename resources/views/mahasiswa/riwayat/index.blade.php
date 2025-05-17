@extends('layouts.tamplate')

@section('content')
<div class="page-heading">
    <h3>Riwayat Magang</h3>
</div>

<div class="page-content">
    <section class="row" id="riwayatContainer">
        {{-- Data akan dimuat lewat Ajax --}}
    </section>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $.ajax({
            url: '{{ url("/mahasiswa/riwayat/data") }}',
            method: 'GET',
            success: function (data) {
                if (data.length === 0) {
                    $('#riwayatContainer').html(`
                        <div class="col-12">
                            <div class="alert alert-warning text-center">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                Anda belum memiliki riwayat magang.
                            </div>
                        </div>
                    `);
                    return;
                }

                let html = '';

                data.forEach((item) => {
                    const periode = item.periode_magang;
                    const lowongan = periode.lowongan_magang;
                    const perusahaan = lowongan.perusahaan;
                    const bidang = lowongan.bidang;
                    const jenis = perusahaan.jenis_perusahaan;

                    const mulai = new Date(periode.tanggal_mulai).toLocaleDateString('id-ID');
                    const selesai = new Date(periode.tanggal_selesai).toLocaleDateString('id-ID');

                    const status = item.status || 'Proses'; // Ganti jika field status ada
                    let badgeClass = 'bg-secondary';
                    if (status === 'Diterima') badgeClass = 'bg-success';
                    else if (status === 'Ditolak') badgeClass = 'bg-danger';

                    html += `
                        <div class="col-md-6 col-lg-4">
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar bg-light-primary me-3">
                                            <i class="bi bi-building fs-4"></i>
                                        </div>
                                        <div>
                                            <h5 class="card-title mb-0">${lowongan.nama}</h5>
                                            <small class="text-muted">${perusahaan.nama}</small>
                                        </div>
                                    </div>

                                    <p class="mb-1"><strong>Jenis:</strong> ${jenis.jenis}</p>
                                    <p class="mb-1"><strong>Bidang:</strong> ${bidang.nama}</p>
                                    <p class="mb-1"><strong>Periode:</strong> ${mulai} - ${selesai}</p>
                                    
                                    <div class="mt-3">
                                        <span class="badge ${badgeClass} text-white px-3 py-2">${status}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                $('#riwayatContainer').html(html);
            },
            error: function () {
                $('#riwayatContainer').html(`
                    <div class="col-12">
                        <div class="alert alert-danger text-center">
                            <i class="bi bi-x-circle-fill me-2"></i>
                            Gagal memuat data riwayat.
                        </div>
                    </div>
                `);
            }
        });
    });
</script>
@endpush
