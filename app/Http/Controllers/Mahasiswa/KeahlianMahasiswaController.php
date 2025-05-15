<?php

namespace App\Http\Controllers\Mahasiswa;

use DB;
use App\Http\Controllers\Controller;
use App\Models\AkunModel;
use App\Models\BidangModel;
use App\Models\KeahlianMahasiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class KeahlianMahasiswaController extends Controller
{
    private function idMahasiswa()
    {
        return AkunModel::with('mahasiswa:id_mahasiswa,id_akun')
            ->where('id_akun', Auth::user()->id_akun)
            ->first()
            ->mahasiswa
            ->id_mahasiswa;
    }

    private function bidangDipilih($id_mahasiswa = null)
    {
        $id_mahasiswa = $id_mahasiswa ?? $this->idMahasiswa();
        return KeahlianMahasiswaModel::where('id_mahasiswa', $id_mahasiswa)
            ->pluck('id_bidang')
            ->toArray();
    }

    public function getAddKeahlian()
    {
        $id_mahasiswa = $this->idMahasiswa();
        $bidangDipilih = $this->bidangDipilih($id_mahasiswa);
        $bidang = BidangModel::whereNotIn('id_bidang', $bidangDipilih)->get(['id_bidang', 'nama']);
        return view('tes.addKeahlian', [
            'data' => [
                'bidang' => $bidang,
                'prioritas' => count($bidangDipilih) + 1
            ]
        ]);
    }

    public function getKeahlian($id_keahlian)
    {
        try {
            $pilihanTerakhir = KeahlianMahasiswaModel::findOrFail($id_keahlian);
            $bidangDipilih = $this->bidangDipilih($pilihanTerakhir->id_mahasiswa);
            $bidang = BidangModel::whereNotIn('id_bidang', array_diff($bidangDipilih, [$pilihanTerakhir->id_bidang]))
                ->get(['id_bidang', 'nama']);
            return view('tes.editKeahlian', [
                'data' => [
                    'bidang' => $bidang,
                    'prioritas' => count($bidangDipilih),
                    'pilihan_terakhir' => $pilihanTerakhir
                ]
            ]);
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
                    $id_mahasiswa = $this->idMahasiswa();
                    $bidangDipilih = $this->bidangDipilih($id_mahasiswa);
                    $prioritas = (int) $request->input('prioritas');

                    if ($prioritas == count($bidangDipilih) + 1) {
                        $this->insertKeahlian($id_mahasiswa, $request);
                    } else {
                        $this->updatePrioritas($id_mahasiswa, $prioritas);
                        $this->insertKeahlian($id_mahasiswa, $request);
                    }
                });
                return response()->json(['success' => $request->all()]);
            } catch (\Exception $e) {
                Log::error("Gagal menambahkan keahlian: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    private function insertKeahlian($id_mahasiswa, $request)
    {
        KeahlianMahasiswaModel::insert([
            'id_mahasiswa' => $id_mahasiswa,
            'id_bidang' => $request->input('id_bidang'),
            'prioritas' => $request->input('prioritas'),
            'keahlian' => $request->input('keahlian')
        ]);
    }

    private function updatePrioritas($id_mahasiswa, $prioritas)
    {
        KeahlianMahasiswaModel::where('id_mahasiswa', $id_mahasiswa)
            ->where('prioritas', '>=', $prioritas)
            ->orderBy('prioritas', 'desc')
            ->get()
            ->each(function ($keahlian) {
                $keahlian->prioritas += 1;
                $keahlian->save();
            });
    }

    public function putKeahlian(Request $request, $id_keahlian)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(function () use ($request, $id_keahlian) {
                    $id_mahasiswa = $this->idMahasiswa();
                    $dataLama = KeahlianMahasiswaModel::where('id_mahasiswa', $id_mahasiswa)
                        ->where('id_keahlian_mahasiswa', $id_keahlian)
                        ->first();
                    $prioritasLama = $dataLama->prioritas;
                    $prioritasBaru = (int) $request->input('prioritas');

                    KeahlianMahasiswaModel::where('id_mahasiswa', $id_mahasiswa)
                        ->where('id_keahlian_mahasiswa', $id_keahlian)
                        ->update([
                            'prioritas' => null
                        ]);

                    if ($prioritasBaru < $prioritasLama) {
                        $this->updateTurunPrioritas($id_mahasiswa, $prioritasBaru, $prioritasLama);
                    } elseif ($prioritasBaru > $prioritasLama) {
                        $this->updateNaikPrioritas($id_mahasiswa, $prioritasLama + 1, $prioritasBaru + 1);
                    }

                    $this->updateKeahlian($id_keahlian, $id_mahasiswa, $request);
                });
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                Log::error("Gagal update keahlian: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    private function updateTurunPrioritas($id_mahasiswa, $start, $end)
    {
        KeahlianMahasiswaModel::where('id_mahasiswa', $id_mahasiswa)
            ->whereBetween('prioritas', [$start, $end - 1])
            ->orderBy('prioritas', 'desc')
            ->get()
            ->each(function ($item) {
                $item->prioritas += 1;
                $item->save();
            });
    }

    private function updateNaikPrioritas($id_mahasiswa, $start, $end)
    {
        KeahlianMahasiswaModel::where('id_mahasiswa', $id_mahasiswa)
            ->whereBetween('prioritas', [$start, $end - 1])
            ->orderBy('prioritas', 'asc')
            ->get()
            ->each(function ($item) {
                $item->prioritas -= 1;
                $item->save();
            });
    }

    private function updateKeahlian($id_keahlian, $id_mahasiswa, $request)
    {
        KeahlianMahasiswaModel::where('id_mahasiswa', $id_mahasiswa)
            ->where('id_keahlian_mahasiswa', $id_keahlian)->update([
                    'id_bidang' => $request->input('id_bidang'),
                    'prioritas' => $request->input('prioritas'),
                    'keahlian' => $request->input('keahlian')
                ]);
    }

    public function deleteKeahlian(Request $request, $id_keahlian, $prioritas)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(function () use ($id_keahlian, $prioritas) {
                    $id_mahasiswa = $this->idMahasiswa();
                    KeahlianMahasiswaModel::where('id_mahasiswa', $id_mahasiswa)
                        ->where('id_keahlian_mahasiswa', $id_keahlian)
                        ->delete();

                    KeahlianMahasiswaModel::where('id_mahasiswa', operator: $id_mahasiswa)
                        ->where('prioritas', '>', $prioritas)
                        ->orderBy('prioritas', 'asc')
                        ->get()
                        ->each(function ($item) {
                            $item->prioritas -= 1;
                            $item->save();
                        });
                });
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                Log::error("Gagal menghapus keahlian: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }
}
