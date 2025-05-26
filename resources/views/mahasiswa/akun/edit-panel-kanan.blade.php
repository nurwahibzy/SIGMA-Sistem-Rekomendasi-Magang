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

        <a href="{{ url('mahasiswa/profil/') }}" class="btn-edit-section btn btn-outline-danger">
            <i class="bi bi-box-arrow-left"></i> Kembali
        </a>

    </div>


    <div class="tab-content" id="detailTabContent">
        <div class="tab-pane fade show active" id="keahlian" role="tabpanel">
            @include('mahasiswa.keahlian.index')
        </div>
        <div class="tab-pane fade" id="perusahaan" role="tabpanel">
            @include('mahasiswa.preferensi-perusahaan.index')
        </div>
        <div class="tab-pane fade" id="lokasi" role="tabpanel">
            @include('mahasiswa.preferensi-lokasi.index')
        </div>
        <div class="tab-pane fade" id="pengalaman" role="tabpanel">
            @include('mahasiswa.pengalaman.index')
        </div>
        <div class="tab-pane fade" id="dokumen" role="tabpanel">
            @include('mahasiswa.dokumen.index')
        </div>
    </div>
</div>