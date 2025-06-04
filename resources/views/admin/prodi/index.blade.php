@extends('layouts.tamplate')

@section('content')
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Program Studi</h5>
            <button onclick="modalAction('{{ url('/admin/prodi/tambah') }}')" class="btn btn-primary"><i class="bi bi-plus"></i>Tambah Prodi</button>
        </div>
        <div class="card-body">
        @if (count($prodi))
            <div class="table-responsive">
                <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th>Jurusan</th>
                            <th>Prodi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach ($prodi as $item)
                                <tr>
                                <td>{{ $item->nama_jurusan ?? '-' }}</td>
                                <td>{{ $item->nama_prodi ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-sm btn-warning btn-edit"
                                            onclick="modalAction('{{ url('/admin/prodi/edit/' . $item->id_prodi) }}')">Edit</button>
                                        <button class="btn btn-sm btn-danger btn-hapus"
                                            data-id="{{ $item->id_prodi }}">Hapus</button>
                                    </div>
                                </td>
                                </tr>
                            @endforeach
                        </tbody>
                </table>
            </div>
            @else
                <div class="text-center">
                    <p class="mt-4">Tidak ada prodi tersedia</p>
                </div>
            @endif
        </div>
    </div>

    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }

        $(document).ready(function () {
            $(document).on('click', '.btn-hapus', function () {
                let id = $(this).data('id');
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
                            url: `{{ url('/admin/prodi/edit') }}/${id}`,
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
@endpush