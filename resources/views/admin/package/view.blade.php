@extends('admin.layouts.master')
@section('title', 'Package view')
@section('main-content')
    <div class="main-content">
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>

                    <a href="{{ route('admin.package.list') }}" class="breadcrumb-item">Package</a>
                    <span class="breadcrumb-item active">Detail</span>
                </nav>
            </div>
        </div>
        <div class="container">

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img class="img-fluid" src="{{ asset("uploads/package/".$package->image) }}" alt="" style="width: 100%; max-height: 100%;">
                        </div>
                        <div class="col-md-8">
                            <h4 class="m-b-10">{{$package->title}}</h4>
                            <div class="d-flex align-items-center m-t-5 m-b-15">
                                <div class="">
                                    <h1>Price  :-  {{$package->price}} </h1>
                                </div>
                                <div class="m-l-10">
                                    <span class="text-gray font-weight-semibold"></span>
                                    <span class="m-h-5 text-gray">|</span>
                                    <span class="text-gray">
                                        {{$package->duration}}
                                        @if ($package->type == '1') Day Free
                                        @elseif ($package->type == '2') Month
                                        @elseif ($package->type == '3') Year
                                        @endif
                                    </span>
                                    <span class="text-gray"> | </span>
                                    <span class="text-gray">No Of Campaign :-</span>
                                    <span class="text-gray">{{$package->no_of_campaign}}</span>
                                </div>
                            </div>
                            <p class="m-b-20"> {{ $description = html_entity_decode(strip_tags(preg_replace('/\s+/', ' ', $package->description)))}}</p>
                            <div class="text-right">
                                <a class="btn btn-hover font-weight-semibold" href="blog-post.html">
                                    {{-- <span>Read More</span> --}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
