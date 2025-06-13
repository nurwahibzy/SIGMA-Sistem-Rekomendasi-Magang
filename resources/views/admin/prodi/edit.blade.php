<form action="{{ url('/admin/prodi/edit/' . $prodi->id_prodi) }}" method="POST" id="form-edit">
    @csrf
   <div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content shadow-sm rounded">

        <div class="modal-header bg-primary text-white rounded-top">
            <h5 class="text-light">Edit Prodi</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

            <div class="modal-body">
                <div class="container mt-4">
                    <div class="tab-content" id="detailTabContent">
                        <div class="mb-3">
                            <label for="nama_jurusan" class="form-label">Jurusan</label>
                            <input type="text" class="form-control" id="nama_jurusan" name="nama_jurusan" readonly value="Teknologi Informasi">
                        </div>
                        <div class="mb-3">
                            <label for="nama_prodi" class="form-label">Program Studi</label>
                            <input type="text" class="form-control" id="nama_prodi" name="nama_prodi" required value="{{ $prodi->nama_prodi }}"> 
                        </div>
                        <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="">Pilih Status</option>
                                    <option value="aktif" {{ $prodi->status == 'aktif' ? 'selected' : ''  }}>
                                        aktif
                                    </option>
                                    <option value="nonaktif" {{ $prodi->status == 'nonaktif' ? 'selected' : ''  }}>
                                        nonaktif</option>
                                </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-bs-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {
        $("#form-edit").validate({
            rules: {
                nama_jurusan: { required: true },
                nama_prodi: { required: true },
                status: { required: true },
            },
            messages: {
                nama_jurusan: "Jurusan wajib diisi",
                nama_prodi: "Program studi wajib diisi",
                status: "Status wajib diisi",
            },
            errorElement: 'div',
            errorClass: 'invalid-feedback',
            validClass: 'is-valid',
            errorClass: 'is-invalid',
            submitHandler: function (form) {
                const formData = new FormData(form);

                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Sedang memproses data',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

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

                // return false;
            }
        });
    });
</script>