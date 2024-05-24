@extends('front.layouts.master')
@section('title', 'Thank You')
@section('main-content')
    <div class="d-flex  p-v-10 flex-column justify-content-between">
        <div class="d-none d-md-flex p-h-40">
            <img style="width: 200px ; hight:50px"
                src="@if (!empty($siteSetting) && !empty($siteSetting->logo) && file_exists('uploads/setting/' . $siteSetting->logo)) {{ url('uploads/setting/' . $siteSetting->logo) }} @else {{ asset('assets/images/logo/logo.png') }} @endif "
                alt="Logo">
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="p-v-10">
                        <h1 class=" display-1 text-primary lh-1-2"></h1>
                        <h4 class="font-weight-semibold display-4 text-primary lh-6-2">Thank you!</h4>
                        <p class="lead m-b-20">Thank You for submitting survey information.</p>
                        <a href="{{ route('front.campaign.list') }}" class="btn btn-primary btn-tone">Go Back</a>
                    </div>
                </div>
                <div class="col-md-6 m-l-auto">
                    <img class="img-fluid w-60" src="{{ asset('assets/images/others/thankyou.png') }}" alt="">
                </div>
            </div>
        </div>

    </div>
    </div>
    </div>

@endsection
@section('js')
@endsection
