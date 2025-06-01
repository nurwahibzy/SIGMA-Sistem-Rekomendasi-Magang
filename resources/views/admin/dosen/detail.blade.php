<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content shadow-sm rounded">

        <!-- Header Modal -->
        <div class="modal-header bg-primary text-white rounded-top">
            <h5 class="modal-title">Detail Dosen</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="container mt-4">
                <div class="d-flex justify-content-center align-items-center flex-column">
                    <div class="avatar avatar-2xl mb-3">
                        <img id="preview" src="{{ Storage::exists('public/profil/akun/' . $dosen->akun->foto_path)
    ? asset('storage/profil/akun/' . $dosen->akun->foto_path)
    : asset('template/assets/images/mhs.jpeg') }}" alt="Profile Picture" class="rounded-circle"
                            style="width: 120px; height: 120px; border: 5px solid blue; object-fit: cover; cursor: pointer;"
                            onclick="showImagePopup(this.src)" />

                    </div>
                    <h4 class="mt-2 text-center">{{ $dosen->nama }}</h4>
                    <p class="text-small">{{ $dosen->akun->id_user }}</p>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <ul class="nav nav-tabs w-100" id="detailTab" role="tablist">
                    <li class="nav-item flex-fill text-center">
                        <a class="nav-link active" data-bs-toggle="tab" href="#profil" role="tab">Profil</a>
                    </li>
                    <li class="nav-item flex-fill text-center">
                        <a class="nav-link" data-bs-toggle="tab" href="#keahlian" role="tab">Keahlian</a>
                    </li>
                    <li class="nav-item flex-fill text-center">
                        <a class="nav-link" data-bs-toggle="tab" href="#bimbingan" role="tab">Bimbingan</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content" id="detailTabContent">
                <div class="tab-pane fade show active" id="profil" role="tabpanel">

                    <div class="container mt-4">
                        <div class="d-flex justify-content-between">
                            <div class="w-50 me-2">
                                <div>
                                    <label class="form-label fw-bold">Email</label>
                                    <div class="border rounded p-2">
                                        <p class="form-control-plaintext mb-0">{{ $dosen->email }}</p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="form-label fw-bold">Tanggal Lahir</label>
                                    <div class="border rounded p-2">
                                        <p class="form-control-plaintext mb-0">
                                            {{  \Carbon\Carbon::parse($dosen->tanggal_lahir)->format('d M Y')  }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="w-50 ms-2">
                                <div>
                                    <label class="form-label fw-bold">Telepon</label>
                                    <div class="border rounded p-2">
                                        <p class="form-control-plaintext mb-0">{{ $dosen->telepon }}</p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="form-label fw-bold">Gender</label>
                                    <div class="border rounded p-2">
                                        <p class="form-control-plaintext mb-0">{{ $dosen->gender == 'l' ? 'Laki-laki' : 'Perempuan' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container mt-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat</label>
                            <div class="border rounded p-2">
                                <p class="form-control-plaintext mb-0">{{ $dosen->alamat }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="keahlian" role="tabpanel">
                    <div class="container mt-4">
                        @if (count($keahlian))
                            <div class="border rounded p-2">
                                <table class="table">
                                    @foreach ($keahlian as $i => $item)
                                        <tr>
                                            <td>{{ $item->bidang->nama  }}</td>
                                            <td>{{$item->keahlian }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        @else
                            <div class="alert alert-warning text-center">
                                Belum ada keahlian yang dimasukkan.
                            </div>
                        @endif
                    </div>
                </div>
                <div class="tab-pane fade" id="bimbingan" role="tabpanel">
                    @if (!empty($perusahaan))
                                    <div class="container mt-4">
                                        <label class="form-label fw-bold fs-5 mb-3">Perusahaan Teratas</label>
                                        <div class="d-flex align-items-center border rounded p-3 shadow-sm">
                                            <div class="me-4">
                                                <img src="{{ Storage::exists('public/profil/perusahaan/' . $perusahaan->foto_path)
                        ? asset('storage/profil/perusahaan/' . $perusahaan->foto_path)
                        : asset('template/assets/images/mhs.jpeg') }}" alt="Profile Picture" class="img-fluid rounded"
                                                    style="width: 100px; height: 100px; object-fit: cover;">
                                            </div>
                                            <div>
                                                <table class="table table-borderless mb-0">
                                                    <tr>
                                                        <th class="text-muted">Perusahaan</th>
                                                        <td>: {{ $perusahaan->nama }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-muted">Total Mahasiswa</th>
                                                        <td>: {{ $perusahaan->total }} orang</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container mt-4">
                                        <label class="form-label fw-bold fs-5 mb-3">Mahasiswa</label>
                                        <div class="d-flex justify-content-between">
                                            <div class="w-50 me-2 border rounded d-flex justify-content-center">
                                                <div style="width: 250px; height: 250px;">
                                                    <canvas id="statusChart"></canvas>
                                                </div>
                                            </div>
                                            <div
                                                class="w-50 ms-2 border rounded text-center d-flex flex-column justify-content-center align-items-center fw-bold py-3">
                                                <p class="mb-1">Jumlah Mahasiswa</p>
                                                <p class="mb-0">{{ $amountMahasiswaDiterima + $amountMahasiswaLulus }}</p>
                                            </div>
                                        </div>
                                    </div>
                    @else
                        <div class="alert alert-warning text-center">
                            Belum ada mahasiswa yang dibimbing.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="btn-hapus">
                <i class="bi bi-trash"></i> Hapus
            </button>
            <button type="button" class="btn btn-primary"
                onclick="modalAction('{{ url('/admin/dosen/edit/' . $dosen->akun->id_akun) }}')">
                Edit
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
    const ctx = document.getElementById('statusChart').getContext('2d');

    const statusChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['diterima', 'lulus'],
            datasets: [{
                label: 'Jumlah Mahasiswa',
                data: [{{ $amountMahasiswaDiterima }}, {{ $amountMahasiswaLulus }}],
                backgroundColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                title: {
                    display: true,
                    text: 'Status Magang'
                }
            }
        }
    });
</script>
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
                text: "Tindakan ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url(path: 'admin/dosen/edit/' . $dosen->akun->id_akun) }}",
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