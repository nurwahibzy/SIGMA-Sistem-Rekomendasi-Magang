@extends('layouts.tamplate')
@section('content')
    <div class="page-heading">
        <h3>My Profile</h3>
    </div>
    <section class="section">
        <div class="row">
            @include('mahasiswa.akun.edit-panel-kiri')
            @include('mahasiswa.akun.edit-panel-kanan')
        </div>
    </section>
@endsection
