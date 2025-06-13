<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content shadow-sm rounded">

        <div class="modal-header bg-primary text-white rounded-top">
            <h5 class="text-light">Detail Perusahaan</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="container mt-4">
                <div class="d-flex justify-content-center align-items-center flex-column">
                    <div class="avatar avatar-2xl mb-3">
                        <img id="preview" src="{{ Storage::exists('public/profil/perusahaan/' . $perusahaan->foto_path)
    ? asset('storage/profil/perusahaan/' . $perusahaan->foto_path)
    : asset('template/assets/images/mhs.jpeg') }}" alt="Profile Picture" class="rounded-circle"
                            style="width: 120px; height: 120px; border: 5px solid blue; object-fit: cover; cursor: pointer;"
                            onclick="showImagePopup(this.src)" />

                    </div>
                    <h4 class="mt-2 text-center">{{ $perusahaan->nama }}</h4>
                    <p class="text-small">{{ $perusahaan->telepon }}</p>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <ul class="nav nav-tabs w-100" id="detailTab" role="tablist">
                    <li class="nav-item flex-fill text-center">
                        <a class="nav-link active" data-bs-toggle="tab" href="#perusahaan" role="tab">Perusahaan</a>
                    </li>
                    <li class="nav-item flex-fill text-center">
                        <a class="nav-link" data-bs-toggle="tab" href="#lowongan" role="tab">Lowongan</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content" id="detailTabContent">
                <div class="tab-pane fade show active" id="perusahaan" role="tabpanel">
                    <div class="container mt-4">
                        <div class="d-flex justify-content-between">
                            <div class="w-50 me-2">
                                <div>
                                    <label class="form-label fw-bold">Jenis Perusahaan</label>
                                    <div class="border rounded p-2">
                                        <p class="form-control-plaintext mb-0">
                                            {{ $perusahaan->jenis_perusahaan->jenis }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="w-50 ms-2"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="w-50 me-2">
                                <div class="mt-4">
                                    <label class="form-label fw-bold">Provinsi</label>
                                    <div class="border rounded p-2">
                                        <p class="form-control-plaintext mb-0">{{ $perusahaan->provinsi }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="w-50 ms-2">
                                <div class="mt-4">
                                    <label class="form-label fw-bold">Daerah</label>
                                    <div class="border rounded p-2">
                                        <p class="form-control-plaintext mb-0">{{ $perusahaan->daerah }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container mt-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi</label>
                            <div class="border rounded p-2">
                                <p class="form-control-plaintext mb-0">{{ $perusahaan->deskripsi }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="lowongan" role="tabpanel">
                    @if (count($lowongan))
                        <div class="container mt-4">
                            @foreach ($lowongan as $item)
                                <div class="mb-3 border rounded pb-2 pt-2 pe-5 ps-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="form-control-plaintext mb-0">{{ $item->lowongan . ' - ' . $item->bidang }}</p>
                                        </div>
                                        <div>
                                           @if ($item->status == 'baru')
    <div 
        class="px-3 py-1 border text-white rounded" 
        style="background: linear-gradient(to right,rgb(130, 191, 234), #8B5CF6);">
        Baru
    </div>

                                            @else
                                            <ul class="list-unstyled mb-0">
    <li><strong>Tugas:</strong>
        @for ($i = 0; $i < round($item->tugas); $i++)
            <span class="text-warning">★</span>
        @endfor
    </li>
    <li><strong>Pembinaan:</strong>
        @for ($i = 0; $i < round($item->pembinaan); $i++)
            <span class="text-warning">★</span>
        @endfor
    </li>
    <li><strong>Fasilitas:</strong>
        @for ($i = 0; $i < round($item->fasilitas); $i++)
            <span class="text-warning">★</span>
        @endfor
    </li>
</ul>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                    <div class="alert alert-warning">
                                    Belum ada lowongan magang.
                                </div>
                    @endif
                </di>
            </div>
        </div>

        <div class="modal-footer">
            <!-- d-flex justify-content-between -->
            <button type="button" class="btn btn-danger" id="btn-hapus">
                <i class="bi bi-trash"></i> Hapus
            </button>
            <button type="button" class="btn btn-primary"
                onclick="modalAction('{{ url('/admin/perusahaan/edit/' . $perusahaan->id_perusahaan) }}')">
                <i class="bi bi-pencil-square"></i> Edit
            </button>
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
<script>
    $(document).ready(function () {
        $('#btn-hapus').click(function () {
            Swal.fire({
                title: 'Yakin ingin menghapus data ini?',
                text: "Akan Menghapus Seluruh Lowongan dan Periode yang dimiliki Perusahaan ini!!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('admin/perusahaan/edit/' . $perusahaan->id_perusahaan) }}",
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Data berhasil dihapus.'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal menghapus data. Silakan coba lagi.'
                            });
                        }
                    });
                }
            });
        });
    })
</script>