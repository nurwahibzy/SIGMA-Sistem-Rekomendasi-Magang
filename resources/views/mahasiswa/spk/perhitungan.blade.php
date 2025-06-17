@extends('layouts.tamplate')

@section('content')
    <h1>Langkah Perhitungan MEREC dan ARAS</h1>
    <br>
    <a href="{{ url('mahasiswa/periode/rekomendasi') }}" class="btn btn-primary">Kembali</a>

    <br>
    <br>

    <h2>Metode MEREC</h2>

    <div class="card rounded p-5">
        <h4>Langkah 1: Membuat Matriks Keputusan Awal <span>$$ X $$</span></h4>
        <p>Matriks ini berisi kinerja alternatif terhadap setiap kriteria.</p>
        <table class="table border">
            <thead>
                <tr>
                    <th>ID Alternatif</th>
                    @foreach (array_column($data['kriteria'], 'id') as $criterion_id)
                        <th>{{ $criterion_id }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($data['matriksKeputusan'] as $alternative_id => $values)
                    <tr>
                        <td>{{ $alternative_id }}</td>
                        @foreach (array_column($data['kriteria'], 'id') as $criterion_id)
                            <td>{{ $values[$criterion_id] ?? 'N/A' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card rounded p-5">
        <h4>Langkah 2: Normalisasi Matriks Keputusan <span>$$ N $$</span></h4>
        <p>Normalisasi matriks untuk memastikan perbandingan antar kriteria yang berbeda. Rumus normalisasi bergantung
            pada apakah kriteria tersebut bersifat benefit atau cost.</p>
        <p class="formula">Untuk kriteria benefit: <span>$$ n_{ij} = \frac{x_{ij}}{\max(x_{ij})} $$</span></p>
        <p class="formula">Untuk kriteria cost: <span>$$ n_{ij} = \frac{\min(x_{ij})}{x_{ij}} $$</span></p>
        <table class="table border">
            <thead>
                <tr>
                    <th>ID Alternatif</th>
                    @foreach (array_column($data['kriteria'], 'id') as $criterion_id)
                        <th>{{ $criterion_id }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($data['normalisasiMerec'] as $alternative_id => $values)
                    <tr>
                        <td>{{ $alternative_id }}</td>
                        @foreach (array_column($data['kriteria'], 'id') as $criterion_id)
                            <td>{{ number_format($values[$criterion_id] ?? 0, 4) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card rounded p-5">
        <h4>Langkah 3: Menghitung Kinerja Keseluruhan Alternatif Awal <span> $$ S_i $$</span></h4>
        <p>Hitung kinerja keseluruhan untuk setiap alternatif menggunakan formula logaritma.</p>
        <p class="formula">$$ S_i = \ln\left(1 + \frac{1}{n}\sum_{j=1}^{n} |\ln(n_{ij})|\right) $$</p>
        <table class="table border">
            <thead>
                <tr>
                    <th>ID Alternatif</th>
                    <th>Kinerja Keseluruhan <span>$$ S_i $$</span></th>
                </tr>
            </thead>
            <tbody>
                @if (isset($data['initial_s_j']))
                    @foreach ($data['initial_s_j'] as $alternative_id => $s_j_value)
                        <tr>
                            <td>{{ $alternative_id }}</td>
                            <td>{{ number_format($s_j_value, 4) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2">Data <span>$$ S_i $$</span> awal tidak tersedia.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="card rounded p-5">
        <h4>Langkah 4: Menghitung Kinerja Setelah Menghapus Kriteria <span>$$ S_{ij}' $$</span></h4>
        <p>Untuk setiap kriteria, hitung kinerja tanpa kriteria tersebut.</p>
        <p class="formula">$$ S_{ij}' = \ln\left(1 + \frac{1}{n-1}\sum_{\substack{k=1 \\ k \neq j}}^{n} |\ln(n_{ik})|\right)
            $$</p>
        <table class="table border">
            <thead>
                <tr>
                    <th>ID Alternatif</th>
                    @foreach (array_column($data['kriteria'], 'id') as $criterion_id)
                        <th><span>$$ S_{ij}' $$</span> (tanpa {{ $criterion_id }})</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @if (isset($data['s_prime_j']) && !empty($data['s_prime_j']))
                    @foreach ($data['s_prime_j'] as $alternative_id => $criterion_values)
                        <tr>
                            <td>{{ $alternative_id }}</td>
                            @foreach (array_column($data['kriteria'], 'id') as $criterion_id_to_remove)
                                <td>{{ number_format($criterion_values[$criterion_id_to_remove] ?? 0, 4) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="{{ count(array_column($data['kriteria'], 'id')) + 1 }}">Data <span>$$ S_{ij}'
                                $$</span> tidak tersedia</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="card rounded p-5">
        <h4>Langkah 5: Menghitung Efek Penghapusan Kriteria <span>$$ E_j $$</span></h4>
        <p>Hitung total efek penghapusan setiap kriteria terhadap kinerja alternatif.</p>
        <p class="formula">$$ E_j = \sum_{i=1}^{m} |S_i - S_{ij}'| $$</p>
        <table class="table border">
            <thead>
                <tr>
                    <th>Kriteria</th>
                    <th>Efek Penghapusan <span>$$ E_j $$</span></th>
                </tr>
            </thead>
            <tbody>
                @if (isset($data['e_j']))
                    @foreach ($data['e_j'] as $criterion_id => $e_j_value)
                        <tr>
                            <td>{{ $criterion_id }}</td>
                            <td>{{ number_format($e_j_value, 4) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2">Data <span>$$ E_j $$</span> tidak tersedia</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="card rounded p-5">
        <h4>Langkah 6: Menghitung Bobot Kriteria <span>$$ w_j $$</span></h4>
        <p>Bobot kriteria dihitung berdasarkan efek penghapusan relatif terhadap total efek.</p>
        <p class="formula">$$ w_j = \frac{E_j}{\sum_{k=1}^{n} E_k} $$</p>
        <table class="table border">
            <thead>
                <tr>
                    <th>Kriteria</th>
                    <th>Bobot <span>$$ w_j $$</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['bobotMerec'] as $criterion_id => $weight)
                    <tr>
                        <td>{{ $criterion_id }}</td>
                        <td>{{ number_format($weight, 4) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <hr>

    <h2>Metode ARAS</h2>

    <div class="card rounded p-5">
        <h4>Langkah 1: Membuat Matriks Keputusan Awal <span>$$ X $$</span></h4>
        </p>
        <table class="table border">
            <thead>
                <tr>
                    <th>ID Alternatif</th>
                    @foreach (array_column($data['kriteria'], 'id') as $criterion_id)
                        <th>{{ $criterion_id }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ([0, 1] as $pass)
                    @foreach ($data['matriksKeputusanAras'] as $alternative_id => $values)
                        @if (($pass === 0 && $alternative_id == 0) || ($pass === 1 && $alternative_id != 0))
                            <tr>
                                <td>{{ $alternative_id }}</td>
                                @foreach (array_column($data['kriteria'], 'id') as $criterion_id)
                                    <td>{{ $values[$criterion_id] ?? 'N/A' }}</td>
                                @endforeach
                            </tr>
                        @endif
                    @endforeach
                @endforeach

                <!-- @if (isset($data['matriksOptimal']))
    <tr>
                            <td>Optimal</td>
                            @foreach (array_column($data['kriteria'], 'id') as $criterion_id)
    <td>{{ $data['matriksOptimal'][$criterion_id] ?? 'N/A' }}</td>
    @endforeach
                        </tr>
    @endif -->
            </tbody>
        </table>
    </div>

    <div class="card rounded p-5">
        <h4>Langkah 2: Normalisasi Matriks Keputusan <span>$$ N $$</span></h4>
        <p>Normalisasi matriks menggunakan metode normalisasi yang sesuai (misalnya, normalisasi linear).</p>
        <p class="formula">Untuk kriteria benefit: <span>$$ n_{ij} = x_{ij} / \sum_{i=0}^{m} x_{ij} $$</p>
        <p class="formula">Transformasi nilai cost menjadi benefit: <span>$$ x^*_{ij} = \frac{1}{x_{ij}} $$</span></p>
        <p class="formula">Normalisasi nilai: <span>$$ r_{ij} = \frac{x^*_{ij}}{\sum_{i=0}^{m} x^*_{ij}} $$</span></p>
        <p class="formula"></span> (dimana <span>$$ x_{0j} $$</span> adalah
            nilai optimal)</p>
        <table class="table border">
            <thead>
                <tr>
                    <th>ID Alternatif</th>
                    @foreach (array_column($data['kriteria'], 'id') as $criterion_id)
                        <th>{{ $criterion_id }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ([0, 1] as $pass)
                    @foreach ($data['normalisasiAras'] as $alternative_id => $values)
                        @if (($pass === 0 && $alternative_id == 0) || ($pass === 1 && $alternative_id != 0))
                            <tr>
                                <td>{{ $alternative_id }}</td>
                                @foreach (array_column($data['kriteria'], 'id') as $criterion_id)
                                    <td>{{ $values[$criterion_id] ?? 'N/A' }}</td>
                                @endforeach
                            </tr>
                        @endif
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card rounded p-5">
        <h4>Langkah 3: Menentukan Matriks Keputusan Ternormalisasi Berbobot <span>$$ V $$</span></h4>
        <p>Kalikan matriks yang dinormalisasi dengan bobot kriteria (yang dapat diperoleh dari MEREC).</p>
        <p class="formula">$$ v_{ij} = w_j \times n_{ij} $$</p>
        <table class="table border">
            <thead>
                <tr>
                    <th>ID Alternatif</th>
                    @foreach (array_column($data['kriteria'], 'id') as $criterion_id)
                        <th>{{ $criterion_id }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @if (isset($data['weighted_normalized_matrix']) && !empty($data['weighted_normalized_matrix']))
                    @foreach ([0, 1] as $pass)
                        @foreach ($data['weighted_normalized_matrix'] as $alternative_id => $values)
                            @if (($pass === 0 && $alternative_id == 0) || ($pass === 1 && $alternative_id != 0))
                                <tr>
                                    <td>{{ $alternative_id }}</td>
                                    @foreach (array_column($data['kriteria'], 'id') as $criterion_id)
                                        <td>{{ $values[$criterion_id] ?? 'N/A' }}</td>
                                    @endforeach
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                @else
                    <tr>
                        <td colspan="{{ count(array_column($data['kriteria'], 'id')) + 1 }}">Weighted Normalized Matrix
                            (V) tidak tersedia di JSON yang diberikan.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="card rounded p-5">
        <h4>Langkah 4: Menghitung Nilai Fungsi Optimalitas <span>$$ K_j $$</span></h4>
        <p>Hitung nilai fungsi optimalitas untuk setiap alternatif dengan menjumlahkan nilai-nilai yang dinormalisasi
            dan dibobot.</p>
        <p class="formula">$$ K_j = \sum_{i=1}^{m} v_{ij} $$</p>
        <table class="table border">
            <thead>
                <tr>
                    <th>ID Alternatif</th>
                    <th>Fungsi Optimalitas ($K_j$)</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($data['optimality_function']))
                    @foreach ([0, 1] as $pass)
                        @foreach ($data['optimality_function'] as $alternative_id => $k_j_value)
                            @if (($pass === 0 && $alternative_id == 0) || ($pass === 1 && $alternative_id != 0))
                                <tr>
                                    <td>{{ $alternative_id }}</td>
                                    <td>{{ number_format($k_j_value, 4) }}</td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach

                    <!-- @foreach ([0, 1] as $pass)
    @foreach ($data['weighted_normalized_matrix'] as $alternative_id => $values)
    @if (($pass === 0 && $alternative_id == 0) || ($pass === 1 && $alternative_id != 0))
    <tr>
                                        <td>{{ $alternative_id }}</td>
                                        @foreach (array_column($data['kriteria'], 'id') as $criterion_id)
    <td>{{ $values[$criterion_id] ?? 'N/A' }}</td>
    @endforeach
                                    </tr>
    @endif
    @endforeach
    @endforeach -->
                @else
                    <tr>
                        <td colspan="2">Data Fungsi Optimalitas <span>$$ K_j $$</span> tidak tersedia di JSON yang
                            diberikan.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="card rounded p-5">
        <h4>Langkah 5: Menghitung Derajat Utilitas <span>$$ P_j $$</span></h4>
        <p>Tentukan derajat utilitas untuk setiap alternatif dengan membandingkan nilai fungsi optimalitasnya dengan
            nilai fungsi optimalitas maksimum.</p>
        <p class="formula">$$ P_j = K_j / \max(K) $$</p>
        <table class="table border">
            <thead>
                <tr>
                    <th>ID Alternatif</th>
                    <th>Derajat Utilitas <span>$$ P_j $$</span></th>
                </tr>
            </thead>
            <tbody>
                @if (isset($data['utility_degree']))
                    @foreach ($data['utility_degree'] as $alternative_id => $p_j_value)
                        <tr>
                            <td>{{ $alternative_id }}</td>
                            <td>{{ number_format($p_j_value, 4) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2">Data Derajat Utilitas <span>$$ P_j $$</span> tidak tersedia di JSON yang
                            diberikan.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="card rounded p-5">
        <h4>Langkah 6: Memeringkat Alternatif</h4>
        <p>Peringkatkan alternatif berdasarkan skornya dalam ascending.</p>
        <table class="table border">
            <thead>
                <tr>
                    <th>Peringkat</th>
                    <th>id_Alternatif</th>
                    <th>Alternatif</th>
                    <th>Skor</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Urutkan hasil berdasarkan 'skor' dalam urutan menurun
                    $ranked_results = $data['hasil'];
                    usort($ranked_results, function ($a, $b) {
                        return $b['skor'] <=> $a['skor'];
                    });
                    $rank = 1;
                @endphp
                @foreach ($ranked_results as $result)
                    <tr>
                        <td>{{ $rank++ }}</td>
                        <td>{{ $result['id_lowongan'] }}</td>
                        <td>{{ $result['alternatif'] }}</td>
                        <td>{{ number_format($result['skor'], 4) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
@endpush
