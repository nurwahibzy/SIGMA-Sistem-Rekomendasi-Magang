@extends('layouts.navbar-landing')

@section('content')
  <div class="card p-5 bg-primary bg-opacity-10 border-0 mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="fw-bold text-primary mb-3">
                    Tarik perhatian rekruter dengan <br>
                    <span class="text-light">Profil Anda</span>
                </h4>
                <p class="text-muted">
                    Buat profil dan bantu perusahaan mengenal Anda lebih mudah.
                    Dapatkan rekomendasi magang yang sesuai pengalaman dan keahlian Anda.
                </p>
            </div>
            <div class="col-md-4 d-flex justify-content-center">
                <img src="{{ asset('template/assets/images/magang.jpg') }}" alt="Foto Profil"
                    class="rounded-circle shadow" style="width: 120px; height: 120px; object-fit: cover;">
            </div>
        </div>
    </div>
@endsection