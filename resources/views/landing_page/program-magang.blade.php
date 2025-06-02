<div class="card p-5 bg-primary border-0 mb-5">
    <div class="row align-items-center">
        <div class="col-md-4 d-flex justify-content-center">
            <img src="{{ asset('template/assets/images/SIGMA1.png') }}" alt="Foto Profil"
                class="img-fluid rounded w-50 h-50" style="object-fit: cover;">
        </div>
        <div class="col-md-8">
            <h1 class="fw-bold text-light mb-3">
                Program Magang
            </h1>
            <h5 class="text-light">
                Magang adalah kesempatan bagi mahasiswa untuk memperoleh pengalaman kerja nyata di dunia industri
                sebelum lulus dari kampus. Melalui magang, mahasiswa dapat mengaplikasikan ilmu yang didapat di bangku
                kuliah ke dunia kerja serta menambah keterampilan profesional
            </h5>
        </div>
    </div>
</div>
<div class="d-flex justify-content-center">
    <div class="card px-5 py-3 border border-secondary mb-5 me-3 ms-5 w-100" style="border-width: 5px;">
        <div class="w-100">
            <h4 class="fw-bold text-primary mb-3">
                Manfaat Magang
            </h4>
            <p class="text-muted">
                <i class="bi bi-check-circle-fill text-success"></i> Pengalaman Kerja Nyata
            </p>
            <p class="text-muted">
                <i class="bi bi-check-circle-fill text-success"></i> Meningkatkan hard skills dan soft skills.
            </p>
            <p class="text-muted">
                <i class="bi bi-check-circle-fill text-success"></i> Memperluas jaringan profesional.
            </p>
            <p class="text-muted">
                <i class="bi bi-check-circle-fill text-success"></i> Menambah nilai plus di CV.
            </p>
            <p class="text-muted">
                <i class="bi bi-check-circle-fill text-success"></i> Membangun Portofolio
            </p>
            <p class="text-muted">
                <i class="bi bi-check-circle-fill text-success"></i> Membuka peluang kerja setelah lulus.
            </p>
        </div>
    </div>
    <div class="card px-5 py-3 border border-secondary mb-5 me-5 ms-3 w-100" style="border-width: 5px;">
        <div class="w-100">
            <h4 class="fw-bold text-primary mb-3">
                Faktor Penentu Rekomendasi
            </h4>
            <p class="text-muted">
                <i class="bi bi-check-circle-fill text-success"></i> Keahlian
            </p>
            <p class="text-muted">
                <i class="bi bi-check-circle-fill text-success"></i> Preferensi Perusahaan
            </p>
            <p class="text-muted">
                <i class="bi bi-check-circle-fill text-success"></i> Preferensi Lokasi
            </p>
            <p class="text-muted">
                <i class="bi bi-check-circle-fill text-success"></i> Pengalaman
            </p>
            <p class="text-muted">
                <i class="bi bi-check-circle-fill text-success"></i> Dokumen
            </p>
        </div>
    </div>
</div>
<div>
    <h4 class="fw-bold text-primary mb-3">
        Bidang Magang
    </h4>
    <p class="text-muted">
        Kami percaya bahwa setiap Anda pantas mendapatkan peluang untuk berkembang di bidang yang Anda sukai. Anda bisa
        menjelajahi berbagai program magang di berbagai bidang :
    </p>
</div>
<div class="d-flex flex-wrap justify-content-start">
    @foreach ($bidang as $item)
        <div class="card px-4 py-2 border border-secondary rounded-pill text-center me-3 mb-3">
            {{ $item->nama }}
        </div>
    @endforeach
</div>