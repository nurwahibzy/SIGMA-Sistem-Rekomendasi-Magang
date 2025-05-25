@extends('layouts.tamplate')
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>My Profile</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dosen/dashboard/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <div class="avatar avatar-2xl">
                                <img src="{{ Storage::exists('public/profil/akun/' . Auth::user()->foto_path)
                                    ? asset('storage/profil/akun/' . Auth::user()->foto_path)
                                    : asset('template/assets/images/mhs.jpeg') }}" 
                                    alt="Profile Picture"
                                    class="rounded-circle"
                                    style="width: 100px; height: 100px; border: 5px solid blue; object-fit: cover;">
                            </div>

                            <h3 class="mt-3">{{ Auth::user()->dosen->nama ?? 'N/A' }}</h3>
                            <p class="text-small">NIP: {{ Auth::user()->id_user ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form id="form-profile" class="text-start">
                            @csrf
                            <div class="form-group">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" name="nip" id="nip" class="form-control" 
                                    value="{{ Auth::user()->id_user ?? '-' }}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" name="nama" id="nama" class="form-control" 
                                    value="{{ Auth::user()->dosen->nama ?? '-' }}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" 
                                    value="{{ Auth::user()->dosen->email ?? '-' }}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="no_telepon" class="form-label">Telepon</label>
                                <input type="text" name="no_telepon" id="no_telepon" class="form-control" 
                                    value="{{ Auth::user()->dosen->telepon ?? '-' }}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="alamat" class="form-label">Alamat</label>
                                <input type="text" name="alamat" id="alamat" class="form-control" 
                                    value="{{ Auth::user()->dosen->alamat ?? '-' }}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" 
                                    value="{{ Auth::user()->dosen->tanggal_lahir ?? '' }}" disabled>
                            </div>
                            <div class="form-group">
                                <a href="{{ url('dosen/profil/edit') }}" id="btn-edit-profile" 
                                    class="btn btn-primary">
                                    <i class="bi bi-pencil-square"></i> Edit Profile
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection