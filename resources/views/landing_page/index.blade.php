@extends('layouts.template-landing')

@section('content')
    <div class="tab-content" id="detailTabContent">
        <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
           @include('landing_page.dashboard')
        </div>
        <div class="tab-pane fade" id="program" role="tabpanel">
            <h1>a</h1>

        </div>
        <div class="tab-pane fade" id="perusahaan" role="tabpanel">
            <h1>ad</h1>
        </div>
    </div>
@endsection