@extends('layouts.tamplate')

@section('content')
    <div>
        <section class="section">
            <div class="row">
                <div class="col-md-4">
                    <div class="position-sticky" style="top: 90px;">
                        <div class="card p-4 shadow  d-flex justify-content-between align-items-center">
                            <div style="width: 225px;">
                                <canvas id="statusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Program Studi</h5>
                            <button onclick="modalAction('{{ url('/admin/prodi/tambah') }}')" class="btn btn-primary"><i
                                    class="bi bi-plus"></i>Tambah Prodi</button>
                        </div>
                        <div class="card-body">
                            @if (count($prodi))
                                <div class="table-responsive">
                                    <table class="table" id="table1">
                                        <thead>
                                            <tr>
                                                <th>Jurusan</th>
                                                <th>Prodi</th>
                                                <th>Status</th>
                                                <th class="text-center"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($prodi as $item)
                                                <tr>
                                                    <td>{{ $item->nama_jurusan ?? '-' }}</td>
                                                    <td>{{ $item->nama_prodi ?? '-' }}</td>
                                                    <td>
                                        <span class="badge 
                                            @if($item->status == 'aktif') bg-success
                                            @elseif($item->status == 'nonaktif') bg-primary
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($item->status ?? '-') }}
                                        </span>
                                    </td>
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
                </div>
            </div>
        </section>
    </div>

    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>

@endsection

@php
    $labels = collect($data)->pluck('prodi.nama_prodi');
    $totals = collect($data)->pluck('total');
@endphp

@push('js')
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
    <script>
        const labels = @json($labels);
        const data = @json($totals);

        function generateColors(count) {
            const colors = [];
            for (let i = 0; i < count; i++) {
                const hue = Math.floor((360 / count) * i);
                colors.push(`hsl(${hue}, 70%, 60%)`);
            }
            return colors;
        }

        const dynamicColors = generateColors(labels.length);

        const ctx = document.getElementById('statusChart').getContext('2d');

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Mahasiswa',
                    data: data,
                    backgroundColor: dynamicColors,
                    borderColor: '#ffffff',
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        align: 'start',
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Jumlah Mahasiswa'
                    }
                }
            }
        });
    </script>
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