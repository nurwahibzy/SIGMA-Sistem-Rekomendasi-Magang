<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\AkunModel;
use App\Models\MagangModel;
use DB;
use Auth;
use Illuminate\Http\Request;

class MagangController extends Controller
{
    // add peringatan, try catch, transaction
    private function idDosen()
    {
        try {
            $id_dosen = AkunModel::with(relations: 'dosen:id_dosen,id_akun')
                ->where('id_akun', Auth::user()->id_akun)
                ->first(['id_akun', 'id_level'])
                ->dosen
                ->id_dosen;
            return $id_dosen;
        } catch (\Exception $e) {
            throw new \Exception('Tidak dapat mengambil ID dosen: ' . $e->getMessage());
        }
    }
    
    public function getDashboard(){
        try {
            $id_dosen = $this->idDosen();
            $peserta = MagangModel::where('id_dosen', $id_dosen)->get();
            return view('dosen.index', compact('peserta'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getRiwayat(){
        try {
            $id_dosen = $this->idDosen();
            $magang = MagangModel::where('id_dosen', $id_dosen)
                ->where('status', 'diterima')
                ->get();
            
            return view('dosen.mahasiswa.index', compact('magang'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getDetailRiwayat($id_magang)
    {
        try {
            // Validasi ID dosen
            $id_dosen = $this->idDosen();
            
            // Get detailed magang information with all related data
            $magang = MagangModel::with([
                'mahasiswa',
                'mahasiswa.keahlian_mahasiswa' => function ($query) {
                    $query->orderBy('prioritas');
                },
                'mahasiswa.pengalaman',
                'mahasiswa.akun',
                'dosen',
                'periode_magang',
                'periode_magang.lowongan_magang',
                'periode_magang.lowongan_magang.perusahaan',
                'periode_magang.lowongan_magang.perusahaan.jenis_perusahaan',
                'periode_magang.lowongan_magang.bidang'
            ])
            ->where('id_magang', $id_magang)
            ->where('id_dosen', $id_dosen) // Pastikan magang milik dosen yang sedang login
            ->first();

            if (!$magang) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data magang tidak ditemukan atau Anda tidak memiliki akses.'
                ], 404);
            }

            // Jika request adalah AJAX, return view partial
            if (request()->ajax()) {
                return view('dosen.mahasiswa.detail', compact('magang'));
            }
            
            // Jika bukan AJAX, redirect dengan pesan error
            return redirect()->back()->with('error', 'Akses tidak valid.');
            
        } catch (\Exception $e) {
            \Log::error('Error in getDetailRiwayat: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Data magang tidak ditemukan atau terjadi kesalahan.');
        }
    }
}