@extends('layouts.tamplate')
@section('content')
    <div class="page-heading">
        <h3>My Profile</h3>
    </div>
    <section class="section">
        <div class="row">

            <div class="col-md-4">
                <div class="position-sticky" style="top: 90px;">
                    <div class="card p-4 text-center">
                        <img src="{{ asset('storage/profil/akun/' . Auth::user()->foto_path ) ?? asset('template/assets/images/mhs.jpeg') }}" alt="Profile Picture"
                            class="rounded-circle mx-auto d-block mb-3" width="100" height="100"
                            style="border: 5px solid blue;">
                        <form id="form-left-panel" class="text-start mt-3">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label">NIM</label>
                                <input type="text" class="form-control" id="nim" value="{{ Auth::user()->id_user ?? '-' }}"
                                    disabled>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama"
                                    value="{{ Auth::user()->admin->nama ?? '-' }}" disabled>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" value="******" disabled>
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah.</small>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamat"
                                    value="{{ Auth::user()->admin->alamat ?? '-' }}" disabled>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">No Telepon</label>
                                <input type="text" class="form-control" id="no_telepon"
                                    value="{{ Auth::user()->admin->telepon ?? '-' }}" disabled>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir"
                                    value="{{ Auth::user()->admin->tanggal_lahir ?? '-' }}" disabled>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" value="{{ Auth::user()->admin->email }}"
                                    disabled>
                            </div>
                            <a href="{{ url('admin/profil/edit') }}" id="btn-edit-profile" class="btn btn-outline-primary mt-2 w-100">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection