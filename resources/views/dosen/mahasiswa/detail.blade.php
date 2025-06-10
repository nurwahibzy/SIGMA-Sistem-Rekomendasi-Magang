<div id="modal-master" class="modal-dialog modal-xl" role="document">
    <div class="modal-content shadow-lg rounded">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title text-light">
                Detail Magang - {{ $magang->mahasiswa->nama }}
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="container-fluid">
                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs mb-4" id="detailTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#info-magang">
                            <i class="bi bi-briefcase me-1"></i>Info Magang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#profil-mahasiswa">
                            <i class="bi bi-person me-1"></i>Profil Mahasiswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#keahlian">
                            <i class="bi bi-gear me-1"></i>Keahlian
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#pengalaman">
                            <i class="bi bi-journal me-1"></i>Pengalaman
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#lowongan">
                            <i class="bi bi-building me-1"></i>Lowongan
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="detailTabContent">
                    <!-- Info Magang Tab -->
                    <div class="tab-pane fade show active" id="info-magang" role="tabpanel">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi Magang</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td class="fw-bold" style="width: 40%;">Status:</td>
                                                <td>
                                                    <span class="badge 
                                                        @if($magang->status == 'diterima') bg-success
                                                        @elseif($magang->status == 'ditolak') bg-danger
                                                        @elseif($magang->status == 'proses') bg-warning
                                                        @elseif($magang->status == 'lulus') bg-primary
                                                        @else bg-secondary
                                                        @endif">
                                                        {{ ucfirst($magang->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Tanggal Pengajuan:</td>
                                                <td>{{ \Carbon\Carbon::parse($magang->tanggal_pengajuan)->format('d M Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Periode Magang:</td>
                                                <td>{{ $magang->periode_magang->nama ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td class="fw-bold" style="width: 40%;">Tanggal Mulai:</td>
                                                <td>{{ \Carbon\Carbon::parse($magang->periode_magang->tanggal_mulai)->format('d M Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Tanggal Selesai:</td>
                                                <td>{{ \Carbon\Carbon::parse($magang->periode_magang->tanggal_selesai)->format('d M Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Dosen Pembimbing:</td>
                                                <td>{{ $magang->dosen->nama ?? 'Belum ditentukan' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profil Mahasiswa Tab -->
                    <div class="tab-pane fade" id="profil-mahasiswa" role="tabpanel">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-person-circle me-2"></i>Profil Mahasiswa</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td class="fw-bold" style="width: 40%;">Nama:</td>
                                                <td>{{ $magang->mahasiswa->nama }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Email:</td>
                                                <td>{{ $magang->mahasiswa->email }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Telepon:</td>
                                                <td>{{ $magang->mahasiswa->telepon }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Alamat:</td>
                                                <td>{{ $magang->mahasiswa->alamat }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td class="fw-bold" style="width: 40%;">Tanggal Lahir:</td>
                                                <td>{{ \Carbon\Carbon::parse($magang->mahasiswa->tanggal_lahir)->format('d M Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Status Akun:</td>
                                                <td>
                                                    <span class="badge {{ $magang->mahasiswa->akun->status == 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ ucfirst($magang->mahasiswa->akun->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Keahlian Tab -->
                                    <div class="tab-pane fade" id="keahlian" role="tabpanel">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header">
                                                <h6 class="mb-0"><i class="bi bi-tools me-2"></i>Keahlian Mahasiswa</h6>
                                            </div>
                                            <div class="card-body">
                                                @if($magang->mahasiswa->keahlian_mahasiswa->count() > 0)
                                                    <ul class="list-group">
                                                        @foreach($magang->mahasiswa->keahlian_mahasiswa->sortBy('prioritas') as $keahlian)
                                                            <li class="list-group-item">
                                                                <strong>Prioritas {{ $keahlian->prioritas }}:</strong> {{ $keahlian->keahlian }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <div class="text-center text-muted py-4">
                                                        <i class="bi bi-info-circle fs-1"></i>
                                                        <p class="mt-2">Belum ada keahlian yang ditambahkan</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pengalaman Tab -->
                                    <div class="tab-pane fade" id="pengalaman" role="tabpanel">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header">
                                                <h6 class="mb-0"><i class="bi bi-journal-text me-2"></i>Pengalaman Mahasiswa</h6>
                                            </div>
                                            <div class="card-body">
                                                @if($magang->mahasiswa->pengalaman->count() > 0)
                                                    <ul class="list-group">
                                                        @foreach($magang->mahasiswa->pengalaman as $pengalaman)
                                                            <li class="list-group-item">{{ $pengalaman->deskripsi }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <div class="text-center text-muted py-4">
                                                        <i class="bi bi-info-circle fs-1"></i>
                                                        <p class="mt-2">Belum ada pengalaman yang ditambahkan</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Lowongan Tab -->
                                    <div class="tab-pane fade" id="lowongan" role="tabpanel">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header">
                                                <h6 class="mb-0"><i class="bi bi-building me-2"></i>Informasi Lowongan</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <td class="fw-bold" style="width: 40%;">Nama Lowongan:</td>
                                                                <td>{{ $magang->periode_magang->lowongan_magang->nama }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-bold">Perusahaan:</td>
                                                                <td>{{ $magang->periode_magang->lowongan_magang->perusahaan->nama ?? '-' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-bold">Jenis Perusahaan:</td>
                                                                <td>{{ $magang->periode_magang->lowongan_magang->perusahaan->jenis_perusahaan->jenis ?? '-' }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <td class="fw-bold" style="width: 40%;">Bidang:</td>
                                                                <td>{{ $magang->periode_magang->lowongan_magang->bidang->nama ?? '-' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-bold">Lokasi:</td>
                                                                <td>{{ $magang->periode_magang->lowongan_magang->perusahaan->provinsi ?? '-' }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <div class="border-top pt-3">
                                                            <strong>Deskripsi:</strong>
                                                            <p class="mt-2">{{ $magang->periode_magang->lowongan_magang->deskripsi }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="border-top pt-3">
                                                            <strong>Persyaratan:</strong>
                                                            <div class="mt-2">
                                                                {{-- {!! nl2br(e($magang->periode_magang->lowongan_magang->persyaratan)) !!} --}}
                                                                {!! htmlspecialchars_decode($magang->periode_magang->lowongan_magang->persyaratan) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    </div>
</div>

