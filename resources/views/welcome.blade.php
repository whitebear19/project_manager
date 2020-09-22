@extends('layouts.app')

@section('content')
    <div class="banner py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 vertical-middle">
                    <div>
                        <h1 class="home_title">More than just messaging</h1>
                        <p>Run your business with Flock</p>


                    </div>
                </div>
                <div class="col-md-6 vertical-middle">
                    <div class="bannar_image">
                        <img src="{{ asset('/img/home.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
