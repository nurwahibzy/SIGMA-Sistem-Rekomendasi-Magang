<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Langkah Perhitungan MEREC dan ARAS</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
        }

        h1,
        h2,
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
        }

        .formula {
            font-style: italic;
            color: #555;
        }

        .note {
            color: #888;
            font-size: 0.9em;
        }
    </style>
</head>

<body>

    <h1>Langkah Perhitungan Metode MEREC dan ARAS</h1>
    <hr>

    <h2>Metode MEREC</h2>

    <div class="step">
        <h4>Langkah 1: Matriks Keputusan Awal ($X$)</h4>
        <p>Matriks ini berisi nilai kinerja alternatif terhadap masing-masing kriteria.</p>
        <table>
            <thead>
                <tr>
                    <th>Alternatif</th>
                    @foreach (array_column($data['kriteria'], 'id') as $criterion_id)
                        <th>{{ $criterion_id }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($data['matriksKeputusan'] as $alt_id => $values)
                    <tr>
                        <td>{{ $alt_id }}</td>
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
        <p>Normalisasi dilakukan agar nilai kriteria dapat dibandingkan. Rumus tergantung jenis kriteria.</p>
        <p class="formula">Kriteria keuntungan: $n_{ij} = x_{ij} / \max_i(x_{ij})$</p>
        <p class="formula">Kriteria biaya: $n_{ij} = \min_i(x_{ij}) / x_{ij}$</p>
        <table>
            <thead>
                <tr>
                    <th>Alternatif</th>
                    @foreach (array_column($data['kriteria'], 'id') as $criterion_id)
                        <th>{{ $criterion_id }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($data['normalisasiMerec'] as $alt_id => $values)
                    <tr>
                        <td>{{ $alt_id }}</td>
                        @foreach (array_column($data['kriteria'], 'id') as $criterion_id)
                            <td>{{ number_format($values[$criterion_id] ?? 0, 4) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="step">
        <h4>Langkah 3: Hitung Kinerja Keseluruhan Awal ($S_j$)</h4>
        <p>Jumlahkan nilai normalisasi dari setiap alternatif.</p>
        <p class="formula">$S_j = \sum_{i=1}^{m} n_{ij}$</p>
        <table>
            <thead>
                <tr>
                    <th>Alternatif</th>
                    <th>Kinerja ($S_j$)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['normalisasiMerec'] as $alt_id => $values)
                    @php
                        $sum_sj = array_sum($values);
                    @endphp
                    <tr>
                        <td>{{ $alt_id }}</td>
                        <td>{{ number_format($sum_sj, 4) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="step">
        <h4>Langkah 4: Hitung $S'_j$ (tanpa 1 kriteria)</h4>
        <p>Hitung ulang $S_j$ untuk tiap alternatif dengan menghapus satu kriteria setiap kali.</p>
        <div class="note">Perhitungan $S'_j$ perlu dilakukan di backend. Tabel di bawah ini bersifat placeholder.</div>
        <table>
            <thead>
                <tr>
                    <th>Alternatif</th>
                    <th>$S'_j$</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">Data belum tersedia.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="step">
        <h4>Langkah 5: Hitung Jumlah Selisih Mutlak ($E_j$)</h4>
        <p>Hitung selisih mutlak antara $S_j$ dan $S'_j$ untuk setiap alternatif.</p>
        <p class="formula">$E_j = \sum_{j=1}^{n} |S_j - S'_j|$</p>
        <table>
            <thead>
                <tr>
                    <th>Kriteria</th>
                    <th>$E_j$</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">Belum tersedia.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="step">
        <h4>Langkah 6: Hitung Bobot Akhir Kriteria ($w_j$)</h4>
        <p>Bobot ditentukan dari nilai $E_j$.</p>
        <p class="formula">$w_j = E_j / \sum E_k$</p>
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
        <h4>Langkah 1: Matriks Keputusan Awal ($X$)</h4>
        <p>Matriks yang sama seperti pada MEREC atau matriks baru untuk ARAS.</p>
        <table>
            <thead>
                <tr>
                    <th>Alternatif</th>
                    @foreach (array_column($data['kriteria'], 'id') as $criterion_id)
                        <th>{{ $criterion_id }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($data['matriksKeputusan'] as $alt_id => $values)
                    <tr>
                        <td>{{ $alt_id }}</td>
                        @foreach (array_column($data['kriteria'], 'id') as $criterion_id)
                            <td>{{ $values[$criterion_id] ?? 'N/A' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="step">
        <h4>Langkah 2: Normalisasi Matriks ($N$)</h4>
        <p>Lakukan normalisasi linear.</p>
        <p class="formula">Kriteria keuntungan: $n_{ij} = x_{ij} / \sum x_{ij}$</p>
        <p class="formula">Kriteria biaya: $n_{ij} = (1/x_{ij}) / \sum (1/x_{ij})$</p>
        <table>
            <thead>
                <tr>
                    <th>Alternatif</th>
                    @foreach (array_column($data['kriteria'], 'id') as $criterion_id)
                        <th>{{ $criterion_id }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($data['normalisasiAras'] as $alt_id => $values)
                    <tr>
                        <td>{{ $alt_id }}</td>
                        @foreach (array_column($data['kriteria'], 'id') as $criterion_id)
                            <td>{{ number_format($values[$criterion_id] ?? 0, 4) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="step">
        <h4>Langkah 3: Matriks Ternormalisasi Tertimbang ($V$)</h4>
        <p>Kalikan nilai normalisasi dengan bobot ($w_j$) dari MEREC atau metode lain.</p>
        <p class="formula">$v_{ij} = w_j \times n_{ij}$</p>
        <table>
            <thead>
                <tr>
                    <th>Alternatif</th>
                    @foreach (array_column($data['kriteria'], 'id') as $criterion_id)
                        <th>{{ $criterion_id }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="{{ count($data['kriteria']) + 1 }}">Data belum tersedia.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="step">
        <h4>Langkah 4: Fungsi Optimalitas ($K_j$)</h4>
        <p>Jumlahkan seluruh nilai tertimbang ($v_{ij}$) dari setiap alternatif.</p>
        <p class="formula">$K_j = \sum v_{ij}$</p>
        <table>
            <thead>
                <tr>
                    <th>Alternatif</th>
                    <th>$K_j$</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">Belum dihitung.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="step">
        <h4>Langkah 5: Derajat Utilitas ($P_j$)</h4>
        <p>Bandingkan $K_j$ dengan nilai maksimalnya.</p>
        <p class="formula">$P_j = K_j / \max(K)$</p>
        <table>
            <thead>
                <tr>
                    <th>Alternatif</th>
                    <th>$P_j$</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">Belum dihitung.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="step">
        <h4>Langkah 6: Peringkat Alternatif</h4>
        <p>Urutkan alternatif berdasarkan nilai $P_j$ dari yang tertinggi.</p>
        <table>
            <thead>
                <tr>
                    <th>Peringkat</th>
                    <th>Alternatif</th>
                    <th>Skor ($P_j$)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="3">Data belum tersedia.</td>
                </tr>
            </tbody>
        </table>
    </div>

</body>

</html>
