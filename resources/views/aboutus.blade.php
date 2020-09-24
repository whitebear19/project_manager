@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="text-center mt-50">About Us</h1>
        </div>
        <br>
        <div class="col-md-6">
            <img style="width: 100%;" src="{{ asset('img/aboutus.jpg') }}" alt="" srcset="">
        </div>
        <div class="col-md-6">
            <br>
            <p class="fs-18">
                Project Manager is an online tool for managing projects and personal tasks.
                That may sound rather prosaic.
            </p>
            <p class="fs-18">
                But this increasingly popular app often inspires the sort of passion usually reserved for consumer apps.
            </p>
            <p class="fs-18">
                It’s the kind of business software that slips into businesses through the backdoor, just because individual employees like how it works.
            </p>
        </div>
    </div>
</div>
@endsection
