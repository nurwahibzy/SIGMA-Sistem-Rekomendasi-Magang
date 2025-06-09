@extends('layouts.tamplate')

@section('content')
    <div class="row">
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2 d-flex align-items-center justify-content-center">
                                <img src="{{ asset('template/assets/compiled/jpg/1.jpg') }}" alt="Profile Icon"
                                    class="img-fluid rounded">
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Mahasiswa</h6>
                            <h6 class="font-extrabold mb-0">{{ $jumlah_dibimbing ?? '0' }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon blue mb-2">
                                <i class="iconly-boldProfile"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Diterima</h6>
                            <h6 class="font-extrabold mb-0">{{ $jumlah_diterima ?? '0' }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon green mb-2">
                                <i class="iconly-boldAdd-User"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Lulus</h6>
                            <h6 class="font-extrabold mb-0">{{ $jumlah_lulus ?? '0' }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Daftar Mahasiswa Bimbingan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @if ($magang->isEmpty())
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-circle"></i> Tidak ada mahasiswa bimbingan.
                    </div>
                @else
                    <table class="table" id="table1">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Periode Magang</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Status</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($magang as $item)
                                <tr>
                                    <td>{{ $item->mahasiswa->nama ?? '-' }}</td>
                                    <td>
                                        @if($item->periode_magang)
                                            {{ $item->periode_magang->tanggal_mulai->format('d M Y') }} - 
                                            {{ $item->periode_magang->tanggal_selesai->format('d M Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $item->tanggal_pengajuan ? \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d M Y') : '-' }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($item->status == 'diterima') bg-success
                                            @elseif($item->status == 'lulus') bg-primary
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($item->status ?? '-') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-info btn-detail"
                                                data-url="{{ url('/dosen/riwayat/detail/' . $item->id_magang) }}"
                                                onclick="modalAction(this.dataset.url)">Detail
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div id="modal-content">
            <!-- Content will be loaded here -->
        </div>
    </div>
@endsection

@push('css')
    <style>
        .btn-detail:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .modal-xl {
            max-width: 90%;
        }
        
        @media (max-width: 768px) {
            .modal-xl {
                max-width: 95%;
                margin: 10px;
            }
        }
    </style>
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            if (!url) {
                console.error('URL tidak ditemukan');
                alert('Terjadi kesalahan: URL tidak valid');
                return;
            }

            // Show loading
            $('#modal-content').html(`
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-body text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3">Memuat data...</p>
                        </div>
                    </div>
                </div>
            `);
            
            // Show modal
            $('#myModal').modal('show');

            // Load content via AJAX
            $.ajax({
                url: url,
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#modal-content').html(response);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    console.error('Status:', status);
                    console.error('Response:', xhr.responseText);
                    
                    let errorMessage = 'Terjadi kesalahan saat memuat data.';
                    
                    if (xhr.status === 404) {
                        errorMessage = 'Data tidak ditemukan.';
                    } else if (xhr.status === 403) {
                        errorMessage = 'Anda tidak memiliki akses untuk melihat data ini.';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Terjadi kesalahan server.';
                    }
                    
                    $('#modal-content').html(`
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">
                                        <i class="bi bi-exclamation-triangle me-2"></i>Error
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-center py-4">
                                    <i class="bi bi-exclamation-circle text-danger" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3 text-danger">Oops! Terjadi Kesalahan</h5>
                                    <p class="text-muted">${errorMessage}</p>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    `);
                }
            });
        }

        // Close modal when clicking outside or pressing escape
        $(document).ready(function() {
            $('#myModal').on('hidden.bs.modal', function () {
                $('#modal-content').empty();
            });
        });
    </script>
@endpush