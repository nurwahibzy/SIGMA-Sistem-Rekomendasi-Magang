<div class="col-md-4">
    <div class="position-sticky" style="top: 90px;">
        <div class="card p-4 text-center">

            <img src="{{ Storage::exists('public/profil/akun/' . Auth::user()->foto_path)
    ? asset('storage/profil/akun/' . Auth::user()->foto_path)
    : asset('template/assets/images/mhs.jpeg') }}" alt="Foto Profil" alt="Profile Picture"
                class="rounded-circle mx-auto d-block mb-3" width="100" height="100" style="border: 5px solid blue;" />
            <form id="form-left-panel" class="text-start mt-3">
                @csrf
                <div class="mb-2">
                    <label class="form-label">NIM</label>
                    <input type="text" class="form-control" id="nim" value="{{ Auth::user()->id_user ?? '-' }}"
                        disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" value="{{ Auth::user()->mahasiswa->nama ?? '-' }}"
                        disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">Alamat</label>
                    <input type="text" class="form-control" id="alamat" value="{{ Auth::user()->mahasiswa->alamat ?? '-' }}"
                        disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">Telepon</label>
                    <input type="text" class="form-control" id="no_telepon"
                        value="{{ Auth::user()->mahasiswa->telepon ?? '-' }}" disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tanggal_lahir"
                        value="{{ Auth::user()->mahasiswa->tanggal_lahir ?? '-' }}" disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" value="{{ Auth::user()->mahasiswa->email }}"
                        disabled>
                </div>
            </form>
        </div>
    </div>
</div>