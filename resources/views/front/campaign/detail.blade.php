@extends('front.layouts.master')
@section('title', 'Task')
@section('main-content')

    <style>
        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .card-text {
            font-size: 1rem;
        }
    </style>

    <!-- Content Wrapper START -->
    <div class="main-content">
        {{-- @include('company.includes.message') --}}
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item">Campaign</span>
                    <span class="breadcrumb-item active">View</span>
                </nav>
            </div>
        </div>
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if (isset($campagin_detail) && $campagin_detail->image != '' && file_exists(base_path('uploads/company/campaign/' . $campagin_detail->image)))
                                <img class="card-img-top" src="{{ asset('uploads/company/campaign/' . $campagin_detail->image) }}" class="w-100 img-responsive">
                            @else
                                <img src="{{ asset('assets/images/others/No_image_available.png') }}" class="w-100 img-responsive">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4 class="m-b-10">{{ $campagin_detail->title ?? '' }}</h4>

                            <p class="m-b-20">{!! $campagin_detail->description !!}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <input type="hidden" id="status" value="1">

@endsection
