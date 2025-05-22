<form action="{{ url('/admin/mahasiswa/edit/' . $mahasiswa->akun->id_akun) }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mt-4">
                    <div class="tab-content" id="detailTabContent">

                        <div class="mb-3">
                            <label for="file" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".jpg,.jpeg,.png">
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">Pilih Status</option>
                                <option value="aktif" {{ $mahasiswa->akun->status == 'aktif' ? 'selected' : ''  }}>aktif
                                </option>
                                <option value="nonaktif" {{ $mahasiswa->akun->status == 'nonaktif' ? 'selected' : ''  }}>
                                    nonaktif</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_prodi" class="form-label">Program Studi</label>
                            <select name="id_prodi" id="id_prodi" class="form-control" required>
                                <option value="">Pilih Prodi</option>
                                @foreach ($prodi as $item)
                                    <option value="{{ $item->id_prodi }}" {{ $mahasiswa->id_prodi == $item->id_prodi ? 'selected' : ''  }}>{{ $item->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_user" class="form-label">NIP</label>
                            <input type="text" class="form-control" id="id_user" name="id_user" required
                                value="{{ $mahasiswa->akun->id_user }}">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required
                                value="{{ $mahasiswa->nama }}">
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3"
                                required>{{ $mahasiswa->alamat }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="telepon" class="form-label">Telepon</label>
                            <input type="text" class="form-control" id="telepon" name="telepon" required
                                value="{{ $mahasiswa->telepon }}">
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required
                                value="{{ $mahasiswa->tanggal_lahir }}">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required
                                value="{{ $mahasiswa->email }}">
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
                status: { required: true },
                id_prodi: { required: true },
                nama: { required: true },
                alamat: { required: true },
                telepon: { required: true, digits: true },
                tanggal_lahir: { required: true, date: true },
                email: { required: true, email: true }
            },
            messages: {
                id_user: "ID User wajib diisi dan numerik",
                status: "Status wajib diisi",
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