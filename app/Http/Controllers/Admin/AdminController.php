<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getAdmin()
    {
        $admin = AdminModel::with('akun')
        ->get();

        return view('admin.admin.index', ['admin' => $admin]);
    }

}
