@extends('layouts.template-landing')

@section('content')
    <div class="tab-content" id="detailTabContent">
        <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
           @include('landing_page.beranda')
        </div>
        <div class="tab-pane fade" id="program" role="tabpanel">
           @include('landing_page.program-magang')

        </div>
    </div>
@endsection