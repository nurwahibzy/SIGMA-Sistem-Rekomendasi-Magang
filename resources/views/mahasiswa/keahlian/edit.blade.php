<form action="{{ url('/mahasiswa/profil/edit/keahlian/edit/' . $data->pilihan_terakhir->id_keahlian_mahasiswa ) }}" method="POST" id="form-edit-keahlian" style="height: 100%!important;
    display: flex;
    align-items: center;">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document" style="width:100%">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Keahlian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mt-4">
                    <div class="tab-content" id="detailTabContent">
                        <div class="mb-3">
                            <label for="id_bidang" class="form-label">Bidang</label>
                            <select name="id_bidang" id="id_bidang" class="form-select" required>
                                <option value="">Pilih Bidang</option>
                                @foreach ($data->bidang as $bidang)
                                    <option value="{{ $bidang->id_bidang }}" {{ $bidang->id_bidang == $data->pilihan_terakhir->id_bidang ? 'selected' : '' }}>
                                        {{ $bidang->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="prioritas" class="form-label">Prioritas</label>
                            <select name="prioritas" id="prioritas" class="form-select" required>
                                <option value="">Pilih Prioritas</option>
                                @for ($i = 1; $i <= $data->prioritas; $i++)
                                    <option value="{{ $i }}" {{ $i == $data->pilihan_terakhir->prioritas ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="keahlian" class="form-label">Keahlian</label>
                            <textarea class="form-control" id="keahlian" name="keahlian" rows="3"
                                required>{{ $data->pilihan_terakhir->keahlian }}</textarea>
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
        $("#form-edit-keahlian").validate({
            rules: {
                id_bidang: { required: true },
                prioritas: { required: true },
                keahlian: { required: true },
            },
            messages: {
                id_bidang: "Pilih bidang",
                prioritas: "Pilih prioritas",
                keahlian: "keahlian wajib diisi",
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

                // return false;
            }
        });
    });
</script>