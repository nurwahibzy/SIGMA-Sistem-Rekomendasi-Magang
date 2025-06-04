<!-- Banner Program Magang -->
<div class="card p-5 bg-primary border-0 mb-5">
    <div class="row align-items-center">
        <div class="col-md-4 d-flex justify-content-center">
            <dotlottie-player src="https://lottie.host/906be8dd-de3a-4b30-b538-b5985cc6d2bf/bzP4AQrrOO.lottie" 
                background="transparent" speed="1" style="width: 70%; height: 70%;" loop autoplay>
            </dotlottie-player>
        </div>
        <div class="col-md-8">
            <h1 class="fw-bold mb-3 text-white">
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

<!-- Manfaat & Faktor Rekomendasi -->
<div class="container mb-5">
    <div class="d-flex justify-content-center gap-4 flex-wrap">
        <!-- Card Manfaat Magang -->
        <div class="card shadow-sm rounded-3 p-4 company-card-hover" style="min-width: 300px; max-width: 500px;">
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
        <div class="card shadow-sm rounded-3 p-4 company-card-hover" style="min-width: 300px; max-width: 500px;">
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
</div>

<!-- Bidang Magang -->
<div class="container-fluid px-0">
    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold text-primary mb-3">
                Bidang Magang
            </h4>
            <p class="text-muted mb-3">
                Kami percaya bahwa setiap Anda pantas mendapatkan peluang untuk berkembang di bidang yang Anda sukai. Anda bisa
                menjelajahi berbagai program magang di berbagai bidang :
            </p>
            <div class="d-flex flex-wrap gap-2 mb-4 px-3">
                @foreach ($bidang as $item)
                    <div class="chip chip-primary shadow-sm">
                        <span class="chip-label">{{ $item->nama }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Tips Sukses Selama Masa Magang -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0">Tips Sukses Selama Masa Magang !</h4>
                </div>
                <div class="card-body">
                    <div class="accordion accordion-flush" id="accordionFlushExample">

                        <!-- Accordion Item 1 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseOne" aria-expanded="false"
                                    aria-controls="flush-collapseOne">
                                    #1 Persiapkan diri sebelum memulai magang
                                </button>
                            </h2>
                            <div id="flush-collapseOne" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    Luangkan waktu untuk mempelajari perusahaan tempat kamu akan magang dan posisi yang akan kamu emban. Dengan persiapan yang matang, kamu akan lebih percaya diri dan siap menghadapi lingkungan kerja baru.
                                </div>
                            </div>
                        </div>

                        <!-- Accordion Item 2 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseTwo" aria-expanded="false"
                                    aria-controls="flush-collapseTwo">
                                    #2 Jadilah proaktif dan memiliki inisiatif tinggi
                                </button>
                            </h2>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    Selama masa magang, jangan hanya menunggu instruksi dari atasan. Tunjukkan bahwa kamu punya semangat belajar yang tinggi dengan menawarkan bantuan, bertanya apakah ada pekerjaan tambahan, atau yang lain. Sikap proaktif ini bisa membuat kesan yang sangat baik.
                                </div>
                            </div>
                        </div>

                        <!-- Accordion Item 3 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseThree" aria-expanded="false"
                                    aria-controls="flush-collapseThree">
                                    #3 Berperilaku profesional setiap saat
                                </button>
                            </h2>
                            <div id="flush-collapseThree" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    Profesionalisme adalah kunci selama magang. Datang tepat waktu, berpakaian rapi sesuai ketentuan perusahaan, dan tunjukkan sikap hormat kepada rekan kerja serta atasan.
                                </div>
                            </div>
                        </div>

                        <!-- Accordion Item 4 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseFour" aria-expanded="false"
                                    aria-controls="flush-collapseFour">
                                    #4 Bangun relasi yang positif dan saling mendukung
                                </button>
                            </h2>
                            <div id="flush-collapseFour" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    Jadilah mudah diajak kerja sama dan ramah pada semua orang di tempat magang. Hubungan yang baik dengan rekan kerja dan mentor bisa menjadi modal penting untuk mendapatkan rekomendasi kerja atau penawaran magang lanjutan di masa depan.
                                </div>
                            </div>
                        </div>

                        <!-- Accordion Item 5 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingFive">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseFive" aria-expanded="false"
                                    aria-controls="flush-collapseFive">
                                    #5 Dokumentasikan aktivitas harian dan hasil kerjamu
                                </button>
                            </h2>
                            <div id="flush-collapseFive" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    Selama magang, catat semua kegiatan yang kamu lakukan setiap hari, termasuk tugas yang diberikan, tools yang dipelajari, dan proyek yang dikerjakan. Catatan ini akan sangat membantu saat kamu membuat laporan mingguan maupun log harian yang harus dikirimkan ke dosen pembimbing.
                                </div>
                            </div>
                        </div>

                        <!-- Accordion Item 6 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingSix">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseSix" aria-expanded="false"
                                    aria-controls="flush-collapseSix">
                                    #6 Lakukan tugas dengan serius dan penuh tanggung jawab
                                </button>
                            </h2>
                            <div id="flush-collapseSix" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingSix" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    Tunjukkan bahwa kamu teliti, disiplin, dan bisa diandalkan. Ini adalah cara terbaik untuk menunjukkan bahwa kamu memiliki etos kerja yang kuat dan layak mendapat kepercayaan untuk tugas yang lebih kompleks.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Script Lottie Player -->
<script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs"  type="module"></script>

<!-- Style CSS -->
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

    /* Chip/Badge */
    .chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #ffffff;
        background-color: rgb(238, 238, 245);
        border-radius: 50px;
        transition: all 0.3s ease;
        cursor: default;
        white-space: nowrap;
    }

    .chip:hover {
        background-color: rgb(69, 65, 117);
        transform: scale(1.05);
    }

    .chip-primary {
        color: #fff;
        background-color: #435ebe;
    }

    /* Padding bawah halaman */
    body {
        padding-bottom: 60px;
    }
</style>