<form action="{{ url('/admin/dosen/edit/' . $dosen->akun->id_akun) }}" method="POST" id="form-edit-dosen">
    @csrf
    @method('PUT')

<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content shadow-sm rounded">

        <!-- Header Modal -->
        <div class="modal-header bg-primary text-white rounded-top">
            <h5 class="modal-title">Edit Dosen</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

            <div class="modal-body">
                <div class="container mt-4">

                    <div class="mb-3">
                        <label for="id_user" class="form-label">NIP</label>
                        <input type="text" name="id_user" id="id_user" class="form-control" 
                        value="{{ old('email', $dosen->id_user ?? $dosen->akun->id_user) }}" required />
                    </div>

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Dosen</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $dosen->nama) }}" required />
                    </div>

                    <div class="mb-3">
                        <label for="telepon" class="form-label">No. Telepon / HP</label>
                        <input type="text" name="telepon" id="telepon" class="form-control" value="{{ old('telepon', $dosen->telepon ?? '') }}" />
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control"
                               value="{{ old('tanggal_lahir', $dosen->tanggal_lahir ? date('Y-m-d', strtotime($dosen->tanggal_lahir)) : '') }}" />
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3">{{ old('alamat', $dosen->alamat ?? '') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                               value="{{ old('email', $dosen->email ?? $dosen->akun->email) }}" required />
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-warning" onclick="modalAction('{{ url('/admin/dosen/detail/' . $dosen->akun->id_akun) }}')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<!-- Script untuk validasi dan AJAX -->
<script>
    $(document).ready(function () {
        $("#form-edit-dosen").validate({
            rules: {
                nama: { required: true },
                nip: { required: true },
                email: { email: true }
            },
            messages: {
                nama: "Nama wajib diisi",
                nip: "NIP wajib diisi",
                email: "Masukkan email valid"
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
                                text: response.message || 'Data berhasil disimpan.'
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
                        let errors = xhr.responseJSON?.errors;
                        if (errors) {
                            let errorList = Object.values(errors).flat().join('<br>');
                            Swal.fire({
                                icon: 'error',
                                title: 'Validasi Gagal',
                                html: errorList
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan.'
                            });
                        }
                    }
                });

                return false;
            }
        });
    });
</script>