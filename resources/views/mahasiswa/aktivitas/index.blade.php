@extends('layouts.tamplate')

@section('content')
    <div class="page-heading">
        <h3>Log Aktivitas Magang</h3>
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-12">

                {{-- Tombol Tambah --}}
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus"></i> Tambah Aktivitas
                </button>

                {{-- Jika tidak ada aktivitas --}}
                @if($aktivitas->isEmpty())
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-circle"></i> Belum ada aktivitas magang yang ditambahkan.
                    </div>
                @else
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Daftar Aktivitas</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="aktivitasTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Deskripsi</th>
                                        <th>File</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($aktivitas as $i => $item)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $item->keterangan }}</td>
                                            <td>
                                                @if($item->foto_path)
                                                    <a href="{{ asset('storage/aktivitas/' . $item->foto_path) }}" target="_blank">Lihat
                                                        File</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal ?? $item->created_at)->format('d M Y') }}
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info btn-detail"
                                                    data-id="{{ $item->id_aktivitas }}">
                                                    Detail
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- Tombol Kembali --}}
                    <a href="javascript:history.back()" class="btn btn-secondary mb-3">
                        <i class="bi bi-arrow-left-circle"></i> Kembali
                    </a>
                @endif
            </div>
        </section>
    </div>

    {{-- Modal Tambah Aktivitas --}}
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="uploadForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahLabel">Tambah Aktivitas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div id="result"></div>

                        <div class="mb-3">
                            <label for="file" class="form-label">File Aktivitas (PDF, JPG, PNG, JPEG)</label>
                            <input type="file" name="file" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Deskripsi Aktivitas</label>
                            <textarea name="keterangan" id="keterangan" rows="4" class="form-control" required></textarea>
                        </div>

                        <input type="hidden" name="id_magang" value="{{ $id_magang }}">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-upload"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Detail Aktivitas --}}
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="detailContent">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailLabel">Detail Aktivitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit Aktivitas --}}
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="editContent">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit Aktivitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="modalKonfirmasiHapus" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content" id="konfirmasiContent">
                <div class="modal-body text-center">
                    <div class="spinner-border text-primary"></div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            let table = $('#aktivitasTable').DataTable({ responsive: true });

            $('#uploadForm').on('submit', function (e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: '{{ url("/mahasiswa/aktivitas/$id_magang/tambah") }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (res) {
                        if (res.success) {
                            $('#result').html(`<div class="alert alert-success">Aktivitas berhasil ditambahkan!</div>`);
                            const data = res.data;
                            const today = new Date(data.tanggal).toLocaleDateString('id-ID', {
                                day: '2-digit', month: 'short', year: 'numeric'
                            });

                            table.row.add([
                                table.rows().count() + 1,
                                data.keterangan,
                                `<a href="{{ asset('storage/aktivitas/') }}/${data.foto_path}" target="_blank">${data.foto_path}</a>`,
                                today,
                                `<button type="button" class="btn btn-sm btn-info btn-detail" data-id="${data.id_aktivitas}">Detail</button>`
                            ]).draw(false);

                            $('#uploadForm')[0].reset();
                            bootstrap.Modal.getInstance(document.getElementById('modalTambah')).hide();
                        } else {
                            $('#result').html(`<div class="alert alert-danger">Gagal menyimpan aktivitas.</div>`);
                        }
                    },
                    error: function (xhr) {
                        $('#result').html(`<div class="alert alert-danger">Gagal: ${xhr.responseJSON?.message || 'Terjadi kesalahan.'}</div>`);
                    }
                });
            });

            $(document).on('click', '.btn-detail', function () {
                const id = $(this).data('id');

                $('#modalDetail').modal('show');
                $('#detailContent .modal-body').html(`<div class="text-center"><div class="spinner-border text-primary"></div></div>`);

                $.get(`/mahasiswa/aktivitas/${id}/detail`, function (res) {
                    $('#detailContent .modal-body').html(res);
                });
            });

            $(document).on('click', '#btnEdit', function () {
                const id = $(this).data('id');
                const idMagang = '{{ $id_magang }}'; // Ambil dari blade

                $('#modalDetail').modal('show');
                $('#detailContent .modal-body').html(`<div class="text-center"><div class="spinner-border text-primary"></div></div>`);

                $.get(`/mahasiswa/aktivitas/${idMagang}/edit/${id}`, function (res) {
                    $('#detailContent .modal-body').html(res);

                    // Bind ulang submit form edit di dalam modal
                    $('#editForm').off('submit').on('submit', function (e) {
                        e.preventDefault();
                        let formData = new FormData(this);

                        $.ajax({
                            url: `/mahasiswa/aktivitas/${idMagang}/edit/${id}`,
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function (res) {
                                if (res.success) {
                                    $('#result').html(`<div class="alert alert-success">Aktivitas berhasil diperbarui!</div>`);
                                    const data = res.data;

                                    const today = new Date(data.tanggal).toLocaleDateString('id-ID', {
                                        day: '2-digit', month: 'short', year: 'numeric'
                                    });

                                    // Update baris di DataTable
                                    $('#aktivitasTable tbody tr').each(function () {
                                        let btn = $(this).find('.btn-detail');
                                        if (btn.data('id') == data.id_aktivitas) {
                                            let rowIndex = table.row($(this)).index();

                                            table.row(rowIndex).data([
                                                rowIndex + 1,
                                                data.keterangan,
                                                data.foto_path
                                                    ? `<a href="{{ asset('storage/aktivitas/') }}/${data.foto_path}" target="_blank">${data.foto_path}</a>`
                                                    : '-',
                                                today,
                                                `<button type="button" class="btn btn-sm btn-info btn-detail" data-id="${data.id_aktivitas}">Detail</button>`
                                            ]).draw(false);
                                        }
                                    });

                                    bootstrap.Modal.getInstance(document.getElementById('modalDetail')).hide();
                                } else {
                                    $('#result').html(`<div class="alert alert-danger">Gagal memperbarui aktivitas.</div>`);
                                }
                            },
                            error: function (xhr) {
                                $('#result').html(`<div class="alert alert-danger">Gagal: ${xhr.responseJSON?.message || 'Terjadi kesalahan.'}</div>`);
                            }
                        });
                    });
                });
            });

            $(document).on('click', '#btnHapus', function () {
                const id = $(this).data('id');
                const idMagang = '{{ $id_magang }}';

                $('#modalDetail').modal('hide'); // sembunyikan modal detail dulu
                $('#modalKonfirmasiHapus').modal('show');
                $('#konfirmasiContent').html(`<div class="text-center"><div class="spinner-border text-primary"></div></div>`);

                // load konfirmasi ke dalam modal
                $.get(`/mahasiswa/aktivitas/${idMagang}/confirm/${id}`, function (res) {
                    $('#konfirmasiContent').html(res);
                });
            });

            $(document).on('click', '#confirmDeleteBtn', function () {
                const id = $(this).data('id');
                const idMagang = $(this).data('idmagang');

                $.ajax({
                    url: `/mahasiswa/aktivitas/${idMagang}/delete/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function () {
                        $('#modalKonfirmasiHapus').modal('hide');
                        alert('Aktivitas berhasil dihapus.');
                        location.reload();
                    },
                    error: function () {
                        alert('Gagal menghapus aktivitas.');
                    }
                });
            });



        });
    </script>

@endpush