@extends('layouts.tamplate')
@section('content')
    <div class="page-heading">
        <h3>My Profile</h3>
    </div>

    <section class="section">
        <div class="row">
            <!-- Kolom Foto -->
            <div class="col-md-4">
                <div class="card text-center p-4">
                    <img src="{{ Storage::exists('public/profil/akun/' . Auth::user()->foto_path)
                        ? asset('storage/profil/akun/' . Auth::user()->foto_path)
                        : asset('template/assets/images/mhs.jpeg') }}"
                        alt="Profile Picture"
                        class="rounded-circle mx-auto mb-3"
                        width="100" height="100"
                        style="border: 5px solid blue;">
                    <h5 class="mb-0">{{ Auth::user()->admin->first_name ?? '-' }} {{ Auth::user()->admin->last_name ?? '' }}</h5>
                    <small class="text-muted">{{ Auth::user()->admin->email ?? '-' }}</small>
                </div>
            </div>

            <!-- Kolom Form -->
            <div class="col-md-8">
                <div class="card p-4">
                    <form id="form-profile" class="row g-3">
                        @csrf

                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <input type="role" class="form-control" id="role" value="{{ Auth::user()->admin->akun->level->role }}"
                                disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ID</label>
                            <input type="text" class="form-control" id="nim" value="{{ Auth::user()->id_user ?? '-' }}"
                                disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" value="{{ Auth::user()->admin->nama ?? '-' }}"
                                disabled>
                        </div>
                       
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir"
                                value="{{ Auth::user()->admin->tanggal_lahir ?? '-' }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" value="{{ Auth::user()->admin->email }}"
                                disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telepon</label>
                            <input type="text" class="form-control" id="no_telepon"
                                value="{{ Auth::user()->admin->telepon ?? '-' }}" disabled>
                        </div>
                        
                         
                       
                        <div class="col-md-12">
                            <label class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="alamat"
                                value="{{ Auth::user()->admin->alamat ?? '-' }}" disabled>
                        </div>

                        <div class="col-12 text-end">
                            <a href="{{ url('admin/profil/edit') }}" class="btn btn-primary">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
