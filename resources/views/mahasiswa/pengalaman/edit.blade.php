<form action="{{ url('/mahasiswa/profil/edit/pengalaman/edit/' . $pengalaman->id_pengalaman  ) }}" method="POST" id="form-edit-pengalaman" style="height: 100%!important;
    display: flex;
    align-items: center;">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document" style="width:100%">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Pengalaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mt-4">
                    <div class="tab-content" id="detailTabContent">
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Pengalaman</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required>{{ $pengalaman->deskripsi }}</textarea>
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
        $("#form-edit-pengalaman").validate({
            rules: {
                deskripsi: { required: true },
            },
            messages: {
                deskripsi: "Pengalaman wajib diisi",
            },
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