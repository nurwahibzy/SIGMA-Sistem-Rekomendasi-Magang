<div class="card p-5 bg-primary border-0 mb-5">
    <div class="row align-items-center">
        <div class="col-md-4 d-flex justify-content-center">
            <dotlottie-player src="https://lottie.host/906be8dd-de3a-4b30-b538-b5985cc6d2bf/bzP4AQrrOO.lottie"
                background="transparent" speed="1" style="width: 70%; height: 70%;" loop autoplay>
            </dotlottie-player>
        </div>
        <div class="col-md-8">
            <h1 class="fw-bold mb-3" style="color: white;">
                Program Magang
            </h1>
            <h5 class="text-white">
                Magang adalah kesempatan bagi mahasiswa untuk memperoleh pengalaman kerja nyata di dunia industri
                sebelum lulus dari kampus. Melalui magang, mahasiswa dapat mengaplikasikan ilmu yang didapat di bangku
                kuliah ke dunia kerja serta menambah keterampilan profesional
            </h5>
        </div>
    </div>
</div>
<div class="d-flex justify-content-center gap-4 mb-5">
    <!-- Card Manfaat Magang -->
    <div class="card shadow-sm rounded-3 p-4 company-card-hover" style="min-width: 400px; max-width: 700px;">
        <h4 class="fw-bold text-primary mb-4">Manfaat Magang</h4>
        <p class="text-gray-600 mb-2">
            <i class="bi bi-check-circle-fill text-success me-2"></i>Pengalaman Kerja Nyata
        </p>
        <p class="text-gray-600 mb-2">
            <i class="bi bi-check-circle-fill text-success me-2"></i>Meningkatkan hard skills dan soft skills.
        </p>
        <p class="text-gray-600 mb-2">
            <i class="bi bi-check-circle-fill text-success me-2"></i>Memperluas jaringan profesional.
        </p>
        <p class="text-gray-600 mb-2">
            <i class="bi bi-check-circle-fill text-success me-2"></i>Menambah nilai plus di CV.
        </p>
        <p class="text-gray-600 mb-2">
            <i class="bi bi-check-circle-fill text-success me-2"></i>Membangun Portofolio
        </p>
        <p class="text-gray-600 mb-0">
            <i class="bi bi-check-circle-fill text-success me-2"></i>Membuka peluang kerja setelah lulus.
        </p>
    </div>

    <!-- Card Faktor Penentu Rekomendasi -->
    <div class="card shadow-sm rounded-3 p-4 company-card-hover" style="min-width: 400px; max-width: 700px;">
        <h4 class="fw-bold text-primary mb-4">Faktor Penentu Rekomendasi</h4>
        <p class="text-gray-600 mb-2">
            <i class="bi bi-check-circle-fill text-success me-2"></i>Keahlian
        </p>
        <p class="text-gray-600 mb-2">
            <i class="bi bi-check-circle-fill text-success me-2"></i>Preferensi Perusahaan
        </p>
        <p class="text-gray-600 mb-2">
            <i class="bi bi-check-circle-fill text-success me-2"></i>Preferensi Lokasi
        </p>
        <p class="text-gray-600 mb-2">
            <i class="bi bi-check-circle-fill text-success me-2"></i>Pengalaman
        </p>
        <p class="text-gray-600 mb-0">
            <i class="bi bi-check-circle-fill text-success me-2"></i>Dokumen
        </p>
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
<div class="d-flex flex-wrap gap-2">
    @foreach ($bidang as $item)
        <div class="chip chip-primary shadow-sm">
            <span class="chip-label">{{ $item->nama }}</span>
        </div>
    @endforeach
</div>
<script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
<style>
   /* Efek hover pada card */
.company-card-hover {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.company-card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1) !important;
}

/* Warna teks netral */
.text-gray-600 {
    color: #6c757d;
}

/* Sesuaikan jika perlu responsif */
@media (max-width: 768px) {
    .d-flex {
        flex-direction: column;
        align-items: center;
    }
}
.chip {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #ffffff;
    background-color:rgb(238, 238, 245); /* Indigo - Primary warna Mazer */
    border-radius: 50px;
    border: none;
    transition: all 0.3s ease;
    cursor: default;
    white-space: nowrap;
}

.chip:hover {
    background-color:rgb(69, 65, 117); /* Warna biru tua saat hover */
    transform: scale(1.05);
}

.chip-primary {
    color: #fff;
    background-color: #435ebe;
}
</style>