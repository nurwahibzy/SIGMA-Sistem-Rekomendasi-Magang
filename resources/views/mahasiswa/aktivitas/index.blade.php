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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($aktivitas as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $item->keterangan }}</td>
                                        <td>
                                            @if($item->foto_path)
                                                <a href="{{ asset('storage/aktivitas/' . $item->foto_path) }}" target="_blank">Lihat File</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal ?? $item->created_at)->format('d M Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
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
                        <label for="file" class="form-label">File Aktivitas (PDF, JPG, PNG, dll)</label>
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
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    let table = $('#aktivitasTable').DataTable({
        responsive: true
    });

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
                        today
                    ]).draw(false);

                    $('#uploadForm')[0].reset();

                    var modalTambahEl = document.getElementById('modalTambah');
                    var modalTambah = bootstrap.Modal.getInstance(modalTambahEl);
                    if (!modalTambah) {
                        modalTambah = new bootstrap.Modal(modalTambahEl);
                    }
                    modalTambah.hide();
                } else {
                    $('#result').html(`<div class="alert alert-danger">Gagal menyimpan aktivitas.</div>`);
                }
            },
            error: function (xhr) {
                $('#result').html(`<div class="alert alert-danger">Gagal: ${xhr.responseJSON?.message || 'Terjadi kesalahan.'}</div>`);
            }
        });
    });
});
</script>
@endpush
