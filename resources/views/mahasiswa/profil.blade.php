@extends('layouts.tamplate')

@section('content')
    <div class="page-heading">
        <h3>My Profile</h3>
    </div>

    <section class="section">
        <div class="row">
           <!-- LEFT PANEL -->
<div class="col-md-4">
    <div class="position-sticky" style="top: 90px;">
        <div class="card p-4 text-center">
            <img src="{{ asset('template/assets/images/mhs.jpeg') }}" alt="Profile Picture"
                class="rounded-circle mx-auto d-block mb-3" width="100" height="100"
                style="border: 5px solid blue;">

            <div class="text-start mt-3">
                <div class="mb-2">
                    <label class="form-label">NIM</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->mahasiswa->nim ?? '-' }}" disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">Nama</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->mahasiswa->nama ?? '-' }}" disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" value="******" disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">Alamat</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->mahasiswa->alamat ?? '-' }}" disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">No Telepon</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->mahasiswa->no_telepon ?? '-' }}" disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->mahasiswa->tanggal_lahir ?? '-' }}" disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                </div>
                <a href="#" class="btn btn-outline-primary mt-2">Edit</a>
            </div>
        </div>
    </div>
</div>

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

                <!-- Preferensi Perusahaan & Lokasi -->
                <h5 class="fw-bold mb-2">Preferensi Perusahaan</h5>
                <p class="text-muted mb-3">
                    Pilih jenis perusahaan yang kamu minati. Kamu bisa memilih lebih dari satu.
                </p>

                <div class="card p-4">
                    <div class="form-group">
                        <label for="jenis_perusahaan" class="form-label">Jenis Perusahaan</label>
                        <select class="choices form-select multiple-remove" multiple="multiple" id="jenis_perusahaan" name="jenis_perusahaan[]">
                            @foreach($jenis as $item)
                                <option value="{{ $item->id }}" {{ in_array($item->id, old('jenis_perusahaan', $preferensi_perusahaan->pluck('jenis_perusahaan_id')->toArray() ?? [])) ? 'selected' : '' }}>
                                    {{ $item->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <h5 class="fw-bold mb-3">Preferensi Lokasi</h5>
                    <p class="text-muted">
                        Pilih sesuai dengan lokasi kamu sekarang.
                    </p>
                <div class="card p-4">
                    <div class="row">
                        <!-- Provinsi -->
                        <div class="col-md-6 mb-3">
                            <label for="provinsi" class="form-label">Provinsi</label>
                            <select class="form-select" id="provinsi" name="provinsi">
                                <option value="">Provinsi</option>
                                {{-- @foreach($provinsi as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->nama }}</option>
                                @endforeach --}}
                            </select>
                        </div>

                        <!-- Kota -->
                        <div class="col-md-6 mb-3">
                            <label for="kota" class="form-label">Kota/Kabupaten</label>
                            <select class="form-select" id="kota" name="kota">
                                <option value="">Kota/Kabupaten</option>
                            </select>
                        </div>
                    </div>
                </div>

                <section class="section">
                    <h5 class="fw-bold mb-3">Pengalaman</h5>
                    <p class="text-muted">
                        Isi semua pengalaman kamu di sini.
                    </p>
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <textarea class="form-control" id="pengalaman" name="pengalaman" rows="4"
                                            placeholder="Contoh: Magang di PT XYZ sebagai Web Developer..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="section">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Upload CV</h5>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <p class="card-text">Lengkapi Profilmu dengan mengunggah dokumen CV.
                            </p>
                            <!-- Basic file uploader -->
                            <input type="file" class="basic-filepond">
                        </div>
                    </div>
                </div>
            </div>
            </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectElement = document.getElementById('jenis_perusahaan');
            if (selectElement) {
                new Choices(selectElement, {
                    removeItemButton: true,
                    placeholder: true,
                    placeholderValue: 'Pilih jenis perusahaan',
                    searchEnabled: true,
                    itemSelectText: '',
                });
            }
        });
    </script>
@endsection