@extends('admin.layouts.master')
@section('title', 'Company List')​
@section('main-content')
    <!-- Content Wrapper START -->
    <div class="main-content">
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <a class="breadcrumb-item" href="{{ route('admin.company.list') }}">Company</a>
                    <span class="breadcrumb-item active">Profile</span>
                </nav>
            </div>
        </div>
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="d-md-flex align-items-center">
                                <div class="text-center text-sm-left ">
                                    <div class="avatar avatar-image" style="width: 150px; height:150px">
                                        <img src="{{ asset('uploads/user') }}/{{ $user_company->user->profile_image ?: 'avtar.png' }}"
                                            alt="">
                                    </div>
                                </div>
                                <div class="text-center text-sm-left m-v-15 p-l-30">
                                    <h2 class="m-b-5">{{ $user_company->user->first_name }}
                                        {{ $user_company->user->last_name }}</h2>
​
                                    <div class="row">
                                        <div class="d-md-block d-none border-left col-1"></div>
                                        <div class="col-md-12">
                                            <ul class="list-unstyled m-t-10">
                                                <li class="row">
                                                    <p class="col-sm-4 col-4 font-weight-semibold text-dark m-b-5">
                                                        <i class="m-r-10 text-primary anticon anticon-mail"></i>
                                                        <span>Email: </span>
                                                    </p>
                                                    <p class="col font-weight-semibold">{{ $user_company->user->email }}</p>
                                                </li>
                                                <li class="row">
                                                    <p class="col-sm-4 col-4 font-weight-semibold text-dark m-b-5">
                                                        <i class="m-r-10 text-primary anticon anticon-phone"></i>
                                                        <span>Phone: </span>
                                                    </p>
                                                    <p class="col font-weight-semibold">
                                                        {{ $user_company->user->contact_number }}</p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-md-flex align-items-center">
                                <div class="text-center text-sm-left m-v-15 p-l-30">
​
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="text-center text-sm-left ">
                                                <div class="avatar avatar-image" style="width: 150px; height:150px">
                                                    <img src="{{ asset('uploads/company') }}/{{ $user_company->company_logo ?: 'avtar.png' }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-10 " style="padding-left: 95px;">
                                            <div class="text-center text-sm-left ">
                                                <h2 class="m-b-5">{{ $user_company['company_name'] }}</h2>
                                                <a href="//{{ $user_company['subdomain'] }}"
                                                    target="_blank">{{ $user_company['subdomain'] }}</a>
                                            </div>
                                        </div>
                                    </div>
​
                                    <hr>
​
                                    <div class="row">
                                        <div class="d-md-block d-none border-left col-1"></div>
                                        <div class="col-md-12">
                                            <h5>Company Contact</h5>
                                            <ul class="list-unstyled m-t-10">
                                                <li class="row">
                                                    <p class="col-sm-4 col-4 font-weight-semibold text-dark m-b-5">
                                                        <i class="m-r-10 text-primary anticon anticon-mail"></i>
                                                        <span>Email: </span>
                                                    </p>
                                                    <p class="col font-weight-semibold">
                                                        <a
                                                            href="mailTo:{{ $user_company['contact_email'] }}">{{ $user_company->contact_email }}</a>
                                                    </p>
                                                </li>
                                                <li class="row">
                                                    <p class="col-sm-4 col-4 font-weight-semibold text-dark m-b-5">
                                                        <i class="m-r-10 text-primary anticon anticon-phone"></i>
                                                        <span>Phone: </span>
                                                    </p>
                                                    <p class="col font-weight-semibold">
                                                        <a
                                                            href="tel:{{ $user_company['contact_number'] }}">{{ $user_company->contact_number }}</a>
                                                    </p>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-12">
                                            <hr>
                                            <h5>Description</h5>
                                            {{ $user_company['company_description'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
​
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content Wrapper END -->
@endsection
