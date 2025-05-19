<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProdiModel;
use Yajra\DataTables\DataTables;

class ProdiController extends Controller
{

    public function index()
    {
        return view('admin.prodi.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = ProdiModel::select('id_prodi', 'nama_jurusan', 'nama_prodi');
            return DataTables::of($data)
                ->addColumn('aksi', function ($row) {
                    return '<button class="btn btn-sm btn-info">Detail</button>';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jurusan' => 'required|string|min:2',
            'nama_prodi' => 'required|string|min:2',
        ]);

        ProdiModel::create($validated);

        return response()->json(['message' => 'Berhasil disimpan']);
    }

}
