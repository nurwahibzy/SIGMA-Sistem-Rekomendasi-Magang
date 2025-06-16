<form action="{{ url('/admin/mahasiswa/tambah/excel') }}" method="POST" id="form-tambah-excel">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white rounded-top">
                <h5 class="text-light">Tambah Mahasiswa Excel</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mt-4">
                    <a href="{{ url('/admin/mahasiswa/unduh/excel') }}" class="btn btn-primary"><i
                            class="fa fa-file-excel"></i> Format </a>
                </div>
                <div class="container mt-4 alert alert-warning">
                    <p>Perhatian:</p>
                    <ul>
                        <li>NIM: Harus angka dan tidak boleh sama</li>
                        <li>Nomor Telepon: Minimal 8 angka dan tidak boleh sama</li>
                        <li>Tanggal lahir: Harus kurang dari sama dengan tanggal ini</li>
                        <li>Gender: l (laki-laki), p (perempuan)</li>
                        <li>Email: Tidak boleh sama</li>
                    </ul>
                </div>
                <div class="container mt-4">
                    <p>Contoh: </p>
                    <ul>
                        <li>NIM: 2341720172</li>
                        <li>Nama: ACHMAD MAULANA HAMZAH</li>
                        <li>Alamat: Gang Ahmad Dahlan No. 85
                            Bengkulu, Sumatera Barat 21915</li>
                        <li>Nomor Telepon: 0857153140</li>
                        <li>Tanggal Lahir: 2004-10-16</li>
                        <li>Gender: l</li>
                        <li>Email: charellino.kalingga.sadewo@example.com</li>
                    </ul>
                </div>
                <div class="container mt-4">
                    <div class="d-flex justify-content-between">
                        <div class="w-50 me-2">
                            <div>
                                <label for="id_prodi" class="form-label">Program Studi</label>
                                <select name="id_prodi" class="form-select" id="id_prodi"
                                    data-placeholder="Pilih Program Studi" required>
                                    <option value="">Pilih Prodi</option>
                                    @foreach ($prodi as $item)
                                        <option value="{{ $item->id_prodi }}">{{ $item->nama_prodi }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="w-50 ms-2">
                            <label for="file_excel" class="form-label">File Excel</label>
                            <input type="file" name="file_excel" id="file_excel" class="form-control"
                                accept=".xlsx,.xls" required />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-bs-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
</form>
<script>
    $(document).ready(function () {
        $("#form-tambah-excel").validate({
            rules: {
                file_excel : { required: true },
                id_prodi: { required: true },
            },
            messages: {
                file_excel : "File wajib diisi",
                id_prodi: "Prodi wajib diisi",
            },
            errorElement: 'div',
            errorClass: 'invalid-feedback',
            validClass: 'is-valid',
            errorClass: 'is-invalid',
            submitHandler: function (form) {

                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Sedang memproses data',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

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

                // return false;
            }
        });
    });
</script>