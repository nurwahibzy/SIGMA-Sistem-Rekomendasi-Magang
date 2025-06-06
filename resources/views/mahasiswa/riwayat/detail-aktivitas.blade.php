<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content shadow-sm rounded">

        <div class="modal-header">
            <h5 class="modal-title">Detail Admin</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            @if (Storage::exists('public/aktivitas/' . $aktivitas->foto_path))
                <div class="container mt-4">
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <div class="avatar avatar-2xl mb-3">
                            <img id="preview" src="{{ asset('storage/aktivitas/' . $aktivitas->foto_path) }}"
                                alt="Profile Picture" class="img-fluid rounded w-50 h-50"
                                style="width: 120px; height: 120px; border: 5px solid blue; object-fit: cover; cursor: pointer;"
                                onclick="showDosenImagePopup(this.src)" />
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning text-center">
                    Tidak ada foto.
                </div>
            @endif

            <div class="container mt-4">
                <div class="mb-3">
                    <label for="keterangan" class="form-label">Kenterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
                        required>{{  $aktivitas->keterangan }}</textarea>
                </div>
            </div>
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