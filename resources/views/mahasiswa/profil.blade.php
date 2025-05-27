@extends('layouts.tamplate')
@section('content')
    <div class="page-heading">
        <h3>Profil Saya</h3>
    </div>
    <section class="section">
        <div class="row">
            @include('mahasiswa.panel-kiri')
            @include('mahasiswa.panel-kanan')
        </div>
    </section>
@endsection
