<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MagangModel;
use Illuminate\Http\Request;

class MagangController extends Controller
{
    public function getDashboard()
    {
        return view('welcome');
        // return response()->json('a');
    }

    public function putMagang(Request $request, $id_magang)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $id_dosen = $request->input('id_dosen');
            $status = $request->input('status');

            MagangModel::where('id_magang', $id_magang)
                ->update([
                    'id_dose' => $id_dosen,
                    'status' => $status
                ]);
        }
    }
}
