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
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Tidak dapat memberikan rekomendasi...',
                text: {!! json_encode(session('error')) !!}
            });
        @endif
    </script>
@endpush
