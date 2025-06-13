<form action="{{ url('/admin/periode/edit/' . $periode->id_periode) }}" method="POST" id="form-edit">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white rounded-top">
                <h5 class="text-light" id="exampleModalLabel">Edit Periode</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mt-4">
                    <div class="tab-content" id="detailTabContent">
                        <div class="mb-3">
                            <label for="id_perusahaan" class="form-label">Perusahaan</label>
                            <select name="id_perusahaan" class="form-select" id="id_perusahaan"
                                data-placeholder="Pilih Perusahaan">
                                <option value=""></option>
                                @foreach ($perusahaan as $item)
                                    <option value="{{ $item->id_perusahaan }}" {{ $periode->lowongan_magang->id_perusahaan == $item->id_perusahaan ? 'selected' : '' }}>
                                        {{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_bidang" class="form-label">Bidang</label>
                            <select name="id_bidang" class="form-select" id="id_bidang" data-placeholder="Pilih Bidang">
                                <option value=""></option>
                                @foreach ($bidang as $item)
                                    <option value="{{ $item->id_bidang }}" {{ $periode->lowongan_magang->id_bidang == $item->id_bidang ? 'selected' : '' }}>{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_lowongan" class="form-label">Lowongan</label>
                            <select name="id_lowongan" class="form-select" id="id_lowongan"
                                data-placeholder="Pilih Lowongan" required>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Periode</label>
                            <input type="text" class="form-control" id="nama" name="nama" required
                                value="{{ $periode->nama }}">
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_mulai_modal" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="tanggal_mulai_modal" name="tanggal_mulai"
                                required min="{{ $now }}" value="{{ $periode->tanggal_mulai->format('Y-m-d') }}" max="{{ $periode->tanggal_selesai->copy()->subDay()->format('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_selesai_modal" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="tanggal_selesai_modal" name="tanggal_selesai"
                                required min="{{ $tomorrow }}" value="{{ $periode->tanggal_selesai->format('Y-m-d') }}">
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

        $.get('{{ url('admin/periode/lowongan/' . $periode->lowongan_magang->id_perusahaan . '/' . $periode->lowongan_magang->id_bidang) }}', function (data) {
            const selectedId = {{ $periode->id_lowongan }}
            data.forEach(function (lowongan) {
                $('#id_lowongan').append($('<option>', {
                    value: lowongan.id_lowongan,
                    text: lowongan.nama,
                    selected: lowongan.id_lowongan === selectedId
                }));
            });
        });

        $('#id_perusahaan').on('change', function () {
            const perusahaanId = $(this).val();
            const BidangId = $('#id_bidang').val()

            if (BidangId != '') {
                $('#id_lowongan').empty().append('<option value="">Pilih Lowongan</option>')
                $.get(`{{ url('admin/periode/lowongan') }}/` + perusahaanId + '/' + BidangId, function (data) {
                    data.forEach(function (lowongan) {
                        $('#id_lowongan').append($('<option>', {
                            value: lowongan.id_lowongan,
                            text: lowongan.nama
                        }));
                    });
                });
            }
        });

        $('#id_bidang').on('change', function () {
            const BidangId = $(this).val();
            const perusahaanId = $('#id_perusahaan').val()

            if (perusahaanId != '') {
                $('#id_lowongan').empty().append('<option value="">Pilih Lowongan</option>')
                $.get(`{{ url('admin/periode/lowongan') }}/` + perusahaanId + '/' + BidangId, function (data) {
                    data.forEach(function (lowongan) {
                        $('#id_lowongan').append($('<option>', {
                            value: lowongan.id_lowongan,
                            text: lowongan.nama
                        }));
                    });
                });
            }
        });

        $('#tanggal_mulai_modal').on('change', function () {
            const tanggalMulai = new Date($(this).val());
            tanggalMulai.setDate(tanggalMulai.getDate() + 1);

            const minTanggalSelesai = tanggalMulai.toISOString().split('T')[0];
            $('#tanggal_selesai_modal').attr('min', minTanggalSelesai);
        });

        $('#tanggal_selesai_modal').on('change', function () {
            const tanggalSelesai = new Date($(this).val());
            tanggalSelesai.setDate(tanggalSelesai.getDate() - 1);

            const maxTanggalMulai = tanggalSelesai.toISOString().split('T')[0];
            $('#tanggal_mulai_modal').attr('max', maxTanggalMulai);
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