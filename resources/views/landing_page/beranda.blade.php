<div class="card p-5 bg-primary border-0 mb-5">
    <div class="row align-items-center">
        <div class="col-md-4 d-flex justify-content-center">
            <lottie-player src="https://assets9.lottiefiles.com/packages/lf20_3rwasyjy.json" background="transparent"
                speed="1" style="width: 70%; height: 70%;" loop autoplay>
            </lottie-player>

        </div>
        <div class="col-md-8">
            <h1 class="fw-bold mb-3" style="color: white;">
                Temukan Magang Impianmu dengan Mudah!
            </h1>
            <h5 class="text-white">
                Rekomendasi magang otomatis berdasarkan minat, jurusan, dan keahlianmu.
            </h5>
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center gap-4 mb-5">
        <!-- Baris pertama -->
        <div class="col-md-6 col-lg-4 d-flex flex-column align-items-center">
            <img src="{{ asset('template/assets/images/target.jpg') }}" alt="Rekomendasi Tempat Magang"
                class="rounded-circle shadow-sm mb-4 border border-2 border-primary"
                style="width: 100px; height: 100px; object-fit: cover;">
            <h4 class="fw-bold text-primary mb-3">
                Rekomendasi Tempat Magang
            </h4>
            <p class="text-dark-muted">
                Dapatkan rekomendasi program magang yang sesuai dengan jurusan, minat, lokasi, dan tingkat studi kamu.
            </p>
        </div>

        <div class="col-md-6 col-lg-4 d-flex flex-column align-items-center">
            <img src="{{ asset('template/assets/images/rocket.jpg') }}" alt="Temukan Peluang Magang Terbaik"
                class="rounded-circle shadow-sm mb-4 border border-2 border-primary"
                style="width: 100px; height: 100px; object-fit: cover;">
            <h4 class="fw-bold text-primary mb-3">
                Temukan Peluang Magang Terbaik
            </h4>
            <p class="text-dark-muted text-center">
                Jelajahi berbagai program magang dari perusahaan terpercaya dan daftarkan diri kamu secara langsung
                melalui platform ini.
            </p>
        </div>
    </div>
    <hr class="my-5" style="max-width: 800px; margin: 0 auto;">
    <div class="row justify-content-center gap-4">
        <!-- Baris kedua -->
        <div class="col-md-6 col-lg-4 d-flex flex-column align-items-center">
            <img src="{{asset('template/assets/images/mhs.jpeg') }}" alt="Kelola Profil Mahasiswa"
                class="rounded-circle shadow-sm mb-4 border border-2 border-primary"
                style="width: 100px; height: 100px; object-fit: cover;">
            <h4 class="fw-bold text-primary mb-3">
                Kelola Profil Mahasiswa
            </h4>
            <p class="text-dark-muted text-center">
                Lengkapi profil kamu dengan informasi pendidikan dan keterampilan untuk mempermudah pencarian magang
                yang cocok.
            </p>
        </div>

        <div class="col-md-6 col-lg-4 d-flex flex-column align-items-center">
            <img src="{{ asset('template/assets/images/otomatis.jpg') }}" alt="Rekomendasi Otomatis"
                class="rounded-circle shadow-sm mb-4 border border-2 border-primary"
                style="width: 100px; height: 100px; object-fit: cover;">
            <h4 class="fw-bold text-primary mb-3">
                Rekomendasi Otomatis
            </h4>
            <p class="text-dark-muted text-center">
                Sistem kami akan merekomendasikan program magang berdasarkan informasi yang kamu masukkan secara lengkap
                dan akurat.
            </p>
        </div>
    </div>
</div>
<br>
<div>
    <h4 class="fw-bold text-primary mb-3">
        Temukan Perusahaan Magang Anda
    </h4>
    <p class="text-dark-muted">
        Jelajahi profil perusahaan untuk menemukan tempat magang yang tepat bagi Anda.
    </p>
</div>

<div class="d-flex overflow-auto flex-nowrap">
    @foreach ($perusahaan as $item)
        <div class="card px-5 mb-5 me-4 company-card" style="min-width: 300px;">
            <img src="{{ asset('storage/profil/perusahaan/' . $item->foto_path) }}" alt="Foto Profil"
                class="img-fluid rounded w-50 h-50 mb-1 mt-3" style="object-fit: cover;">

            <h4 class="fw-bold text-black mb-1">
                {{ $item->nama }}
            </h4>
            <div class="p-1">
                {{ $item->jumlah_lowongan }} Lowongan
            </div>
        </div>
    @endforeach
</div>
{{-- <div class="ms-5 mt-5">
    <button onclick="modalAction('{{ url('/admin/admin/tambah') }}')" class="btn btn-primary">
        Lihat Selengkapnya
    </button>
</div> --}}

<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<style>
    .hover-shadow:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        transition: all 0.3s ease-in-out;
    }

    .text-dark-muted {
        color: #555 !important;
    }

    /* Card Perusahaan */
    .company-card {
        background-color: #f8f9fa;
        /* Warna latar belakang ringan */
        transition: all 0.3s ease;
        /* Efek transisi untuk hover */
        border-radius: 10px;
        /* Bulatkan sudut card */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        /* Bayangan lembut */
    }

    /* Hover Effect */
    .company-card:hover {
        transform: scale(1.05);
        /* Memperbesar sedikit saat hover */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        /* Bayangan lebih tebal saat hover */
        cursor: pointer;
        /* Menunjukkan cursor pointer saat di-hover */
    }

    /* Gambar Profil Perusahaan */
    .company-card img {
        object-fit: cover;
        border-radius: 50%;
        /* Mengubah gambar menjadi lingkaran */
    }

    /* Judul Perusahaan */
    .company-card h4 {
        font-size: 1.2rem;
        /* Ukuran font judul */
        color: #333;
        /* Warna teks hitam pekat */
    }

    /* Jumlah Lowongan */
    .company-card div {
        font-size: 0.9rem;
        /* Ukuran font lebih kecil */
        color: #6c757d;
        /* Warna abu-abu terang */
    }
</style>