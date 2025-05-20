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
                        <form id="form-left-panel" class="text-start mt-3">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label">NIM</label>
                                <input type="text" class="form-control" id="nim"
                                    value="{{ Auth::user()->mahasiswa->nim ?? '-' }}" disabled>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama"
                                    value="{{ Auth::user()->mahasiswa->nama ?? '-' }}" disabled>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" value="******" disabled>
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah.</small>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamat"
                                    value="{{ Auth::user()->mahasiswa->alamat ?? '-' }}" disabled>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">No Telepon</label>
                                <input type="text" class="form-control" id="no_telepon"
                                    value="{{ Auth::user()->mahasiswa->no_telepon ?? '-' }}" disabled>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir"
                                    value="{{ Auth::user()->mahasiswa->tanggal_lahir ?? '-' }}" disabled>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="email"
                                    value="{{ Auth::user()->email }}" disabled>
                            </div>
                            <button type="button" id="btn-edit-profile" class="btn btn-outline-primary mt-2 w-100">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                            <button type="submit" id="btn-save-profile" class="btn btn-success mt-2 w-100 d-none">
                                <i class="bi bi-check-lg"></i> Simpan
                            </button>
                        </form>
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

                <!-- Tombol Edit untuk Right Panel -->
                <div class="mb-4 d-flex justify-content-end">
                    <button id="btn-edit-forms" class="btn btn-outline-primary">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>
                </div>

                <form id="form-right-panel" action="#" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- KEAHLIAN -->
                    <fieldset id="section-keahlian" disabled>
                        <h5 class="fw-bold mb-2">Keahlian</h5>
                        <p class="text-muted mb-3">
                            Tulis keahlian mu dan tentukan skala prioritas nya
                        </p>
                        <div class="card mb-4">
                            <div class="card-body">
                                <!-- Bidang Keahlian -->
                                <div class="mb-3">
                                    <label for="id_bidang" class="form-label">Bidang Keahlian</label>
                                    <select name="id_bidang" id="id_bidang" class="form-select">
                                        <option value="">-- Pilih Bidang --</option>
                                        {{-- @foreach ($data['bidang'] as $bidang)
                                            <option value="{{ $bidang->id_bidang }}">{{ $bidang->nama }}</option>
                                        @endforeach --}}
                                    </select>
                                </div>

                                <!-- Prioritas -->
                                <div class="mb-3">
                                    <label for="prioritas" class="form-label">Prioritas</label>
                                    <select name="prioritas" id="prioritas" class="form-select">
                                        <option value="">-- Pilih Prioritas --</option>
                                        {{-- @for ($i = 1; $i <= 8; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor --}}
                                    </select>
                                    <small class="text-muted">Pilih angka antara 1 - 8 sebagai urutan prioritas.</small>
                                </div>

                                <!-- Deskripsi Keahlian -->
                                <div class="mb-3">
                                    <label for="keahlian" class="form-label">Deskripsi Keahlian</label>
                                    <textarea name="keahlian" id="keahlian" rows="4" class="form-control">{{ $data['pilihan_terakhir']->keahlian ?? old('keahlian') }}</textarea>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-success d-none save-btn">
                                        <i class="bi bi-check-lg"></i> Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </fieldset>

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


                    <!-- PREFERENSI LOKASI -->
                    <fieldset id="section-lokasi" disabled>
                        <h5 class="fw-bold mb-3">Preferensi Lokasi</h5>
                        <p class="text-muted mb-3">
                            Pilih sesuai dengan lokasi kamu sekarang.
                        </p>
                        <div class="card p-4 mb-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="provinsi" class="form-label">Provinsi</label>
                                    <select class="form-select" id="provinsi" name="provinsi">
                                        <option value="">Provinsi</option>
                                        {{-- @foreach($provinsi as $prov)
                                            <option value="{{ $prov->id }}">{{ $prov->nama }}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="kota" class="form-label">Kota/Kabupaten</label>
                                    <select class="form-select" id="kota" name="kota">
                                        <option value="">Kota/Kabupaten</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-sm btn-success d-none save-btn">
                                    <i class="bi bi-check-lg"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </fieldset>

                    <!-- PENGALAMAN -->
                    <fieldset id="section-pengalaman" disabled>
                        <h5 class="fw-bold mb-3">Pengalaman</h5>
                        <p class="text-muted mb-3">
                            Isi semua pengalaman kamu di sini.
                        </p>
                        <div class="row">
                            <div class="col">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <textarea class="form-control" id="pengalaman" name="pengalaman" rows="4"
                                                placeholder="Contoh: Magang di PT XYZ sebagai Web Developer..."></textarea>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-sm btn-success d-none save-btn">
                                                <i class="bi bi-check-lg"></i> Simpan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <!-- UPLOAD CV -->
                    <fieldset id="section-cv" disabled>
                        <h5 class="fw-bold mb-3">CV</h5>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title">Upload CV</h5>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <p class="card-text">Lengkapi Profilmu dengan mengunggah dokumen CV.</p>
                                            <input type="file" name="cv" class="form-control basic-filepond">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-sm btn-success d-none save-btn">
                                <i class="bi bi-check-lg"></i> Simpan
                            </button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const editBtn = document.getElementById("btn-edit-profile");
        const saveBtn = document.getElementById("btn-save-profile");
        const inputs = document.querySelectorAll("#form-left-panel input");

        // EDIT & SAVE PROFILE (LEFT PANEL)
        editBtn.addEventListener("click", function () {
            inputs.forEach(input => input.disabled = false);
            editBtn.classList.add("d-none");
            saveBtn.classList.remove("d-none");
        });

        saveBtn.addEventListener("click", function () {
            const data = {
                _token: "{{ csrf_token() }}",
                nim: document.getElementById("nim").value,
                nama: document.getElementById("nama").value,
                password: document.getElementById("password").value,
                alamat: document.getElementById("alamat").value,
                no_telepon: document.getElementById("no_telepon").value,
                tanggal_lahir: document.getElementById("tanggal_lahir").value,
                email: document.getElementById("email").value,
            };

            
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    alert("Profil berhasil diperbarui!");
                    inputs.forEach(input => input.disabled = true);
                    saveBtn.classList.add("d-none");
                    editBtn.classList.remove("d-none");
                } else {
                    alert("Gagal menyimpan profil.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Terjadi kesalahan.");
            });
        });

        // EDIT & SHOW SAVE BUTTONS (RIGHT PANEL)
        const btnEditForms = document.getElementById("btn-edit-forms");
        const fieldsets = document.querySelectorAll("fieldset");
        const saveButtons = document.querySelectorAll(".save-btn");

        btnEditForms.addEventListener("click", function () {
            if (fieldsets[0].disabled) {
                // Enable semua fieldset
                fieldsets.forEach(fs => fs.disabled = false);

                // Tampilkan semua tombol simpan
                saveButtons.forEach(btn => btn.classList.remove("d-none"));

                // Ganti teks tombol Edit jadi Simpan
                btnEditForms.innerHTML = '<i class="bi bi-save"></i> Simpan';
                btnEditForms.classList.replace("btn-outline-primary", "btn-success");
            } else {
                // Jika sudah enable, kembalikan
                fieldsets.forEach(fs => fs.disabled = true);
                saveButtons.forEach(btn => btn.classList.add("d-none"));

                btnEditForms.innerHTML = '<i class="bi bi-pencil-square"></i> Edit';
                btnEditForms.classList.replace("btn-success", "btn-outline-primary");
            }
        });

        // Choices.js initialization
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