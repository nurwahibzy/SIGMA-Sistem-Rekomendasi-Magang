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
use Str;
use Validator;

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
        return view('mahasiswa.dokumen.tambah');
    }
    public function getEditDokumen($id_dokumen)
    {
        try {
            $dokumen = DB::transaction(function () use ($id_dokumen) {
                $id_mahasiswa = $this->idMahasiswa();
                $dokumen = DokumenModel::where('id_mahasiswa', $id_mahasiswa)
                    ->where('id_dokumen', $id_dokumen)->first(['id_dokumen', 'nama', 'file_path']);
                return $dokumen;
            });
            if ($dokumen != null) {
                return view('mahasiswa.dokumen.edit', ['dokumen' => $dokumen]);
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
                $results = DB::transaction(function () use ($request) {
                    $validator = Validator::make($request->all(), [
                        'file' => 'nullable|file|mimes:pdf|max:2048',
                        'nama' => 'required|string|max:20',
                    ]);

                    if ($validator->fails()) {
                        return false;
                    }

                    // if ($request->hasFile('file')) {
                    $id_mahasiswa = $this->idMahasiswa();
                    $file = $request->file('file');

                    // $nama = Str::slug($request->input('nama'), '_');
                    $nama = $request->input('nama');
                    $slugifiedName = Str::slug($nama, '_');

                    $filename = $id_mahasiswa . '_' . $slugifiedName . '.' . $file->getClientOriginalExtension();
                    DokumenModel::create([
                        'id_mahasiswa' => $id_mahasiswa,
                        'nama' => $nama,
                        'file_path' => $filename
                    ]);

                    $file->storeAs('public/dokumen', $filename);
                    // }
                    return true;
                });
                return response()->json(['success' => $results]);
            } catch (\Throwable $e) {
                Log::error("Gagal menambahkan Dokumen: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function putEditDokumen(Request $request, $id_dokumen)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $results = DB::transaction(
                    function () use ($request, $id_dokumen) {
                        $validator = Validator::make($request->all(), [
                            'file' => 'nullable|file|mimes:pdf|max:2048',
                            'nama' => 'required|string|max:20',
                        ]);

                        if ($validator->fails()) {
                            return false;
                        }

                        $id_mahasiswa = $this->idMahasiswa();
                        $data = DokumenModel::where('id_mahasiswa', $id_mahasiswa)
                            ->where('id_dokumen', $id_dokumen)
                            ->firstOrFail(['file_path', 'nama']);

                        $nama = $request->input('nama');
                        // $nama = Str::slug($request->input('nama'), '_');
                        $file_path = $data->file_path;

                        if ($request->hasFile('file')) {
                            $file_path = $this->handleFileUpload($request, $data, $id_mahasiswa, $nama);
                        } else if ($data->nama !== $nama) {
                            $file_path = $this->renameFileOnly($file_path, $id_mahasiswa, $nama);
                        }

                        DokumenModel::where('id_mahasiswa', $id_mahasiswa)
                            ->where('id_dokumen', $id_dokumen)
                            ->update([
                                'nama' => $nama,
                                'file_path' => $file_path
                            ]);

                        return true;
                    }
                );
                return response()->json(['success' => $results]);
            } catch (\Throwable $e) {
                Log::error("Gagal update Dokumen: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }
    private function handleFileUpload(Request $request, $file_path, $id_mahasiswa, $nama)
    {
        $file = $request->file('file');
        $slugifiedName = Str::slug($nama, '_');
        $filename = $id_mahasiswa . '_' . $slugifiedName . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->delete("dokumen/{$file_path}");
        $file->storeAs('public/dokumen', $filename);

        return $filename;
    }

    private function renameFileOnly($file_path, $id_mahasiswa, $nama)
    {
        $extension = pathinfo($file_path, PATHINFO_EXTENSION);
        $slugifiedName = Str::slug($nama, '_');
        $file_path_baru = $id_mahasiswa . '_' . $slugifiedName . '.' . $extension;

        if (Storage::disk('public')->exists("dokumen/$file_path")) {
            Storage::disk('public')->move("dokumen/$file_path", "dokumen/$file_path_baru");
        }

        return $file_path_baru;
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
