<div class="card p-5 bg-primary border-0 mb-5">
    <div class="row align-items-center">
        <div class="col-md-4 d-flex justify-content-center">
            <img src="{{ asset('template/assets/images/SIGMA1.png') }}" alt="Foto Profil"
                class="img-fluid rounded w-50 h-50" style="object-fit: cover;">
        </div>
        <div class="col-md-8">
            <h1 class="fw-bold text-light mb-3">
                Temukan Magang Impianmu dengan Mudah!
            </h1>
            <h5 class="text-light">
                Rekomendasi magang otomatis berdasarkan minat, jurusan, dan keahlianmu.
            </h5>
        </div>
    </div>
</div>
<div class="d-flex">
    <div class="card px-5 py-3 border border-secondary mb-5 me-3 ms-5 text-center w-100" style="border-width: 5px;">
        <div class="d-flex flex-column align-items-center">
            <img src="{{ asset('template/assets/images/magang.jpg') }}" alt="Foto Profil"
                class="rounded-circle shadow mb-4" style="width: 120px; height: 120px; object-fit: cover;">

            <h4 class="fw-bold text-primary mb-3">
                Rekomendasi Tempat Magang
            </h4>
            <p class="text-muted">
                Dapatkan rekomendasi program magang yang sesuai dengan jurusan, minat, lokasi, dan tingkat studi kamu.
            </p>
        </div>
    </div>
    <div class="card px-5 py-3 border border-secondary mb-5 me-5 ms-3 text-center w-100" style="border-width: 5px;">
        <div class="d-flex flex-column align-items-center">
            <img src="{{ asset('template/assets/images/magang.jpg') }}" alt="Foto Profil"
                class="rounded-circle shadow mb-4" style="width: 120px; height: 120px; object-fit: cover;">

            <h4 class="fw-bold text-primary mb-3">
                Temukan Peluang Magang Terbaik
            </h4>
            <p class="text-muted">
            Jelajahi berbagai program magang dari perusahaan terpercaya dan daftarkan diri kamu secara langsung melalui platform ini.
            </p>
        </div>
    </div>
</div>

<div class="d-flex">
    <div class="card px-5 py-3 border border-secondary mb-5 me-3 ms-5 text-center w-100" style="border-width: 5px;">
        <div class="d-flex flex-column align-items-center">
            <img src="{{ asset('template/assets/images/magang.jpg') }}" alt="Foto Profil"
                class="rounded-circle shadow mb-4" style="width: 120px; height: 120px; object-fit: cover;">

            <h4 class="fw-bold text-primary mb-3">
            Kelola Profil Mahasiswa
            </h4>
            <p class="text-muted">
            Lengkapi profil kamu dengan informasi pendidikan dan keterampilan untuk mempermudah pencarian magang yang cocok.
            </p>
        </div>
    </div>

    <div class="card px-5 py-3 border border-secondary mb-5 me-5 ms-3 text-center w-100" style="border-width: 5px;">
        <div class="d-flex flex-column align-items-center">
            <img src="{{ asset('template/assets/images/magang.jpg') }}" alt="Foto Profil"
                class="rounded-circle shadow mb-4" style="width: 120px; height: 120px; object-fit: cover;">

            <h4 class="fw-bold text-primary mb-3">
            Rekomendasi Otomatis
            </h4>
            <p class="text-muted">
            Sistem kami akan merekomendasikan program magang berdasarkan informasi yang kamu masukkan secara lengkap dan akurat.
            </p>
        </div>
    </div>
</div>
<div>
    <h4 class="fw-bold text-primary mb-3">
        Temukan Perusahaan Magang Anda
    </h4>
    <p class="text-muted">
        Jelajahi profil perusahaan untuk menemukan tempat magang yang tepat bagi Anda.
    </p>
</div>

<div class="d-flex overflow-auto flex-nowrap">
    @foreach ($perusahaan as $item)
        <div class="card px-5 border border-secondary mb-5 me-4 " style="border-width: 5px; min-width: 300px;">

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
<div class="ms-5 mt-5">
    <button onclick="modalAction('{{ url('/admin/admin/tambah') }}')" class="btn btn-primary">
        Lihat Selengkapnya
    </button>
</div>