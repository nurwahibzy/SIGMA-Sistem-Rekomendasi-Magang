<form action="{{ url('/admin/periode/edit/' . $periode->id_periode) }}" method="POST" id="form-edit">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Periode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mt-4">
                    <div class="tab-content" id="detailTabContent">
                        <div class="mb-3">
                            <label for="id_lowongan" class="form-label">Lowongan</label>
                            <select name="id_lowongan" id="id_lowongan" class="form-control" required>
                                <option value="">Pilih Lowongan</option>
                                @foreach ($lowongan as $item)
                                    <option value="{{ $item->id_lowongan }}" {{ $periode->id_lowongan == $item->id_lowongan ? 'selected' : '' }}>
                                        {{ $item->perusahaan->nama . ' - ' . $item->nama . ' - ' . $item->bidang->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required
                                value="{{ $periode->nama }}">
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required
                                min="{{ $now }}" value="{{ $periode->tanggal_mulai->format('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required
                                min="{{ $tomorrow }}" value="{{ $periode->tanggal_selesai->format('Y-m-d') }}">
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
        $('#tanggal_mulai').on('change', function () {
            const tanggalMulai = new Date($(this).val());
            tanggalMulai.setDate(tanggalMulai.getDate() + 1);

            const minTanggalSelesai = tanggalMulai.toISOString().split('T')[0];
            $('#tanggal_selesai').attr('min', minTanggalSelesai);
        });

        $("#form-edit").validate({
            rules: {
                id_lowongan: { required: true },
                nama: { required: true },
                tanggal_mulai: { required: true, date: true },
                tanggal_selesai: { required: true, date: true }
            },
            messages: {
                id_lowongan: "Pilih lowongan",
                nama: "Nama periode wajib diisi",
                tanggal_mulai: "Tanggal mulai wajib diisi",
                tanggal_selesai: "Tanggal selesai wajib diisi"
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