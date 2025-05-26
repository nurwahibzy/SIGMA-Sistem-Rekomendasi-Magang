<!-- PREFERENSI LOKASI SECTION -->
<form id="form-preferensi-lokasi" method="POST"
    action="{{ url('/mahasiswa/profil/edit/preferensi/lokasi/' . $preferensi_lokasi->id_preferensi_lokasi) }}">
    @csrf
    <div class="section-wrapper mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="fw-bold mb-0">Preferensi Lokasi</h5>
            <button type="submit" class="btn btn-outline-success">
                <i class="bi bi-save"></i> Simpan
            </button>
        </div>

        <p class="text-muted mb-3">Preferensi lokasi magang.</p>

        @if (!empty($preferensi_lokasi))
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <p class="mb-1 text-muted">Provinsi: {{ $preferensi_lokasi->provinsi }}</p>
                            <select name="nama_provinsi" id="nama_provinsi" class="form-select">
                                <option value="">Perbarui Provinsi</option>
                            </select>
                            <input type="hidden" name="provinsi" id="provinsi" value="{{ $preferensi_lokasi->provinsi }}">
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Daerah: {{ $preferensi_lokasi->daerah }}</p>
                            <select name="nama_daerah" id="nama_daerah" class="form-select">
                                <option value="">Perbarui Daerah</option>
                            </select>
                            <input type="hidden" name="daerah" id="daerah" value="{{ $preferensi_lokasi->daerah }}">
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</form>
<script>
    $(document).ready(function () {
        let dataProvinsi = {};
        let dataDaerah = {};

        $.get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json', function (data) {
            data.forEach(function (prov) {
                dataProvinsi[prov.id] = prov.name;
                $('#nama_provinsi').append($('<option>', {
                    value: prov.id,
                    text: prov.name
                }));
            });
        });

        $('#nama_provinsi').on('change', function () {
            const provId = $(this).val();
            $('#provinsi').val(dataProvinsi[provId] || '');
            $('#nama_daerah').empty().append('<option value="">Perbarui Daerah</option>');
            $('#daerah').val('');

            if (provId) {
                $.get(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provId}.json`, function (data) {
                    data.forEach(function (daerah) {
                        dataDaerah[daerah.id] = daerah.name;
                        $('#nama_daerah').append($('<option>', {
                            value: daerah.id,
                            text: daerah.name
                        }));
                    });
                });
            }
        });

        $('#nama_daerah').on('change', function () {
            const daerahId = $(this).val();
            $('#daerah').val(dataDaerah[daerahId] || '');
        });

        $("#form-edit").validate({
            rules: {
                provinsi: { required: true },
                daerah: { required: true }
            },
            messages: {
                provinsi: "Pilih provinsi",
                daerah: "Pilih daerah"
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