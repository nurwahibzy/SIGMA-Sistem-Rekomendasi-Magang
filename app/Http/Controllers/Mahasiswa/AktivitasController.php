<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\AktivitasMagangModel;
use App\Models\AkunModel;
use App\Models\MagangModel;
use App\Models\PeriodeMagangModel;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Log;
use Storage;

class AktivitasController extends Controller
{
    // add try catch and transaction
    private function idMahasiswa()
    {
        $id_mahasiswa = AkunModel::with(relations: 'mahasiswa:id_mahasiswa,id_akun')
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_level'])
            ->mahasiswa
            ->id_mahasiswa;
        return $id_mahasiswa;
    }

    public function getMagangDiterima()
    {
        $id_mahasiswa = $this->idMahasiswa();
        $magang = MagangModel::where('id_mahasiswa', $id_mahasiswa)
            ->where('status', 'diterima')
            ->with(
                'periode_magang:id_periode,id_lowongan,nama,tanggal_mulai,tanggal_selesai',
                'periode_magang.lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama,foto_path',
                'periode_magang.lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama',
                'periode_magang.lowongan_magang.bidang:id_bidang,nama',
                'periode_magang.lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
            )
            ->get();
        return view('mahasiswa.aktivitas.magang', [
            'magang' => collect([
                (object)[
                    'id_magang' => 1,
                    'periode_magang' => (object)[
                        'tanggal_mulai' => '2025-08-01',
                        'tanggal_selesai' => '2025-10-31',
                        'lowongan_magang' => (object)[
                            'nama' => 'Intern UI/UX',
                            'perusahaan' => (object)['nama' => 'Tech Corp'],
                            'bidang' => (object)['nama' => 'Design'],
                        ]
                    ]
                ]
            ])
        ]);

        // $magang = PeriodeMagangModel::with(
        // 'lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama,foto_path',
        // 'lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama',
        // 'lowongan_magang.bidang:id_bidang,nama',
        // 'lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
        //     )
        //     ->get(['id_lowongan', 'tanggal_mulai', 'tanggal_selesai']);
        // return response()->json($magang);
    }
    public function getAktivitas($id_magang)
    {
        $id_mahasiswa = $this->idMahasiswa();
        $aktivitas = AktivitasMagangModel::with('magang:id_magang,id_mahasiswa')
            ->where('id_magang', $id_magang)
            ->whereHas('magang', function ($query) use ($id_mahasiswa) {
                $query->where('id_mahasiswa', $id_mahasiswa);
            })
            ->get();
        return view('mahasiswa.aktivitas.index', [
            'aktivitas' => $aktivitas,
            'id_magang' => $id_magang,
        ]);
    }

    public function detail($id)
    {
        $aktivitas = AktivitasMagangModel::findOrFail($id);
        return view('mahasiswa.aktivitas.detail', compact('aktivitas'));
    }

    public function getAddAktivitas($id_magang)
    {
        return view('tes.aktivitas', ['id_magang' => $id_magang]);
    }

    public function getEditAktivitas($id_magang, $id_aktivitas)
    {
        $id_mahasiswa = $this->idMahasiswa();
        $aktivitas = AktivitasMagangModel::with('magang:id_magang,id_mahasiswa')
            ->where('id_aktivitas', $id_aktivitas)
            ->where('id_magang', $id_magang)
            ->whereHas('magang', function ($query) use ($id_mahasiswa) {
                $query->where('id_mahasiswa', $id_mahasiswa);
            })
            ->first();
        $date = Carbon::parse(now())->toDateString();
        return view('mahasiswa.aktivitas.edit', ['id_magang' => $aktivitas->id_magang, 'id_aktivitas' => $aktivitas->id_aktivitas, 'keterangan' => $aktivitas->keterangan]);
    }

    public function postAktivitas(Request $request, $id_magang)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $keterangan = $request->input('keterangan');
                $date = Carbon::parse(now())->toDateString();
                $filename = null;

                DB::transaction(function () use ($request, $id_magang, &$filename, $keterangan, $date) {
                    if ($request->hasFile('file')) {
                        $file = $request->file('file');
                        $id_mahasiswa = $this->idMahasiswa();

                        $filename = $id_magang . '_' . $date . '.' . $file->getClientOriginalExtension();

                        AktivitasMagangModel::with('magang:id_magang,id_mahasiswa')
                            ->where('id_magang', $id_magang)
                            ->whereHas('magang', function ($query) use ($id_mahasiswa) {
                                $query->where('id_mahasiswa', $id_mahasiswa);
                            })->insert([
                                'id_magang' => $id_magang,
                                'tanggal' => $date,
                                'keterangan' => $keterangan,
                                'foto_path' => $filename
                            ]);

                        $file->storeAs('public/aktivitas', $filename);
                    }
                });

                return response()->json([
                    'success' => true,
                    'data' => [
                        'keterangan' => $keterangan,
                        'foto_path' => $filename,
                        'tanggal' => $date,
                    ],
                ]);
            } catch (\Throwable $e) {
                Log::error("Gagal menambahkan Aktivitas: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function putAktivitas(Request $request, $id_magang, $id_aktivitas)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(function () use ($request, $id_aktivitas, $id_magang, &$data) {
                    $id_mahasiswa = $this->idMahasiswa();

                    $data = AktivitasMagangModel::where('id_aktivitas', $id_aktivitas)
                        ->where('id_magang', $id_magang)
                        ->whereHas('magang', function ($query) use ($id_mahasiswa) {
                            $query->where('id_mahasiswa', $id_mahasiswa);
                        })
                        ->first();

                    if (!$data) {
                        throw new \Exception("Data aktivitas tidak ditemukan atau tidak punya akses.");
                    }

                    $keterangan = $request->input('keterangan');

                    if ($request->hasFile('file')) {
                        $this->handleFileUpload($request, $data, $id_aktivitas, $id_magang, $id_mahasiswa, $keterangan);
                    } else {
                        $data->keterangan = $keterangan;
                        $data->save();
                    }
                });

                // Ambil data terbaru untuk dikembalikan ke JS
                $updated = AktivitasMagangModel::find($id_aktivitas);
                return response()->json([
                    'success' => true,
                    'data' => [
                        'id_aktivitas' => $updated->id_aktivitas,
                        'keterangan' => $updated->keterangan,
                        'foto_path' => $updated->foto_path,
                        'tanggal' => $updated->tanggal ?? $updated->created_at
                    ]
                ]);
            } catch (\Throwable $e) {
                Log::error("Gagal update Aktivitas: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
        }
    }


    private function handleFileUpload(Request $request, $data, $id_aktivitas, $id_magang, $id_mahasiswa, $keterangan)
    {

        $file = $request->file('file');
        $date = Carbon::parse(now())->toDateString();
        $filename = $id_magang . '_' . $date . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->delete("aktivitas/{$data->foto_path}");
        $file->storeAs('public/aktivitas', $filename);
        AktivitasMagangModel::with('magang:id_magang,id_mahasiswa')
            ->where('id_aktivitas', $id_aktivitas)
            ->where('id_magang', $id_magang)
            ->whereHas('magang', function ($query) use ($id_mahasiswa) {
                $query->where('id_mahasiswa', $id_mahasiswa);
            })
            ->update([
                'keterangan' => $keterangan,
                'foto_path' => $filename
            ]);
    }

    public function confirm($id_magang, $id_aktivitas)
    {
        $id_mahasiswa = $this->idMahasiswa();

        $aktivitas = AktivitasMagangModel::with('magang:id_magang,id_mahasiswa')
            ->where('id_aktivitas', $id_aktivitas)
            ->where('id_magang', $id_magang)
            ->whereHas('magang', function ($query) use ($id_mahasiswa) {
                $query->where('id_mahasiswa', $id_mahasiswa);
            })
            ->firstOrFail();

        return view('mahasiswa.aktivitas.confirm', compact('aktivitas', 'id_magang'));
    }

    public function deleteAktivitas(Request $request, $id_magang, $id_aktivitas)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(
                    function () use ($request, $id_magang, $id_aktivitas) {
                        $id_mahasiswa = $this->idMahasiswa();
                        $data = AktivitasMagangModel::with('magang:id_magang,id_mahasiswa')
                            ->where('id_aktivitas', $id_aktivitas)
                            ->where('id_magang', $id_magang)
                            ->whereHas('magang', function ($query) use ($id_mahasiswa) {
                                $query->where('id_mahasiswa', $id_mahasiswa);
                            })
                            ->firstOrFail(['foto_path']);

                        $file_path = $data->foto_path;

                        if (Storage::disk('public')->exists("aktivitas/$file_path")) {
                            Storage::disk('public')->delete("aktivitas/$file_path");
                        }

                        AktivitasMagangModel::with('magang:id_magang,id_mahasiswa')
                            ->where('id_aktivitas', $id_aktivitas)
                            ->where('id_magang', $id_magang)
                            ->whereHas('magang', function ($query) use ($id_mahasiswa) {
                                $query->where('id_mahasiswa', $id_mahasiswa);
                            })
                            ->delete();
                    }
                );
                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal update Aktivitas: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }
}
