<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\AkunModel;
use App\Models\EvaluasiMagangModel;
use Auth;
use DB;
use Illuminate\Http\Request;
use Log;

class EvaluasiController extends Controller
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
    // public function getAllEvaluasi($id_magang){
    //     $id_dosen = $this->idDosen();
    //     $evaluasi = EvaluasiMagangModel::with('magang:id_magang,id_dosen')
    //     ->where('id_magang', $id_magang)
    //     ->whereHas('magang', function ($query) use ($id_dosen) {
    //         $query->where('id_dosen', $id_dosen);
    //     })
    //     ->get();

    // return response()->json($evaluasi);
    // }

    public function getAddEvaluasi()
    {

    }

    public function getEvaluasi($id_magang)
    {
        $id_dosen = $this->idDosen();
        $evaluasi = EvaluasiMagangModel::with('magang:id_magang,id_dosen')
            ->where('id_magang', $id_magang)
            ->whereHas('magang', function ($query) use ($id_dosen) {
                $query->where('id_dosen', $id_dosen);
            })
            ->get();

        return response()->json($evaluasi);
    }

    public function postEvaluasi(Request $request, $id_magang)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(function () use ($request, $id_magang) {
                    $id_dosen = $this->idDosen();
                    $feedback = $request->input('feedback');

                    EvaluasiMagangModel::with('magang:id_magang,id_dosen')
                        ->where('id_magang', $id_magang)
                        ->whereHas('magang', function ($query) use ($id_dosen) {
                            $query->where('id_dosen', $id_dosen);
                        })
                        ->insert([
                            'id_magang' => $id_magang,
                            'feedback' => $feedback
                        ]);
                });
                return response()->json(['success' => $request->all()]);
            } catch (\Exception $e) {
                Log::error("Gagal menambahkan evaluasi: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function getEditEvaluasi($id_magang, $id_evaluasi)
    {
        $id_dosen = $this->idDosen();
        $evaluasi = EvaluasiMagangModel::with('magang:id_magang,id_dosen')
            ->where('id_magang', $id_magang)
            ->where('id_evaluasi', $id_evaluasi)
            ->whereHas('magang', function ($query) use ($id_dosen) {
                $query->where('id_dosen', $id_dosen);
            })
            ->first(['id_magang', 'id_evaluasi', 'feedback']);

        return response()->json($evaluasi);
    }

    public function putEvaluasi(Request $request, $id_magang, $id_evaluasi)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(function () use ($request, $id_magang, $id_evaluasi) {
                    $id_dosen = $this->idDosen();
                    $feedback = $request->input('feedback');

                    EvaluasiMagangModel::with('magang:id_magang,id_dosen')
                        ->where('id_magang', $id_magang)
                        ->where('id_evaluasi', $id_evaluasi)
                        ->whereHas('magang', function ($query) use ($id_dosen) {
                            $query->where('id_dosen', $id_dosen);
                        })
                        ->update([
                            'feedback' => $feedback
                        ]);
                });
                return response()->json(['success' => $request->all()]);
            } catch (\Exception $e) {
                Log::error("Gagal update evaluasi: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }


    public function deleteEvaluasi(Request $request, $id_magang, $id_evaluasi)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(function () use ($request, $id_magang, $id_evaluasi) {
                    $id_dosen = $this->idDosen();

                    EvaluasiMagangModel::with('magang:id_magang,id_dosen')
                        ->where('id_magang', $id_magang)
                        ->where('id_evaluasi', $id_evaluasi)
                        ->whereHas('magang', function ($query) use ($id_dosen) {
                            $query->where('id_dosen', $id_dosen);
                        })
                        ->delete();
                });
                return response()->json(['success' => $request->all()]);
            } catch (\Exception $e) {
                Log::error("Gagal menghapus evaluasi: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }
}
