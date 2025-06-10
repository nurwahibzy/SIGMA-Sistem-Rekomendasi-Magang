<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkunModel;
use App\Models\BidangModel;
use App\Models\DosenModel;
use App\Models\JenisPerusahaanModel;
use App\Models\MagangModel;
use Auth;
use DB;
use Illuminate\Http\Request;
use Storage;
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


    // public function getDetailkegiatan($id_magang)
    // {
    //     $data = DB::transaction(function () use ($id_magang) {
    //         $magang = MagangModel::with(
    //             'mahasiswa',
    //             'mahasiswa.keahlian_mahasiswa',
    //             'mahasiswa.pengalaman',
    //             'mahasiswa.akun',
    //             'periode_magang',
    //             'periode_magang.lowongan_magang'
    //         )->where('id_magang', $id_magang)->firstOrFail();

    // $dosen = DosenModel::whereHas('akun', function ($query) {
    //     $query->where('status', 'aktif');
    // })
    //     ->get();

    //         $activeButton = [];

    //         if ($magang->status === 'proses') {
    //             $activeButton = ['diterima', 'ditolak'];
    //         } elseif ($magang->status === 'diterima') {
    //             $activeButton = ['lulus'];
    //         }

    //         return [
    //             'magang' => $magang,
    //             'activeButton' => $activeButton,
    //             'dosen' => $dosen
    //         ];
    //     });

    //     return view('admin.kegiatan.detail', $data);
    //     // return view('admin.kegiatan.tes', $data);
    //     // return response()->json($dosen);
    // }


    private function allDataProfil($id_akun)
    {
        $akun = AkunModel::with(
            'mahasiswa:id_mahasiswa,id_akun,id_prodi,nama,alamat,telepon,tanggal_lahir,email',
            'mahasiswa.prodi:id_prodi,nama_prodi,nama_jurusan',
            'mahasiswa.pengalaman:id_pengalaman,id_mahasiswa,deskripsi',
            'mahasiswa.dokumen:id_dokumen,id_mahasiswa,nama,file_path',
            'mahasiswa.preferensi_lokasi_mahasiswa:id_preferensi_lokasi,id_mahasiswa,provinsi,daerah',
            'mahasiswa.preferensi_perusahaan_mahasiswa:id_preferensi_perusahaan,id_mahasiswa,id_jenis',
            'mahasiswa.preferensi_perusahaan_mahasiswa.jenis_perusahaan:id_jenis,jenis',
            'mahasiswa.keahlian_mahasiswa:id_keahlian_mahasiswa,id_mahasiswa,id_bidang,prioritas,keahlian',
            'mahasiswa.keahlian_mahasiswa.bidang:id_bidang,nama',
        )
            ->where('id_akun', $id_akun)
            ->first(['id_akun', 'id_user']);
        return $akun;
    }

    public function getDetailkegiatan($id_magang)
    {
        $magang = MagangModel::with('mahasiswa.akun')
            ->where('id_magang', $id_magang)
            ->first();

        if (!$magang) {
            return view(
                'admin.kegiatan.detail'
            );
        }

        $id_akun = $magang->mahasiswa->akun->id_akun;
        $akun = $this->allDataProfil($id_akun);
        $bidang = BidangModel::get();
        $jenis = JenisPerusahaanModel::get();
        $magang = MagangModel::with(
            'mahasiswa',
            'mahasiswa.keahlian_mahasiswa',
            'mahasiswa.pengalaman',
            'mahasiswa.akun',
            'periode_magang.lowongan_magang.perusahaan.jenis_perusahaan'
        )->where('id_magang', $id_magang)->firstOrFail();

        $dosen = DosenModel::whereHas('akun', function ($query) {
            $query->where('status', 'aktif');
        })
            ->get();

        $lowongan = DB::table('lowongan_magang')
            ->select(
                'lowongan_magang.id_lowongan',
                DB::raw("COALESCE(AVG(penilaian.tugas), 0) as tugas"),
                DB::raw("COALESCE(AVG(penilaian.pembinaan), 0) as pembinaan"),
                DB::raw("COALESCE(AVG(penilaian.fasilitas), 0) as fasilitas"),
                DB::raw("CASE WHEN COUNT(penilaian.id_penilaian) = 0 THEN 'baru' ELSE 'lama' END as status")
            )
            ->leftJoin('periode_magang', 'periode_magang.id_lowongan', '=', 'lowongan_magang.id_lowongan')
            ->leftJoin('magang', 'magang.id_periode', '=', 'periode_magang.id_periode')
            ->leftJoin('penilaian', 'penilaian.id_magang', '=', 'magang.id_magang')
            ->where('lowongan_magang.id_lowongan', $magang->periode_magang->id_lowongan)
            ->groupBy(
                'lowongan_magang.id_lowongan'
            )
            ->first();

        $activeButton = [];

        if ($magang->status === 'proses') {
            $activeButton = ['diterima', 'ditolak'];
        } elseif ($magang->status === 'diterima') {
            $activeButton = ['lulus'];
        }
        return view(
            'admin.kegiatan.detail',
            [
                'dosen' => $dosen,
                'activeButton' => $activeButton,
                'magang' => $magang,
                'mahasiswa' => $akun->mahasiswa,
                'pengalaman' => $akun->mahasiswa->pengalaman,
                'bidang' => $bidang,
                'keahlian' => $akun->mahasiswa->keahlian_mahasiswa->sortBy('prioritas'),
                'lowongan' => $lowongan,
                'periode' =>$magang->periode_magang,
                'kompetensi' => $akun->mahasiswa->kompetensi,
                'jenis' => $jenis,
                'preferensi_perusahaan' => $akun->mahasiswa->preferensi_perusahaan_mahasiswa,
                'preferensi_lokasi' => $akun->mahasiswa->preferensi_lokasi_mahasiswa,
                'dokumen' => $akun->mahasiswa->dokumen,
            ]
        );
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
            } else if ($status === 'diterima') {
                $dataToUpdate['id_dosen'] = $id_dosen;
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
        $magang = MagangModel::with('aktivitas_magang')
            ->where('id_magang', $id_magang)
            ->first();

        if ($magang) {
            if ($magang->status == 'proses' || $magang->status == 'diterima') {
                return response()->json(['success' => false, 'message' => 'Kegiatan bisa dihapus jika statusnya lulus atau ditolak']);
            }
        }

        foreach ($magang->aktivitas_magang as $aktivitas) {
            $foto_path = $aktivitas->foto_path;

            if (Storage::disk('public')->exists("aktivitas/$foto_path")) {
                Storage::disk('public')->delete("aktivitas/$foto_path");
            }
        }

        MagangModel::where('id_magang', $id_magang)->delete();
        return response()->json(['success' => true]);
    }
}
