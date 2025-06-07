@extends('layouts.tamplate')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>Profil Saya</h3>
        <a href="{{ url('dosen/profil/') }}" id="btn-edit-profile" class="btn-edit-section btn btn-danger">
            <i class="bi bi-box-arrow-left"></i> Kembali
        </a>
    </div>
    <section class="section">
        <div class="row">

            <div class="col-md-4">
                <div class="position-sticky" style="top: 90px;">
                    <div class="card p-4 text-center">

                        <form id="form-tambah" action="{{ url('/dosen/profil/edit/') }}" method="POST"
                            class="text-start mt-3">
                            @csrf
                            <div class="text-center mb-3">
                                <label for="file" style="cursor: pointer;">

                                        <img id="preview" src="{{ Storage::exists('public/profil/akun/' . Auth::user()->foto_path)
                    ? asset('storage/profil/akun/' . Auth::user()->foto_path)
                    : asset('template/assets/images/mhs.jpeg') }}" alt="Foto Profil" alt="Profile Picture"
                            class="rounded-circle mx-auto d-block mb-3" width="100" height="100"
                            style="border: 5px solid blue;" />
                                </label>
                                <input type="file" id="file" name="file" accept="image/*" onchange="previewImage(event)"
                                    style="display: none;">
                            </div>
                            <input type="text" class="form-control" id="nip" name="id_user" required
                        value="{{ Auth::user()->id_user ?? '-' }}" hidden>
                            <div class="mb-2">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" required
                                    value="{{ Auth::user()->dosen->nama ?? '-' }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" value="">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah.</small>
                            </div>
                            <div class="mb-2">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3"
                                    required>{{ Auth::user()->dosen->alamat ?? '-' }}</textarea>
                            </div>
                            <div class="mb-2">
                                <label for="telepon" class="form-label">Telepon</label>
                                <input type="text" class="form-control" id="telepon" name="telepon" required
                                    value="{{ Auth::user()->dosen->telepon ?? '-' }}">
                            </div>
                            <div class="mb-2">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required
                                    value="{{ Auth::user()->dosen->tanggal_lahir ?? '-' }}">
                            </div>
                            <div class="mb-2">

                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                    value="{{ Auth::user()->dosen->email }}">
                            </div>
                            <button type="submit" id="btn-edit-profile" class="btn btn-primary mt-2 w-100">
                                <i class="bi bi-pencil-square"></i> Simpan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
                <div class="col-md-8">
                    <!-- Card Keahlian Dosen -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Keahlian</h5>
                            <button type="button" class="btn btn-sm btn-primary" id="btn-add-keahlian">
                                <i class="bi bi-plus-circle"></i> Tambah
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="keahlian-list">
                                <!-- Keahlian akan dimuat di sini -->
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </section>

    <!-- Modal Tambah/Edit Keahlian -->
    <div class="modal fade" id="modalKeahlian" tabindex="-1" aria-labelledby="modalKeahlianLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKeahlianLabel">Tambah Keahlian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-keahlian">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="id_keahlian_dosen" name="id_keahlian_dosen">
                        <div class="form-group mb-3">
                            <label for="id_bidang" class="form-label">Bidang</label>
                            <select class="form-select" id="id_bidang" name="id_bidang" required>
                                <option value="">Pilih Bidang</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="keahlian" class="form-label">Keahlian</label>
                            <textarea class="form-control" id="keahlian" name="keahlian" 
                                placeholder="Masukkan keahlian" row="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const preview = document.getElementById('preview');
            preview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
<script>
    $(document).ready(function () {
        $("#form-tambah").validate({
            rules: {
                id_user: { required: true, digits: true },
                nama: { required: true },
                alamat: { required: true },
                telepon: { required: true, digits: true },
                tanggal_lahir: { required: true, date: true },
                email: { required: true, email: true }
            },
            messages: {
                id_user: "ID User wajib diisi",
                nama: "Nama wajib diisi",
                alamat: "Alamat wajib diisi",
                telepon: "Nomor telepon wajib diisi dan numerik",
                tanggal_lahir: "Tanggal lahir wajib diisi",
                email: "Email wajib diisi dan harus valid"
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
                                window.location.href = '{{ url('dosen/profil/edit') }}';
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
<script>
$(document).ready(function() {
    // Load keahlian saat halaman dimuat
    loadKeahlian();
    
    // Load bidang untuk modal
    loadBidang();

    // Event listener untuk tombol tambah keahlian
    $('#btn-add-keahlian').click(function() {
        resetModal();
        $('#modalKeahlianLabel').text('Tambah Keahlian');
        $('#modalKeahlian').modal('show');
    });

    // Event listener untuk form submit keahlian
    $('#form-keahlian').submit(function(e) {
        e.preventDefault();
        saveKeahlian();
    });

    // Event delegation untuk tombol edit dan delete
    $(document).on('click', '.btn-edit-keahlian', function() {
        var id = $(this).data('id');
        editKeahlian(id);
    });

    $(document).on('click', '.btn-delete-keahlian', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus keahlian ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteKeahlian(id);
            }
        });
    });
});

function loadKeahlian() {
    $.ajax({
        url: "{{ url('dosen/profil/edit/keahlian/list') }}",
        type: 'GET',
        success: function(response) {
            displayKeahlian(response);
        },
        error: function() {
            $('#keahlian-list').html('<p class="text-muted">Gagal memuat data keahlian</p>');
        }
    });
}

function loadBidang() {
    $.ajax({
        url: "{{ url('dosen/profil/edit/keahlian') }}",
        type: 'GET',
        success: function(response) {
            var options = '<option value="">Pilih Bidang</option>';
            $.each(response, function(index, bidang) {
                options += '<option value="' + bidang.id_bidang + '">' + bidang.nama + '</option>';
            });
            $('#id_bidang').html(options);
        },
        error: function() {
            console.log('Gagal memuat data bidang');
        }
    });
}

function displayKeahlian(keahlianList) {
    var html = '';
    if (keahlianList.length > 0) {
        $.each(keahlianList, function(index, item) {
            html += '<div class="d-flex justify-content-between align-items-start mb-2 p-2 border rounded">';
            html += '<div>';
            html += '<strong>' + item.keahlian + '</strong><br>';
            html += '<small class="text-muted">' + item.bidang.nama + '</small>';
            html += '</div>';
            html += '<div class="btn-group btn-group-sm">';
            html += '<button class="btn btn-primary me-2 btn-edit-keahlian" data-id="' + item.id_keahlian_dosen + '">';
            html += '<i class="bi bi-pencil"></i>';
            html += '</button>';
            html += '<button class="btn btn-danger btn-delete-keahlian" data-id="' + item.id_keahlian_dosen + '">';
            html += '<i class="bi bi-trash"></i>';
            html += '</button>';
            html += '</div>';
            html += '</div>';
        });
    } else {
        html = '<p class="text-muted">Belum ada keahlian yang ditambahkan</p>';
    }
    $('#keahlian-list').html(html);
}

function resetModal() {
    $('#form-keahlian')[0].reset();
    $('#id_keahlian_dosen').val('');
}

function saveKeahlian() {
    var id = $('#id_keahlian_dosen').val();
    var url = id ? "{{ url('dosen/profil/edit/keahlian') }}/" + id : "{{ url('dosen/profil/edit/keahlian') }}";
    var type = id ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        type: type,
        data: $('#form-keahlian').serialize(),
        success: function(response) {
            if (response.success) {
                $('#modalKeahlian').modal('hide');
                loadKeahlian();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Keahlian berhasil disimpan.'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: response.message || 'Terjadi kesalahan saat menyimpan keahlian.'
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Terjadi kesalahan saat menyimpan keahlian.'
            });
        }
    });
}

function editKeahlian(id) {
    $.ajax({
        url: "{{ url('dosen/profil/edit/keahlian') }}/" + id,
        type: 'GET',
        success: function(response) {
            $('#id_keahlian_dosen').val(response.keahlian.id_keahlian_dosen);
            $('#id_bidang').val(response.keahlian.id_bidang);
            $('#keahlian').val(response.keahlian.keahlian);
            $('#modalKeahlianLabel').text('Edit Keahlian');
            $('#modalKeahlian').modal('show');
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Gagal memuat data keahlian.'
            });
        }
    });
}

function deleteKeahlian(id) {
    $.ajax({
        url: "{{ url('dosen/profil/edit/keahlian') }}/" + id,
        type: 'POST', // Gunakan POST dengan _method DELETE
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: 'DELETE'
        },
        success: function(response) {
            if (response.success) {
                loadKeahlian();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Keahlian berhasil dihapus.'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: response.message || 'Gagal menghapus keahlian.'
                });
            }
        },
        error: function(xhr, status, error) {
            console.log('Error:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Terjadi kesalahan saat menghapus keahlian: ' + error
            });
        }
    });
}
</script>