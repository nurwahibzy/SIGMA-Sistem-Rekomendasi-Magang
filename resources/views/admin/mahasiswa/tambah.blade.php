<div class="modal fade" id="modalTambahMahasiswa" tabindex="-1" aria-labelledby="modalTambahMahasiswaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="formTambahMahasiswa">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahMahasiswaLabel">Tambah Program Studi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="jurusan" class="form-label">Jurusan</label>
                        <input type="text" name="nama_jurusan" id="jurusan" class="form-control" required>
                        <span class="text-danger error-text nama_jurusan_err"></span>
                    </div>
                    <div class="mb-3">
                        <label for="prodi" class="form-label">Nama Prodi</label>
                        <input type="text" name="nama_prodi" id="prodi" class="form-control" required>
                        <span class="text-danger error-text nama_prodi_err"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {

    $('#formTambahMahasiswa').validate({
        rules: {
            nama_jurusan: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
            nama_prodi: {
                required: true,
                minlength: 3,
                maxlength: 50,
            }
        },
        messages: {
            nama_jurusan: {
                required: "Jurusan wajib diisi",
                minlength: "Minimal 3 karakter",
                maxlength: "Maksimal 50 karakter"
            },
            nama_prodi: {
                required: "Nama Prodi wajib diisi",
                minlength: "Minimal 3 karakter",
                maxlength: "Maksimal 50 karakter"
            }
        },
        errorElement: 'span',
        errorClass: 'text-danger',
        errorPlacement: function(error, element) {
            let name = element.attr('name');
            $('.' + name + '_err').html(error);
        },
        submitHandler: function(form) {
            // Hapus error text dulu
            $('.error-text').html('');

            // Ambil data form
            var formData = $(form).serialize();

            $.ajax({
                url: '{{ url("admin/mahasiswa/tambah") }}', // ganti sesuai route backend
                method: 'POST',
                data: formData,
                success: function(response) {
                    if(response.status === 'success') {
                        $('#modalTambahMahasiswa').modal('hide');
                        $('#formTambahMahasiswa')[0].reset();
                        alert('Data berhasil disimpan!');

                        if(typeof tableProdi !== 'undefined') {
                            tableProdi.ajax.reload(null, false);
                        }
                    } else if(response.status === 'error' && response.errors) {
                        // Tampilkan error validasi dari backend
                        $.each(response.errors, function(key, val) {
                            $('.' + key + '_err').html(val[0]);
                        });
                    } else {
                        alert('Terjadi kesalahan: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan pada server.');
                }
            });
            return false; // cegah submit form normal
        }
    });

});
</script>
@endpush
