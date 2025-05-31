<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content shadow-sm rounded">

        <div class="modal-header">
            <h5 class="modal-title">Detail Admin</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="container mt-4">

                <div class="mb-3">
                    <h5>File:</h5>
                    @if($aktivitas->foto_path)
                        <img src="{{ asset('storage/aktivitas/' . $aktivitas->foto_path) }}" alt="Foto Aktivitas"
                            class="img-thumbnail mt-2" style="max-width: 300px; height: auto;" onclick="showImagePopup(this.src)">
                    @else
                        <span class="text-muted">Tidak ada file</span>
                    @endif

                </div>

                <div class="mb-3">
                    <h5>Tanggal:</h5>
                    <p>{{ \Carbon\Carbon::parse($aktivitas->tanggal)->format('d M Y') }}</p>
                </div>

                <div class="mb-3">
                    <h5>Deskripsi:</h5>
                    <p>{{ $aktivitas->keterangan }}</p>
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