@extends('layouts.tamplate')

@section('content')
<div class="page-heading">
    <h3>Rating</h3>
</div>

<div id="main">
    <section class="section">
        <div class="row">
            {{-- Rating Fasilitas --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Fasilitas</h4>
                    </div>
                    <div class="card-body">
                        <div id="rating-fasilitas"></div>
                    </div>
                </div>
            </div>

            {{-- Rating Kedisiplinan --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Kedisiplinan</h4>
                    </div>
                    <div class="card-body">
                        <div id="rating-kedisiplinan"></div>
                    </div>
                </div>
            </div>

            {{-- Rating Tugas --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tugas</h4>
                    </div>
                    <div class="card-body">
                        <div id="rating-tugas"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
    {{-- Include rater-js from local or CDN --}}
    <script src="{{ asset('template/assets/extensions/rater-js/index.js') }}"></script>

    {{-- Custom Rating Config --}}
    <script>
        window.addEventListener("DOMContentLoaded", () => {
            // Fasilitas
            raterJs({
                starSize: 32,
                element: document.querySelector("#rating-fasilitas"),
                rateCallback: function (rating, done) {
                    this.setRating(rating);
                    console.log("Fasilitas rating:", rating);
                    done();
                }
            });

            // Kedisiplinan
            raterJs({
                starSize: 32,
                element: document.querySelector("#rating-kedisiplinan"),
                rateCallback: function (rating, done) {
                    this.setRating(rating);
                    console.log("Kedisiplinan rating:", rating);
                    done();
                }
            });

            // Tugas
            raterJs({
                starSize: 32,
                element: document.querySelector("#rating-tugas"),
                rateCallback: function (rating, done) {
                    this.setRating(rating);
                    console.log("Tugas rating:", rating);
                    done();
                }
            });
        });
    </script>
@endsection
