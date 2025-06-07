<link rel="stylesheet" href="{{ asset('template/assets/extensions/quill/quill.snow.css') }}">

<form action="{{ url('/admin/lowongan/edit/' . $lowongan->id_lowongan) }}" method="POST" id="form-edit">
    @csrf
   <div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content shadow-sm rounded">

        <div class="modal-header bg-primary text-white rounded-top">
            <h5 class="text-light">Edit Lowongan</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

            <div class="modal-body">
                <div class="container mt-4">
                    <div class="mb-3">
                        <label for="id_perusahaan" class="form-label">Perusahaan</label>
                        <select name="id_perusahaan" class="form-select" id="id_perusahaan" data-placeholder="Pilih Perusahaan" required>
                            <option value=""></option>
                            @foreach ($perusahaan as $item)
                                <option value="{{ $item->id_perusahaan }}" {{ $lowongan->id_perusahaan == $item->id_perusahaan ? 'selected' : '' }}>{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_bidang" class="form-label">Bidang</label>
                        <select name="id_bidang" class="form-select" id="id_bidang" data-placeholder="Pilih Bidang" required>
                            <option value=""></option>
                            @foreach ($bidang as $item)
                                <option value="{{ $item->id_bidang }}" {{ $lowongan->id_bidang == $item->id_bidang ? 'selected' : '' }}>{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $lowongan->nama }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Persyaratan</label>
                        <div id="snow">
                            {!! htmlspecialchars_decode($lowongan->persyaratan) !!}
                        </div>
                        <!-- Hidden Textarea untuk submit form -->
                        <textarea id="persyaratan" name="persyaratan" style="display: none;"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required>{{ $lowongan->deskripsi }}</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" onclick="modalAction('{{ url('/admin/lowongan/detail/' . $lowongan->id_lowongan) }}')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            </div>
        </div>
    </div>
</form>

<script src="{{ asset('template/assets/extensions/quill/quill.min.js') }}"></script>

<script>
$(document).ready(function () {
    // Inisialisasi Quill Editor
    const quill = new Quill('#snow', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link']
            ]
        }
    });

    // Isi awal dari database jika ada
    @if ($lowongan->persyaratan)
        quill.clipboard.dangerouslyPasteHTML(`{!! htmlspecialchars_decode($lowongan->persyaratan) !!}`);
    @endif

    // Sinkronkan konten Quill ke textarea sebelum validasi
    function syncQuillToTextarea() {
        const htmlContent = quill.root.innerHTML;
        $('#persyaratan').val(htmlContent);
    }

    // Validasi Form
    $("#form-edit").validate({
        rules: {
            id_perusahaan: { required: true },
            id_bidang: { required: true },
            nama: { required: true },
            persyaratan: { required: true },
            deskripsi: { required: true }
        },
        messages: {
            id_perusahaan: "Pilih perusahaan",
            id_bidang: "Pilih bidang magang",
            nama: "Nama magang wajib diisi",
            persyaratan: "Persyaratan magang wajib diisi",
            deskripsi: "Deskripsi magang wajib diisi"
        },
        submitHandler: function (form) {
            // Sinkronkan Quill ke textarea sebelum submit
            syncQuillToTextarea();

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
        }
    });
});
</script>
