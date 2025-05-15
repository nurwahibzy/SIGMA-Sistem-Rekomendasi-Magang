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
        return response()->json($magang);
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
        
        return response()->json($aktivitas);
        
    }



    public function getAddAktivitas()
    {
        return view();
    }

    public function getEditAktivitas($id_magang, $id_aktivitas)
    {
        $aktivitas = AktivitasMagangModel::where('id_aktivitas', $id_aktivitas)->first();
        $date = Carbon::parse(now())->toDateString();
        return response()->json([
            'aktivitas' => $aktivitas,
            'date' => $date
        ]);
        // return response()->json(Carbon::parse(now())->toDateString());
    }

    public function postAktivitas(Request $request, $id_magang)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(function () use ($request, $id_magang) {
                    if ($request->hasFile('file')) {
                        $file = $request->file('file');
                        $keterangan = $request->input('keterangan');
                        $date = Carbon::parse(now())->toDateString();

                        $filename = $id_magang . '_' . $date . '.' . $file->getClientOriginalExtension();
                        AktivitasMagangModel::insert([
                            'id_magang' => $id_magang,
                            'tangga;' => $date,
                            'keterangan;' => $keterangan,
                            'foto_path' => $filename
                        ]);

                        $file->storeAs('public/aktivitas', $filename);
                    }
                });
                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal menambahkan Aktivitas: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }
    public function putAktivitas(Request $request, $id_aktivitas, $id_magang)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(
                    function () use ($request, $id_aktivitas, $id_magang) {
                        $data = AktivitasMagangModel::where('id_aktivitas', $id_aktivitas)
                            ->firstOrFail(['foto_path', 'nama']);

                        $keterangan = $request->input('keterangan');

                        if ($request->hasFile('file')) {
                            $this->handleFileUpload($request, $data, $id_aktivitas, $id_magang, $keterangan);
                        } else {
                            AktivitasMagangModel::where('id_aktivitas', $id_aktivitas)
                                ->update(attributes: [
                                    'keterangan' => $keterangan
                                ]);
                        }
                    }
                );
                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal update Dokumen: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    private function handleFileUpload(Request $request, $data, $id_aktivitas, $id_magang, $keterangan)
    {

        $file = $request->file('file');
        $date = Carbon::parse(now())->toDateString();
        $filename = $id_magang . '_' . $date . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->delete("aktivitas/{$data->foto_path}");
        $file->storeAs('public/aktivitas', $filename);
        AktivitasMagangModel::where('id_aktivitas', $id_aktivitas)
            ->update([
                'keterangan' => $keterangan,
                'file_path' => $filename
            ]);
    }
    public function deleteAktivitas(Request $request, $id_aktivitas)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(
                    function () use ($request, $id_aktivitas) {
                        $data = AktivitasMagangModel::where('id_aktivitas', $id_aktivitas)
                            ->firstOrFail(['foto_path']);

                        $file_path = $data->foto_path;

                        if (Storage::disk('public')->exists("aktivitas/$file_path")) {
                            Storage::disk('public')->delete("aktivitas/$file_path");
                        }

                        AktivitasMagangModel::where('id_dokumen', $id_aktivitas)
                            ->delete();
                    }
                );
                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal update Dokumen: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }
}
