<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DosenModel;
use App\Models\JenisPerusahaanModel;
use App\Models\MagangModel;
use Auth;
use Illuminate\Http\Request;

class MagangController extends Controller
{
    public function getDashboard()
    {
        // return view('admin.index');
        return redirect('/admin/admin');
    }

    public function getKegiatan()
    {
        $magang = MagangModel::with('mahasiswa', 'mahasiswa.akun', 'dosen', 'periode_magang', 'periode_magang.lowongan_magang', 'periode_magang.lowongan_magang.perusahaan')->get();
        return view('admin.kegiatan.index', ['magang' => $magang]);
    }

    public function getDetailkegiatan($id_magang)
    {
        $magang = MagangModel::with(
            'mahasiswa',
            'mahasiswa.keahlian_mahasiswa',
            'mahasiswa.pengalaman',
            'mahasiswa.akun',
            'periode_magang',
            'periode_magang.lowongan_magang'
        )
            ->where('id_magang', $id_magang)
            ->first();

        $dosen = DosenModel::get();
        $activeButton = [];
        if ($magang->status == 'proses') {
            $activeButton = [
                'diterima',
                'ditolak'
            ];
        } else if ($magang->status == 'diterima') {
            $activeButton = [
                'lulus'
            ];
        }
        return view('admin.kegiatan.detail', ['magang' => $magang, 'activeButton' => $activeButton, 'dosen' => $dosen]);
        // return response()->json($dosen);
    }

    public function putKegiatan(Request $request, $id_magang)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $id_dosen = $request->input('id_dosen');
            $status = $request->input('status');

            if ($status == 'diterima') {
                MagangModel::where('id_magang', $id_magang)
                    ->update([
                        'id_dosen' => $id_dosen,
                        'status' => $status
                    ]);
            } else {
                MagangModel::where('id_magang', $id_magang)
                    ->update([
                        'status' => $status
                    ]);
            }
            return response()->json(['success' => true]);
        }
    }
}
