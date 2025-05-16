<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\AkunModel;
use App\Models\BidangModel;
use App\Models\KeahlianDosenModel;
use Auth;
use DB;
use Illuminate\Http\Request;
use Log;

class KeahlianDosenController extends Controller
{
    private function idDosen()
    {
        $id_dosen = AkunModel::with(relations: 'dosen:id_dosen,id_akun')
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_level'])
            ->dosen
            ->id_dosen;
        return $id_dosen;
    }

    public function getAddKeahlian()
    {
        try {
            $bidang = BidangModel::get(['id_bidang', 'nama']);
            return response()->json($bidang);
        } catch (\Exception $e) {
            Log::error("Gagal mendapatkan data bidang: " . $e->getMessage());
            abort(500, "Terjadi kesalahan.");
        }
    }

    public function getKeahlian($id_keahlian)
    {
        try {
            $data = DB::transaction(function () use ($id_keahlian) {
                $id_dosen = $this->idDosen();
                $bidang = BidangModel::get(['id_bidang', 'nama']);
                $keahlian = KeahlianDosenModel::where('id_dosen', $id_dosen)
                    ->where('id_keahlian_dosen', $id_keahlian)
                    ->first(['id_keahlian_dosen', 'id_bidang', 'keahlian']);

                $data = [
                    'bidang' => $bidang,
                    'keahlian' => $keahlian
                ];

                return $data;
            });
            if ($data) {
                // return view('tes.addKeahlian', ['data' => $data]);
                return response()->json($data);
            }
        } catch (\Exception $e) {
            Log::error("Gagal mendapatkan data keahlian: " . $e->getMessage());
            abort(500, "Terjadi kesalahan.");
        }
    }

    public function postKeahlian(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(function () use ($request) {
                    $id_dosen = $this->idDosen();
                    $id_bidang = $request->input('id_bidang');
                    $keahlian = $request->input('keahlian');

                    KeahlianDosenModel::insert([
                        'id_dosen' => $id_dosen,
                        'id_bidang' => $id_bidang,
                        'keahlian' => $keahlian
                    ]);
                });
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                Log::error("Gagal menambahkan keahlian: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function putKeahlian(Request $request, $id_keahlian)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(function () use ($request, $id_keahlian) {
                    $id_dosen = $this->idDosen();
                    $id_bidang = $request->input('id_bidang');
                    $keahlian = $request->input('keahlian');

                    KeahlianDosenModel::where('id_dosen', $id_dosen)
                        ->where('id_keahlian_dosen', $id_keahlian)
                        ->update([
                            'id_bidang' => $id_bidang,
                            'keahlian' => $keahlian
                        ]);
                });
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                Log::error("Gagal update keahlian: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function deleteKeahlian(Request $request, $id_keahlian)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(function () use ($request, $id_keahlian) {
                    $id_dosen = $this->idDosen();

                    KeahlianDosenModel::where('id_dosen', $id_dosen)
                        ->where('id_keahlian_dosen', $id_keahlian)
                        ->delete();
                });
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                Log::error("Gagal menghapus keahlian: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }
}
