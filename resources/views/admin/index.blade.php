@extends('layouts.tamplate')

@section('content')

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header text-white">
                    <h5 class="mb-0">Status Pengguna</h5>
                </div>

                <div class="row d-flex card-body justify-content-between align-items-center">
                    <div style="width: 225px;">
                        <canvas id="AdminChart"></canvas>
                    </div>
                    <div style="width: 225px;">
                        <canvas id="MahasiswaChart"></canvas>
                    </div>
                    <div style="width: 225px;">
                        <canvas id="DosenChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="card shadow">
                <div class="card-header text-white">
                    <h5 class="mb-0">Periode Magang per Tahun</h5>
                </div>
                <div class="card-body">
                    <canvas id="periodeChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header text-white">
                    <h5 class="mb-0">Status Mahasiswa Magang</h5>
                </div>
                <div class="card-body">
                    <canvas id="magangChart" height="120"></canvas>
                </div>
            </div>
            <div class="card shadow">
                <div class="card-header text-white">
                    <h5 class="mb-0">Jenis Perusahaan</h5>
                </div>
                <div class="card-body">
                    <canvas id="jenisPerusahaanChart" height="120"></canvas>
                </div>
            </div>
            <div class="card shadow">
                <div class="card-header text-white">
                    <h5 class="mb-0">Bidang Lowongan</h5>
                </div>
                <div class="card-body">
                    <canvas id="bidangLowonganChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
    </div>


@endsection

@push('scripts')
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
    <script>
        const ctxPeriode = document.getElementById('periodeChart').getContext('2d');
        const dataPeriode = {
            labels: {!! json_encode($periode->pluck('tahun')) !!},
            datasets: [{
                label: 'Jumlah Periode Magang',
                data: {!! json_encode($periode->pluck('total')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 10
            }]
        };
        const configPeriode = {
            type: 'bar',
            data: dataPeriode,
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return ' Total: ' + context.raw;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        };
        new Chart(ctxPeriode, configPeriode);

        const ctxAdmin = document.getElementById('AdminChart').getContext('2d');
        const dataAdmin = {
            labels: {!! json_encode($admin->keys()) !!},
            datasets: [{
                data: {!! json_encode($admin->values()) !!},
                backgroundColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
            }]
        };
        const configAdmin = {
            type: 'pie',
            data: dataAdmin,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        align: 'center',
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Admin'
                    }
                }
            }
        };
        new Chart(ctxAdmin, configAdmin);

        const ctxMahasiswa = document.getElementById('MahasiswaChart').getContext('2d');
        const dataMahasiswa = {
            labels: {!! json_encode($mahasiswa->keys()) !!},
            datasets: [{
                data: {!! json_encode($mahasiswa->values()) !!},
                backgroundColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
            }]
        };
        const configMahasiswa = {
            type: 'pie',
            data: dataMahasiswa,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        align: 'center',
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Mahasiswa'
                    }
                }
            }
        };
        new Chart(ctxMahasiswa, configMahasiswa);

        const ctxDosen = document.getElementById('DosenChart').getContext('2d');
        const dataDosen = {
            labels: {!! json_encode($dosen->keys()) !!},
            datasets: [{
                data: {!! json_encode($dosen->values()) !!},
                backgroundColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
            }]
        };
        const configDosen = {
            type: 'pie',
            data: dataDosen,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        align: 'center',
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Dosen'
                    }
                }
            }
        };
        new Chart(ctxDosen, configDosen);


        const ctxMagang = document.getElementById('magangChart').getContext('2d');
        const dataMagang = {
            labels: {!! json_encode($magang->pluck('status')) !!},
            datasets: [{
                label: 'Jumlah Mahasiswa',
                data: {!! json_encode($magang->pluck('total')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 10
            }]
        };
        const configMagang = {
            type: 'bar',
            data: dataMagang,
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return ' Total: ' + context.raw;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        };
        new Chart(ctxMagang, configMagang);

        const ctxJenisPerusahaan = document.getElementById('jenisPerusahaanChart').getContext('2d');
        const dataJenisPerusahaan = {
            labels: {!! json_encode($jenisPerusahaan->pluck('jenis_perusahaan.jenis')->take(5)) !!},
            datasets: [{
                data: {!! json_encode($jenisPerusahaan->pluck('total')->take(5)) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 10
            }]
        };
        const configJenisPerusahaan = {
            type: 'bar',
            data: dataJenisPerusahaan,
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return ' Total: ' + context.raw;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        };
        new Chart(ctxJenisPerusahaan, configJenisPerusahaan);

        const ctxBidangLowongan = document.getElementById('bidangLowonganChart').getContext('2d');
        const dataBidangLowongan = {
            labels: {!! json_encode($bidangLowongan->pluck('bidang.nama')->take(5)) !!},
            datasets: [{
                data: {!! json_encode($bidangLowongan->pluck('total')->take(5)) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 10
            }]
        };
        const configBidangLowongan = {
            type: 'bar',
            data: dataBidangLowongan,
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return ' Total: ' + context.raw;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        };
        new Chart(ctxBidangLowongan, configBidangLowongan);
    </script>
@endpush