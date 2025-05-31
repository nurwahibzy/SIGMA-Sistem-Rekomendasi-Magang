<!-- RIGHT PANEL -->
<div class="col-md-8">

    <!-- Info Box -->
    <div class="card p-5 bg-primary bg-opacity-10 border-0 mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="fw-bold text-primary mb-3">
                    Tarik perhatian rekruter dengan <br>
                    <span class="text-light">Profil Anda</span>
                </h4>
                <p class="text-muted">
                    Buat profil dan bantu perusahaan mengenal Anda lebih mudah.
                    Dapatkan rekomendasi magang yang sesuai pengalaman dan keahlian Anda.
                </p>
            </div>
            <div class="col-md-4 d-flex justify-content-center">
                <img src="{{ asset('template/assets/images/magang.jpg') }}" alt="Foto Profil"
                    class="rounded-circle shadow" style="width: 120px; height: 120px; object-fit: cover;">
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <ul class="nav nav-tabs" id="detailTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#keahlian" role="tab">Keahlian</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#perusahaan" role="tab">Preferensi Perusahaan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#lokasi" role="tab">Preferensi Lokasi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#pengalaman" role="tab">Pengalaman</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#dokumen" role="tab">Dokumen</a>
            </li>
        </ul>

        <a href="{{ url('mahasiswa/profil/edit') }}" class="btn-edit-section btn btn-outline-primary">
            <i class="bi bi-pencil-square"></i> Edit
        </a>
    </div>


    <div class="tab-content" id="detailTabContent">
        <div class="tab-pane fade show active" id="keahlian" role="tabpanel">
            <!-- KEAHLIAN SECTION -->
            <div class="section-wrapper mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-bold mb-0">Keahlian</h5>
                </div>

                <p class="text-muted mb-3">Keahlian yang dimiliki beserta skala prioritasnya</p>

                @if (count($keahlian))
                    @foreach ($keahlian as $item)
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="d-flex mb-3">
                                    <div class="d-inline-block me-2 fw-bold">
                                        <h5>{{ $item->prioritas }}</h5>
                                    </div>
                                    <div class="d-inline-block fw-bold">
                                        <h5>{{ $item->bidang->nama }}</h5>
                                    </div>
                                </div>

                                <div>
                                    <div class="px-3 py-1 rounded d-inline-block">
                                        {{ $item->keahlian }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="tab-pane fade" id="perusahaan" role="tabpanel">
            <!-- PREFERENSI PERUSAHAAN SECTION -->
            <div class="section-wrapper mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-bold mb-0">Preferensi Perusahaan</h5>
                </div>

                <p class="text-muted mb-3">
                    Jenis perusahaan.
                </p>
                @if (count($preferensi_perusahaan))
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @foreach ($preferensi_perusahaan as $item)
                                <div class="bg-primary bg-opacity-10 px-3 py-1 rounded d-inline-block">
                                    {{ $item->jenis_perusahaan->jenis }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
        <div class="tab-pane fade" id="lokasi" role="tabpanel">
            <!-- PREFERENSI LOKASI SECTION -->
            <div class="section-wrapper mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-bold mb-0">Preferensi Lokasi</h5>
                </div>

                <p class="text-muted mb-3">
                    Preferensi lokasi magang.
                </p>
                @if (!empty($preferensi_lokasi))
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <p class="mb-1 text-muted">Provinsi</p>
                                    <div class="bg-primary bg-opacity-10 text-body px-3 py-1 rounded d-inline-block">
                                        {{ $preferensi_lokasi->provinsi}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted">Daerah</p>
                                    <div class="bg-primary bg-opacity-10 text-body px-3 py-1 rounded d-inline-block">
                                        {{ $preferensi_lokasi->daerah}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="tab-pane fade" id="pengalaman" role="tabpanel">
            <!-- PENGALAMAN SECTION -->
            <div class="section-wrapper mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-bold mb-0">Pengalaman</h5>
                </div>

                <p class="text-muted mb-3">
                    Pengalaman yang pernah dijalani.
                </p>
                @if (count($pengalaman))
                    <fieldset class="form-section" id="section-pengalaman" disabled>
                        <div id="pengalaman-container">
                            @foreach ($pengalaman as $item)
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div>
                                            <div class="px-3 py-1 rounded d-inline-block">
                                                {{ $item->deskripsi }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </fieldset>
                @endif
            </div>
        </div>
        <div class="tab-pane fade" id="dokumen" role="tabpanel">
            <!-- CV SECTION -->
            <div class="section-wrapper mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-bold mb-0">Dokumen</h5>
                </div>
                @if (count($dokumen))
                    <div id="dokumen-container">
                        @foreach ($dokumen as $item)
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="bg-primary bg-opacity-10 px-3 py-1 rounded d-inline-block  mb-4">
                                        {{  $item->nama }}
                                    </div>
                                    <div>
                                        <embed src="{{ url('storage/dokumen/' . $item->file_path) }}" type="application/pdf"
                                            width="100%" height="1000px" />
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>