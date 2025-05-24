<form action="{{ url('/admin/mahasiswa/tambah') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header bg-primary text-white rounded-top">
                            <h5 class="modal-title">Tambah Mahasiswa</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
            <div class="modal-body">
                <div class="container mt-4">
                    <div class="container mt-4">
                        <ul class="nav nav-tabs mb-3" id="detailTab" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#akun">Akun</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#data">Data</a></li>
                        </ul>

                        <div class="tab-content" id="detailTabContent">
                            <div class="tab-pane fade show active" id="akun" role="tabpanel">
                                <div class="mb-3">
                                    <label for="file" class="form-label">Foto</label>
                                    <input type="file" class="form-control" id="file" name="file"
                                        accept=".jpg,.jpeg,.png">
                                </div>
                                <div class="mb-3">
                                    <label for="id_prodi" class="form-label">Program Studi</label>
                                    <select name="id_prodi" id="id_prodi" class="form-select" required>
                                        <option value="">Pilih Prodi</option>
                                        @foreach ($prodi as $item)
                                            <option value="{{ $item->id_prodi }}">{{ $item->nama_prodi }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="id_user" class="form-label">NIM</label>
                                    <input type="text" class="form-control" id="id_user" name="id_user" required>
                                </div>

                                <div class="mb-3">
                                    <label for="telepon" class="form-label">Telepon</label>
                                    <input type="text" class="form-control" id="telepon" name="telepon" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="data" role="tabpanel">
                            <div class="mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>

                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3"
                                        required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
</form>

<script>
    $(document).ready(function () {
        $("#form-tambah").validate({
            rules: {
                id_user: { required: true, digits: true },
                id_prodi: { required: true },
                nama: { required: true },
                alamat: { required: true },
                telepon: { required: true, digits: true },
                tanggal_lahir: { required: true, date: true },
                email: { required: true, email: true }
            },
            messages: {
                id_user: "ID User wajib diisi dan numerik",
                id_prodi: "Prodi wajib diisi",
                nama: "Nama wajib diisi",
                alamat: "Alamat wajib diisi",
                telepon: "Nomor telepon wajib diisi dan numerik",
                tanggal_lahir: "Tanggal lahir wajib diisi",
                email: "Email wajib diisi dan harus valid"
            },
            submitHandler: function (form) {
                const formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Data berhasil disimpan.'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message || 'Terjadi kesalahan saat menyimpan.'
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan.'
                        });
                    }
                });

                return false;
            }
        });
    });
</script>