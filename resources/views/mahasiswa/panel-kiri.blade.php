<div class="col-md-4">
    <div class="position-sticky" style="top: 90px;">
        <div class="card p-4 text-center">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <div class="avatar avatar-2xl mb-3">
                    <img id="preview" src="{{ Storage::exists('public/profil/akun/' . Auth::user()->foto_path)
    ? asset('storage/profil/akun/' . Auth::user()->foto_path)
    : asset('template/assets/images/mhs.jpeg') }}" alt="Profile Picture" class="rounded-circle"
                        style="width: 120px; height: 120px; border: 5px solid blue; object-fit: cover; cursor: pointer;"
                        onclick="showImagePopup(this.src)" />

                </div>
            </div>
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
                    <input type="text" class="form-control" id="alamat"
                        value="{{ Auth::user()->mahasiswa->alamat ?? '-' }}" disabled>
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
<div id="image-popup" style="
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100vw; height: 100vh;
    background-color: rgba(0,0,0,0.8);
    z-index: 1050;
    justify-content: center;
    align-items: center;
">
    <span id="close-popup" style="
        position: absolute;
        top: 20px; right: 30px;
        font-size: 30px;
        color: white;
        cursor: pointer;
        z-index: 1060;
    ">&times;</span>
    <img id="popup-img" src="" alt="Full Image" style="
        max-width: 90vw;
        max-height: 90vh;
        border-radius: 10px;
        box-shadow: 0 0 10px #000;
        object-fit: contain;
    ">
</div>
<script>
    function showImagePopup(src) {
        const popup = document.getElementById('image-popup');
        const popupImg = document.getElementById('popup-img');
        popupImg.src = src;
        popup.style.display = 'flex';
    }

    document.getElementById('close-popup').addEventListener('click', function () {
        document.getElementById('image-popup').style.display = 'none';
    });

    document.getElementById('image-popup').addEventListener('click', function (e) {
        if (e.target.id === 'image-popup') {
            document.getElementById('image-popup').style.display = 'none';
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === "Escape") {
            document.getElementById('image-popup').style.display = 'none';
        }
    });
</script>