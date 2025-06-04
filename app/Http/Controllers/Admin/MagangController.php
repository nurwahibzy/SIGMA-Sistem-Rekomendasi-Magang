<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DosenModel;
use App\Models\JenisPerusahaanModel;
use App\Models\MagangModel;
use Auth;
use DB;
use Illuminate\Http\Request;
use Validator;

class MagangController extends Controller
{
    public function getDashboard()
    {
        // return view('admin.index');
        return redirect('/admin/admin');
    }

    public function getKegiatan()
    {
        $magang = MagangModel::with(
            'mahasiswa',
            'mahasiswa.akun',
            'dosen',
            'periode_magang',
            'periode_magang.lowongan_magang',
            'periode_magang.lowongan_magang.perusahaan'
        )
            ->orderByRaw("FIELD(status, 'proses', 'diterima', 'lulus', 'ditolak')")
            ->orderByDesc('tanggal_pengajuan')
            ->get();

        $proses = MagangModel::where('status', 'proses')->count();
        $diterima = MagangModel::where('status', 'diterima')->count();
        $lulus = MagangModel::where('status', 'lulus')->count();
        $ditolak = MagangModel::where('status', 'ditolak')->count();

        return view('admin.kegiatan.index', [
            'magang' => $magang,
            'proses' => $proses,
            'diterima' => $diterima,
            'lulus' => $lulus,
            'ditolak' => $ditolak
        ]);
    }


    public function getDetailkegiatan($id_magang)
    {
        $data = DB::transaction(function () use ($id_magang) {
            $magang = MagangModel::with(
                'mahasiswa',
                'mahasiswa.keahlian_mahasiswa',
                'mahasiswa.pengalaman',
                'mahasiswa.akun',
                'periode_magang',
                'periode_magang.lowongan_magang'
            )->where('id_magang', $id_magang)->firstOrFail();

            $dosen = DosenModel::whereHas('akun', function ($query) {
                $query->where('status', 'aktif');
            })
                ->get();

            $activeButton = [];

            if ($magang->status === 'proses') {
                $activeButton = ['diterima', 'ditolak'];
            } elseif ($magang->status === 'diterima') {
                $activeButton = ['lulus'];
            }

            return [
                'magang' => $magang,
                'activeButton' => $activeButton,
                'dosen' => $dosen
            ];
        });

        return view('admin.kegiatan.detail', $data);
        // return response()->json($dosen);
    }

    public function putKegiatan(Request $request, $id_magang)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi dasar status
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:proses,diterima,ditolak,lulus',
                'alasan_penolakan' => $request->input('status') === 'ditolak' ? 'required|string|max:500' : 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false]);
            }

            $status = $request->input('status');
            $id_dosen = $request->input('id_dosen');
            $alasan_penolakan = $request->input('alasan_penolakan');

            $dataToUpdate = [
                'status' => $status,
            ];

            if ($status === 'ditolak') {
                $dataToUpdate['alasan_penolakan'] = $alasan_penolakan;
                $dataToUpdate['id_dosen'] = null;
            } else {
                $dataToUpdate['alasan_penolakan'] = null;
            }

            MagangModel::where('id_magang', $id_magang)->update($dataToUpdate);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid request'], 400);
    }

    public function deleteKegiatan(Request $request, $id_magang)
    {
        MagangModel::where('id_magang', $id_magang)->delete();
        return response()->json(['success' => true]);
    }
}
