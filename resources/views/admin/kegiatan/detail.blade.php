<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form id="form-status" method="POST" action="{{ url('admin/kegiatan/edit/' . $magang->id_magang) }}">
            @csrf
            <div class="modal-header bg-primary text-white rounded-top">
                            <h5 class="modal-title">Detail Kegiatan</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
            <div class="modal-body">
                <div class="container mt-4">
                    <ul class="nav nav-tabs mb-3" id="detailTab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#magang">Info
                                Magang</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#mahasiswa">Profil
                                Mahasiswa</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#keahlian">Keahlian</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#pengalaman">Pengalaman</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#periode">Periode &
                                Lowongan</a></li>
                    </ul>

                    <div class="tab-content" id="detailTabContent">
                        <div class="tab-pane fade show active" id="magang" role="tabpanel">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Status</th>
                                    <td>{{ $magang->status }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pengajuan</th>
                                    <td>{{ $magang->tanggal_pengajuan }}</td>
                                </tr>
                                <tr>
                                    <th>Dosen Pembimbing</th>
                                    <td>
                                        @if($magang->dosen)
                                            {{ $magang->dosen->nama }}
                                        @elseif($magang->status == 'ditolak')
                                        -
                                        @else
                                            <select name="id_dosen" class="form-select" id="input-dosen" required>
                                                <option value="">Pilih Dosen Pembimbing</option>
                                                @foreach($dosen as $d)
                                                    <option value="{{ $d['id_dosen'] }}">{{ $d['nama'] }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="tab-pane fade" id="mahasiswa" role="tabpanel">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Nama</th>
                                    <td>{{ $magang->mahasiswa->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $magang->mahasiswa->email }}</td>
                                </tr>
                                <tr>
                                    <th>Telepon</th>
                                    <td>{{ $magang->mahasiswa->telepon }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $magang->mahasiswa->alamat }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Lahir</th>
                                    <td>{{ $magang->mahasiswa->tanggal_lahir }}</td>
                                </tr>
                                <tr>
                                    <th>Status Akun</th>
                                    <td>{{ $magang->mahasiswa->akun->status }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="tab-pane fade" id="keahlian" role="tabpanel">
                            <ul class="list-group">
                                @foreach($magang->mahasiswa->keahlian_mahasiswa as $keahlian)
                                    <li class="list-group-item"><strong>Prioritas {{ $keahlian->prioritas }}:</strong>
                                        {{ $keahlian->keahlian }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="tab-pane fade" id="pengalaman" role="tabpanel">
                            <ul class="list-group">
                                @foreach($magang->mahasiswa->pengalaman as $pengalaman)
                                    <li class="list-group-item">{{ $pengalaman->deskripsi }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="tab-pane fade" id="periode" role="tabpanel">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Periode</th>
                                    <td>{{ $magang->periode_magang->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Mulai</th>
                                    <td>{{ $magang->periode_magang->tanggal_mulai }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Selesai</th>
                                    <td>{{ $magang->periode_magang->tanggal_selesai }}</td>
                                </tr>
                                <tr>
                                    <th>Lowongan</th>
                                    <td>{{ $magang->periode_magang->lowongan_magang->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Deskripsi</th>
                                    <td>{{ $magang->periode_magang->lowongan_magang->deskripsi }}</td>
                                </tr>
                                <tr>
                                    <th>Persyaratan</th>
                                    <td>{{ $magang->periode_magang->lowongan_magang->persyaratan }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer flex-column align-items-stretch gap-3">
                @if(count($activeButton))
                    <div class="w-100">
                        <label class="form-label fw-bold mb-2">Status:</label>
                        <div class="d-flex flex-wrap gap-3 ms-2">
                            @foreach ($activeButton as $status)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" value="{{ $status }}"
                                        id="status_{{ $status }}">
                                    <label class="form-check-label" for="status_{{ $status }}">{{ ucfirst($status) }}</label>
                                </div>
                            @endforeach
                        </div>
                        <div id="status-error" class="text-danger mt-2 d-none">Silakan pilih salah satu status.</div>
                    </div>
                @endif
                <div class="w-100 d-flex justify-content-end gap-2 mt-2">
                    <button type="button" class="btn btn-danger" id="btn-hapus">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                    <button type="submit" class="btn btn-info"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $('input[name="status"]').on('change', function () {
        const selectedStatus = $(this).val();
        if (selectedStatus === 'ditolak') {
            $('#input-dosen').prop('disabled', true).val('');
        } else {
            $('#input-dosen').prop('disabled', false);
        }
    });

    $('input[name="status"]:checked').trigger('change');
    $(document).ready(function () {
        $("#form-status").validate({
            rules: {
                status: { required: true },
                @if(!$magang->dosen)
                    id_dosen: { required: true },
                @endif
            },
        messages: {
        status: { required: "Silakan pilih salah satu status." },
        @if(!$magang->dosen)
            id_dosen: { required: "Silakan pilih dosen pembimbing." },
        @endif
            },
        submitHandler: function (form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data berhasil dihapus.'
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
                error: function () {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan pada server.' });
                }
            });
            return false;
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-check, .form-select').append(error);
        },
        highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        }
        });
        
    $('#btn-hapus').click(function () {
        Swal.fire({
            title: 'Yakin ingin menghapus data ini?',
            text: "Tindakan ini tidak dapat dibatalkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/kegiatan/edit/' . $magang->id_magang) }}",
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data berhasil dihapus.'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal menghapus data. Silakan coba lagi.'
                        });
                    }
                });
            }
        });
    });

    });
</script>