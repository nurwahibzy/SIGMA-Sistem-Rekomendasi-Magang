<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\AkunModel;
use App\Models\DokumenModel;
use Auth;
use DB;
use Illuminate\Http\Request;
use Log;
use Storage;

class DokumenController extends Controller
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
    public function getAddDokumen()
    {
        return view('tes.dokumen');
    }
    public function getDokumen($id_dokumen)
    {
        try {
            $dokumen = DB::transaction(function () use ($id_dokumen) {
                $id_mahasiswa = $this->idMahasiswa();
                $dokumen = DokumenModel::where('id_mahasiswa', $id_mahasiswa)
                    ->where('id_dokumen', $id_dokumen)->first(['id_dokumen', 'nama', 'file_path']);
                return $dokumen;
            });
            if ($dokumen != null) {
                return view('tes.editDokumen', ['dokumen' => $dokumen]);
            }
        } catch (\Throwable $e) {
            Log::error("Gagal menambahkan Dokumen: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
        }
    }

    public function postDokumen(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(function () use ($request) {
                    if ($request->hasFile('file')) {
                        $id_mahasiswa = $this->idMahasiswa();
                        $file = $request->file('file');
                        $nama = $request->input('nama');

                        $filename = $id_mahasiswa . '_' . $nama . '.' . $file->getClientOriginalExtension();
                        DokumenModel::insert([
                            'id_mahasiswa' => $id_mahasiswa,
                            'nama' => $nama,
                            'file_path' => $filename
                        ]);

                        $file->storeAs('public/dokumen', $filename);
                    }
                });
                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal menambahkan Dokumen: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function putDokumen(Request $request, $id_dokumen)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(
                    function () use ($request, $id_dokumen) {
                        $id_mahasiswa = $this->idMahasiswa();
                        $data = DokumenModel::where('id_mahasiswa', $id_mahasiswa)
                            ->where('id_dokumen', $id_dokumen)
                            ->firstOrFail(['file_path', 'nama']);

                        $nama = $request->input('nama');

                        if ($request->hasFile('file')) {
                            $this->handleFileUpload($request, $data, $id_mahasiswa, $id_dokumen, $nama);
                        }

                        if ($data->nama !== $nama) {
                            $this->renameFileOnly($data, $id_mahasiswa, $id_dokumen, $nama);
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
    private function handleFileUpload(Request $request, $data, $id_mahasiswa, $id_dokumen, $nama)
    {
        $file = $request->file('file');
        $filename = $id_mahasiswa . '_' . $nama . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->delete("dokumen/{$data->file_path}");
        $file->storeAs('public/dokumen', $filename);
        DokumenModel::where('id_mahasiswa', $id_mahasiswa)
            ->where('id_dokumen', $id_dokumen)
            ->update([
                'nama' => $nama,
                'file_path' => $filename
            ]);
    }

    private function renameFileOnly($data, $id_mahasiswa, $id_dokumen, $nama)
    {
        $lama = $data->file_path;
        $extension = pathinfo($lama, PATHINFO_EXTENSION);
        $file_path_baru = $id_mahasiswa . '_' . $nama . '.' . $extension;

        if (Storage::disk('public')->exists("dokumen/$lama")) {
            Storage::disk('public')->move("dokumen/$lama", "dokumen/$file_path_baru");

            DokumenModel::where('id_mahasiswa', $id_mahasiswa)
                ->where('id_dokumen', $id_dokumen)
                ->update([
                    'nama' => $nama,
                    'file_path' => $file_path_baru
                ]);
        }
    }

    public function deleteDokumen(Request $request, $id_dokumen)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(
                    function () use ($request, $id_dokumen) {

                        $id_mahasiswa = $this->idMahasiswa();
                        $dokumen = DokumenModel::where('id_mahasiswa', $id_mahasiswa)
                            ->where('id_dokumen', $id_dokumen)
                            ->firstOrFail(['file_path']);

                        $file_path = $dokumen->file_path;

                        if (Storage::disk('public')->exists("dokumen/$file_path")) {
                            Storage::disk('public')->delete("dokumen/$file_path");
                        }

                        DokumenModel::where('id_mahasiswa', $id_mahasiswa)
                            ->where('id_dokumen', $id_dokumen)
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
