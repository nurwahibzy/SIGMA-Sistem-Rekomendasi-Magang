<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Admin\PeriodeMagangController;
use App\Http\Controllers\Controller;
use App\Models\AkunModel;
use App\Models\MagangModel;
use App\Models\PenilaianModel;
use App\Models\PeriodeMagangModel;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Log;
use Barryvdh\DomPDF\Facade\Pdf;

class MagangController extends Controller
{
    // try catch and transaction
    private function idMahasiswa()
    {
        $id_mahasiswa = AkunModel::with(relations: 'mahasiswa:id_mahasiswa,id_akun')
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_level'])
            ->mahasiswa
            ->id_mahasiswa;
        return $id_mahasiswa;
    }
    //   public function getDashboard()
    // {
    //     $magang = PeriodeMagangModel::with(
    //         'lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama',
    //         'lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama',
    //         'lowongan_magang.bidang:id_bidang,nama',
    //         'lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
    //     )
    //         ->where('tanggal_mulai', '>', now())
    //         ->get(['id_lowongan', 'tanggal_mulai', 'tanggal_selesai']);

    //         return view('mahasiswa.index', [
    //             'magang' => $magang,
    //             'activeMenu' => 'dashboard'
    //         ]);


    // }


    //     public function getMagang($id_periode)
    //     {
    //         $id_mahasiswa = $this->idMahasiswa();
    //         $magang = PeriodeMagangModel::with(
    //             'lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama,persyaratan,deskripsi',
    //             'lowongan_magang.periode_magang:id_lowongan,nama,tanggal_mulai,tanggal_selesai',
    //             'lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama,provinsi,daerah',
    //             'lowongan_magang.bidang:id_bidang,nama',
    //             'lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
    //         )
    //             ->where('id_periode', $id_periode)
    //             ->where('tanggal_mulai', '>', now())
    //             ->get();
    //         $status = MagangModel::where('id_mahasiswa', $id_mahasiswa)
    //             ->whereIn('status', ['diterima', 'lulus'])
    //             ->first();

    //         // $status = $status == null ? true : false;
    //         // return response()->json($status == null);
    //         return response()->json($magang);
    //     }

    public function postMagang($id_periode)
    {
        $id_mahasiswa = $this->idMahasiswa();
        $tanggal_pengajuan = now();

        // tentukan tanggal pengajuan kurang dari tanggal
        MagangModel::create([
            'id_mahasiswa' => $id_mahasiswa,
            'id_periode' => $id_periode,
            'tanggal_pengajuan' => $tanggal_pengajuan
        ]);

        return response()->json(['success' => true]);
    }

    public function getPerusahaan($id_magang)
    {
        $magang = PeriodeMagangModel::with(
            'lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama,persyaratan,deskripsi',
            'lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama,telepon,deskripsi,foto_path,provinsi,daerah',
            'lowongan_magang.bidang:id_bidang,nama',
            'lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
        )
            ->get();
        return response()->json($magang);
    }

    public function getRiwayatAktivitas($id_magang)
    {
        // $id_mahasiswa = $this->idMahasiswa();
        // $magang = MagangModel::where('id_mahasiswa', $id_mahasiswa)
        //     ->where('id_magang', $id_magang)
        //     ->with('aktivitas_magang')
        //     ->first();
        // return response()->json($magang);
    }

    public function indexRiwayat()
    {
        try {
            return DB::transaction(function () {
                $id_mahasiswa = $this->idMahasiswa();

                $magang = MagangModel::where('id_mahasiswa', $id_mahasiswa)
                    ->whereIn('status', ['proses', 'lulus', 'ditolak'])
                    ->with([
                        'periode_magang:id_periode,id_lowongan,nama,tanggal_mulai,tanggal_selesai',
                        'periode_magang.lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama',
                        'periode_magang.lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama',
                        'periode_magang.lowongan_magang.bidang:id_bidang,nama',
                        'periode_magang.lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
                    ])
                    ->get();

                return view('mahasiswa.riwayat.index', [
                    'magang' => $magang
                ]);
            });
        } catch (\Throwable $e) {
            Log::error("Gagal memuat halaman penilaian: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
        }
    }

    public function downloadSertifikat($idMagang)
    {
        // Validasi ID Magang
        $magang = MagangModel::with('mahasiswa.akun', 'periode_magang.lowongan_magang.perusahaan')
                            ->where('id_magang', $idMagang)
                            ->firstOrFail();

        // Data untuk template sertifikat
        $data = [
            'nama' => $magang->mahasiswa->nama,
            'perusahaan' => $magang->periode_magang->lowongan_magang->perusahaan->nama,
            'tanggal_mulai' => $magang->periode_magang->tanggal_mulai->format('d F Y'),
            'tanggal_selesai' => $magang->periode_magang->tanggal_selesai->format('d F Y'),
            'tanggal_terbit' => now()->format('d F Y'), // Tanggal penerbitan sertifikat
        ];

        // Render view Blade menjadi PDF dengan ukuran A4 landscape
        $pdf = Pdf::loadView('certificates.sertifikat', $data)
                ->setPaper('A4', 'landscape')
                ->setOptions([
                    'dpi' => 150,
                    'defaultFont' => 'serif',
                    'isRemoteEnabled' => true,
                    'isHtml5ParserEnabled' => true,
                    'enable_php' => false
                ]);

        // Simpan nama file sesuai NIM mahasiswa
        $filename = "Sertifikat_Magang_{$magang->mahasiswa->akun->id_user}.pdf";

        // Return PDF untuk di-download
        return $pdf->download($filename);
    }

}
