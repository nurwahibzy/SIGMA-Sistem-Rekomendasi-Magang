<form action="{{ url('/admin/lowongan/tambah') }}" method="POST" id="form-tambah">
    @csrf
       <div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content shadow-sm rounded">

        <div class="modal-header bg-primary text-white rounded-top">
            <h5 class="modal-title">Tambah Lowongan</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

            <div class="modal-body">
                <div class="container mt-4">
                    <div class="tab-content" id="detailTabContent">
                        <div class="mb-3">
                            <label for="id_perusahaan" class="form-label">Perusahaan</label>
                            <div class="d-flex gap-2">
                                <select name="id_perusahaan" id="id_perusahaan" class="form-control" required>
                                    <option value="">Pilih Perusahaan </option>
                                    @foreach ($perusahaan as $item)
                                        <option value="{{ $item->id_perusahaan }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                                <a href="{{ url('admin/perusahaan/') }}" class="btn btn-success">
                                    <i class="bi bi-plus-lg"></i>
                                </a>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="id_bidang" class="form-label">Bidang</label>
                            <select name="id_bidang" id="id_bidang" class="form-control" required>
                                <option value="">Pilih Bidang </option>
                                @foreach ($bidang as $item)
                                    <option value="{{ $item->id_bidang }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>

                        <div class="mb-3">
                            <label for="persyaratan" class="form-label">Persyaratan</label>
                            <textarea class="form-control" id="persyaratan" name="persyaratan" rows="3"
                                required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
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
                id_perusahaan: { required: true },
                id_bidang: { required: true },
                nama: { required: true },
                persyaratan: { required: true },
                deskripsi: { required: true }
            },
            messages: {
                id_perusahaan: "Pilih perusahaan",
                id_bidang: "Pilih Bidang Magang",
                nama: "Nama Magang wajib diisi",
                persyaratan: "Persyaratan Magang wajib diisi",
                deskripsi: "Deskripsi Magang wajib diisi",
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
                                text: 'Data berhasil diSimpan.'
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