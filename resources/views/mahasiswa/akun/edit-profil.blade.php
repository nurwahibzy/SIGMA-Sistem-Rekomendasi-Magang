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
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <script>
        $('#nama_provinsi').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
        });

        $('#nama_daerah').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
        });

    </script>
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');

                $('#myModal').find('#id_bidang').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#myModal'),
                    placeholder: $('#id_bidang').data('placeholder'),
                    width: '100%'
                });

                $('#myModal').find('#prioritas').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#myModal'),
                    placeholder: $('#prioritas').data('placeholder'),
                    width: '100%'
                });
            });
        }
    </script>
@endpush