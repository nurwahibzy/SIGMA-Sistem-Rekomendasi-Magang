<form action="{{ url('/admin/perusahaan/tambah') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Perusahaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mt-4">
                    <ul class="nav nav-tabs mb-3" id="detailTab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#Profil">Profil</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#Data">Data</a></li>
                    </ul>

                    <div class="tab-content" id="detailTabContent">
                        <div class="tab-pane fade show active" id="Profil" role="tabpanel">
                            <div class="mb-3">
                                <label for="file" class="form-label">Logo</label>
                                <input type="file" class="form-control" id="file" name="file" accept=".jpg,.jpeg,.png" required>
                            </div>
                            <div class="mb-3">
                                <label for="id_jenis" class="form-label">Jenis Perusahaan</label>
                                <select name="id_jenis" id="id_jenis" class="form-control" required>
                                    <option value="">Pilih Jenis</option>
                                    @foreach ($jenis as $item)
                                        <option value="{{ $item->id_jenis }}">{{ $item->jenis }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Perusahaan</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="Data" role="tabpanel">
                            <div class="mb-3">
                                <label for="telepon" class="form-label">Telepon</label>
                                <input type="text" class="form-control" id="telepon" name="telepon" required>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="nama_provinsi" class="form-label">Provinsi</label>
                                <select name="nama_provinsi" id="nama_provinsi" class="form-control" required>
                                    <option value="">Pilih Provinsi</option>
                                </select>
                                <input type="hidden" name="provinsi" id="provinsi">
                            </div>

                            <div class="mb-3">
                                <label for="nama_daerah" class="form-label">Daerah</label>
                                <select name="nama_daerah" id="nama_daerah" class="form-control" required>
                                    <option value="">Pilih Daerah</option>
                                </select>
                                <input type="hidden" name="daerah" id="daerah">
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
        $('#nama_daerah').empty().append('<option value="">Pilih Daerah</option>');
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

    $("#form-tambah").validate({
        rules: {
            id_jenis: { required: true },
            nama: { required: true },
            telepon: {
                required: true,
                digits: true,
                minlength: 8
            },
            provinsi: { required: true },
            daerah: { required: true }
        },
        messages: {
            id_jenis: "Pilih jenis perusahaan",
            nama: "Nama perusahaan wajib diisi",
            telepon: {
                required: "Telepon wajib diisi",
                digits: "Hanya angka yang diperbolehkan",
                minlength: "Minimal 8 digit"
            },
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
