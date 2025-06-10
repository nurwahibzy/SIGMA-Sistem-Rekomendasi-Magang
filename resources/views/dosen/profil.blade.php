@extends('layouts.tamplate')
@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>Profil Saya</h3>
    </div>
    <section class="section">
        <div class="position-sticky" style="top: 90px;">
            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow p-4 text-center">
                        <img src="{{ Storage::exists('public/profil/akun/' . Auth::user()->foto_path)
                            ? asset('storage/profil/akun/' . Auth::user()->foto_path)
                            : asset('template/assets/images/mhs.jpeg') }}"
                            alt="Foto Profil"
                            class="rounded-circle mx-auto d-block mb-3" width="100" height="100"
                            style="border: 5px solid blue;" />

                        <form class="text-start mt-3">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label">NIP</label>
                                <input type="text" class="form-control" id="nip" value="{{ Auth::user()->id_user ?? '-' }}" disabled>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" value="{{ Auth::user()->dosen->nama ?? '-' }}" disabled>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamat" value="{{ Auth::user()->dosen->alamat ?? '-' }}" disabled>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Telepon</label>
                                <input type="text" class="form-control" id="no_telepon" value="{{ Auth::user()->dosen->telepon ?? '-' }}" disabled>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" value="{{ Auth::user()->dosen->tanggal_lahir ?? '-' }}" disabled>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" value="{{ Auth::user()->dosen->email }}" disabled>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card p-5 bg-primary bg-opacity-10 border-0 mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="fw-bold text-primary mb-3">
                                    Lengkapi Informasi<br>
                                    <span>Profil Anda</span>
                                </h4>
                                <p class="text-muted">
                                    Tambahkan dan kelola keahlian untuk mendukung informasi akademik dan profesional Anda.
                                </p>
                            </div>
                            <div class="col-md-4 d-flex justify-content-center">
                                <img src="{{ asset('template/assets/images/magang.jpg') }}" alt="Foto Profil"
                                    class="rounded-circle shadow" style="width: 120px; height: 120px; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                    <div class="page-heading d-flex justify-content-end align-items-center mb-3">
                        <a href="{{ url('dosen/profil/edit') }}" id="btn-edit-profile" class="btn btn-primary">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                    </div>
                    <!-- Card Keahlian -->
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Keahlian</h5>
                        </div>
                        <div class="card-body">
                            <div id="keahlian-list">
                                <!-- Keahlian akan dimuat di sini -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@push('scripts')
<script>
$(document).ready(function() {
    // Load keahlian saat halaman dimuat
    loadKeahlian();
    
    // Load bidang untuk modal
    // loadBidang();

    // // Event listener untuk tombol tambah keahlian
    // $('#btn-add-keahlian').click(function() {
    //     resetModal();
    //     $('#modalKeahlianLabel').text('Tambah Keahlian');
    //     $('#modalKeahlian').modal('show');
    // });

    // // Event listener untuk form submit keahlian
    // $('#form-keahlian').submit(function(e) {
    //     e.preventDefault();
    //     saveKeahlian();
    // });

    // // Event delegation untuk tombol edit dan delete
    // $(document).on('click', '.btn-edit-keahlian', function() {
    //     var id = $(this).data('id');
    //     editKeahlian(id);
    // });

    // $(document).on('click', '.btn-delete-keahlian', function() {
    //     var id = $(this).data('id');
    //     if (confirm('Apakah Anda yakin ingin menghapus keahlian ini?')) {
    //         deleteKeahlian(id);
    //     }
    // });
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

// function loadBidang() {
//     $.ajax({
//         url: "{{ url('dosen/profil/edit/keahlian') }}",
//         type: 'GET',
//         success: function(response) {
//             var options = '<option value="">Pilih Bidang</option>';
//             $.each(response, function(index, bidang) {
//                 options += '<option value="' + bidang.id_bidang + '">' + bidang.nama + '</option>';
//             });
//             $('#id_bidang').html(options);
//         },
//         error: function() {
//             console.log('Gagal memuat data bidang');
//         }
//     });
// }

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
            // html += '<button class="btn btn-outline-primary btn-edit-keahlian" data-id="' + item.id_keahlian_dosen + '">';
            // // html += '<i class="bi bi-pencil"></i>';
            // // html += '</button>';
            // html += '<button class="btn btn-outline-danger btn-delete-keahlian" data-id="' + item.id_keahlian_dosen + '">';
            // html += '<i class="bi bi-trash"></i>';
            // html += '</button>';
            html += '</div>';
            html += '</div>';
        });
    } else {
        html = '<p class="text-muted">Belum ada keahlian yang ditambahkan</p>';
    }
    $('#keahlian-list').html(html);
}

// function resetModal() {
//     $('#form-keahlian')[0].reset();
//     $('#id_keahlian_dosen').val('');
// }

// function saveKeahlian() {
//     var id = $('#id_keahlian_dosen').val();
//     var url = id ? "{{ url('dosen/profil/edit/keahlian') }}/" + id : "{{ url('dosen/profil/edit/keahlian') }}";
//     var type = id ? 'PUT' : 'POST';
    
//     $.ajax({
//         url: url,
//         type: type,
//         data: $('#form-keahlian').serialize(),
//         success: function(response) {
//             if (response.success) {
//                 $('#modalKeahlian').modal('hide');
//                 loadKeahlian();
//                 alert('Keahlian berhasil disimpan');
//             }
//         },
//         error: function() {
//             alert('Terjadi kesalahan saat menyimpan keahlian');
//         }
//     });
// }

// function editKeahlian(id) {
//     $.ajax({
//         url: "{{ url('dosen/profil/edit/keahlian') }}/" + id,
//         type: 'GET',
//         success: function(response) {
//             $('#id_keahlian_dosen').val(response.keahlian.id_keahlian_dosen);
//             $('#id_bidang').val(response.keahlian.id_bidang);
//             $('#keahlian').val(response.keahlian.keahlian);
//             $('#modalKeahlianLabel').text('Edit Keahlian');
//             $('#modalKeahlian').modal('show');
//         },
//         error: function() {
//             alert('Gagal memuat data keahlian');
//         }
//     });
// }

// function deleteKeahlian(id) {
//     $.ajax({
//         url: "{{ url('dosen/profil/edit/keahlian') }}/" + id,
//         type: 'POST', // Gunakan POST dengan _method DELETE
//         data: {
//             _token: $('meta[name="csrf-token"]').attr('content'),
//             _method: 'DELETE'
//         },
//         success: function(response) {
//             if (response.success) {
//                 loadKeahlian();
//                 alert('Keahlian berhasil dihapus');
//             } else {
//                 alert('Gagal menghapus keahlian: ' + (response.message || 'Unknown error'));
//             }
//         },
//         error: function(xhr, status, error) {
//             console.log('Error:', xhr.responseText);
//             alert('Terjadi kesalahan saat menghapus keahlian: ' + error);
//         }
//     });
// }
</script>
@endpush
@endsection
