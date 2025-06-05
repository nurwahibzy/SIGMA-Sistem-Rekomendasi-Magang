<!DOCTYPE html>
<html>

<head>
    <title>Langkah Perhitungan MEREC dan ARAS</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
        }

        h1,
        h2,
        h3,
        h4 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .step {
            margin-bottom: 30px;
            padding: 15px;
            border: 1px solid #eee;
            background-color: #f9f9f9;
        }

        .step h4 {
            margin-top: 0;
            color: #555;
        }

        .formula {
            font-style: italic;
            color: #666;
        }

        .note {
            color: #888;
            font-size: 0.9em;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <h1>Langkah Perhitungan MEREC dan ARAS</h1>
    <a href="rekomendasi">Kembali</a>

    <hr>

    <h2>Metode MEREC</h2>

    <div class="step">
        <h4>Langkah 1: Membuat Matriks Keputusan Awal ($X$)</h4>
        <p>Matriks ini berisi kinerja alternatif terhadap setiap kriteria.</p>
        <table>
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

    <div class="step">
        <h4>Langkah 2: Normalisasi Matriks Keputusan ($N$)</h4>
        <p>Normalisasi matriks untuk memastikan perbandingan antar kriteria yang berbeda. Rumus normalisasi bergantung
            pada apakah kriteria tersebut bersifat keuntungan (benefit) atau biaya (cost).</p>
        <p class="formula">Untuk kriteria keuntungan: $n_{ij} = x_{ij} / \max_i(x_{ij})$</p>
        <p class="formula">Untuk kriteria biaya: $n_{ij} = \min_i(x_{ij}) / x_{ij}$</p>
        <table>
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

    <div class="step">
        <h4>Langkah 3: Menghitung Kinerja Keseluruhan Alternatif (awal $S_j$)</h4>
        <p>Hitung kinerja keseluruhan untuk setiap alternatif berdasarkan matriks yang dinormalisasi.</p>
        <p class="formula">$S_j = \sum_{i=1}^{m} n_{ij}$</p>
        <table>
            <thead>
                <tr>
                    <th>ID Alternatif</th>
                    <th>Kinerja Keseluruhan ($S_j$)</th>
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
                        <td colspan="2">Data $S_j$ awal tidak tersedia di JSON yang diberikan.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="step">
        <h4>Langkah 4: Menghitung Kinerja Keseluruhan Setelah Menghapus Setiap Kriteria ($S'_j$)</h4>
        <p>Untuk setiap kriteria, hapus sementara kriteria tersebut dan hitung ulang kinerja keseluruhan untuk setiap
            alternatif.</p>
        <table>
            <thead>
                <tr>
                    <th>ID Alternatif</th>
                    @foreach (array_column($data['kriteria'], 'id') as $criterion_id)
                        <th>S'j (menghapus {{ $criterion_id }})</th>
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
                        <td colspan="{{ count(array_column($data['kriteria'], 'id')) + 1 }}">Data $S'_j$ tidak tersedia
                            di JSON yang diberikan.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="step">
        <h4>Langkah 5: Menghitung Jumlah Perbedaan Absolut ($E_j$)</h4>
        <p>Hitung jumlah perbedaan absolut antara kinerja keseluruhan awal dan kinerja setelah menghapus setiap
            kriteria.</p>
        <p class="formula">$E_j = \sum_{k=1}^{m} |S_k - S'_{k, j}|$</p>
        <table>
            <thead>
                <tr>
                    <th>Kriteria</th>
                    <th>$E_j$</th>
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
                        <td colspan="2">Data $E_j$ tidak tersedia di JSON yang diberikan.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="step">
        <h4>Langkah 6: Menghitung Bobot Akhir Kriteria ($w_j$)</h4>
        <p>Tentukan bobot akhir kriteria berdasarkan dampaknya terhadap kinerja keseluruhan.</p>
        <p class="formula">$w_j = E_j / \sum_{k=1}^{n} E_k$</p>
        <table>
            <thead>
                <tr>
                    <th>Kriteria</th>
                    <th>Bobot ($w_j$)</th>
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

    <div class="step">
        <h4>Langkah 1: Membuat Matriks Keputusan Awal ($X$)</h4>
        </p>
        <table>
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

    <div class="step">
        <h4>Langkah 2: Normalisasi Matriks Keputusan ($N$)</h4>
        <p>Normalisasi matriks menggunakan metode normalisasi yang sesuai (misalnya, normalisasi linear).</p>
        <p class="formula">Untuk kriteria benefit: $n_{ij} = x_{ij} / \sum_{i=0}^{m} x_{ij}$ (dimana $x_{0j}$ adalah
            nilai optimal)</p>
        <p class="formula">Untuk kriteria cost: $n_{ij} = x_{0j} / x_{ij}$ (dimana $x_{0j}$ adalah nilai optimal)</p>
        <table>
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

    <div class="step">
        <h4>Langkah 3: Menentukan Matriks Keputusan Ternormalisasi Berbobot ($V$)</h4>
        <p>Kalikan matriks yang dinormalisasi dengan bobot kriteria (yang dapat diperoleh dari MEREC).</p>
        <p class="formula">$v_{ij} = w_j \times n_{ij}$</p>
        <table>
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

    <div class="step">
        <h4>Langkah 4: Menghitung Nilai Fungsi Optimalitas ($K_j$)</h4>
        <p>Hitung nilai fungsi optimalitas untuk setiap alternatif dengan menjumlahkan nilai-nilai yang dinormalisasi
            dan dibobot.</p>
        <p class="formula">$K_j = \sum_{i=1}^{m} v_{ij}$</p>
        <table>
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
                        <td colspan="2">Data Fungsi Optimalitas ($K_j$) tidak tersedia di JSON yang diberikan.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="step">
        <h4>Langkah 5: Menghitung Derajat Utilitas ($P_j$)</h4>
        <p>Tentukan derajat utilitas untuk setiap alternatif dengan membandingkan nilai fungsi optimalitasnya dengan
            nilai fungsi optimalitas maksimum.</p>
        <p class="formula">$P_j = K_j / \max(K)$</p>
        <table>
            <thead>
                <tr>
                    <th>ID Alternatif</th>
                    <th>Derajat Utilitas ($P_j$)</th>
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
                        <td colspan="2">Data Derajat Utilitas ($P_j$) tidak tersedia di JSON yang diberikan.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="step">
        <h4>Langkah 6: Memeringkat Alternatif</h4>
        <p>Peringkatkan alternatif berdasarkan skornya dalam ascending.</p>
        <table>
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

</body>

</html>
