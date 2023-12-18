@extends('company.layouts.master')
@section('title', 'Edit Profile')
@section('main-content')
<div class="main-content">
    <div class="page-header">
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Dashboard</a>
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
                                    <img src="{{ asset('assets/images/avatars/thumb-3.jpg') }}" alt="">
                                </div>
                            </div>
                            <div class="text-center text-sm-left m-v-15 p-l-30">
                                <h2 class="m-b-5">{{$profiledetail->first_name}} {{$profiledetail->last_name}}</h2>
                                <div class="row">
                                    <div class="d-md-block d-none border-left col-1"></div>
                                    <div class="col-md-12">
                                        <ul class="list-unstyled m-t-10">
                                            <li class="row">
                                                <p class="col-sm-4 col-4 font-weight-semibold text-dark m-b-5">
                                                    <i class="m-r-10 text-primary anticon anticon-mail"></i>
                                                    <span>Email: </span>
                                                </p>
                                                <p class="col font-weight-semibold">{{$profiledetail->email}}</p>
                                            </li>
                                            <li class="row">
                                                <p class="col-sm-4 col-4 font-weight-semibold text-dark m-b-5">
                                                    <i class="m-r-10 text-primary anticon anticon-phone"></i>
                                                    <span>Phone: </span>
                                                </p>
                                                <p class="col font-weight-semibold">{{$profiledetail->contact_number}}</p>
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
        <b><h2>Company Detail</h2></b>
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="d-md-flex align-items-center">
                            <div class="text-center text-sm-left ">
                                <div class="avatar avatar-image" style="width: 150px; height:150px">
                                    @if(isset($companydetail))
                                    <img src="{{ asset('uploads/setting/'.$companydetail->logo)}}" alt="">
                                    @endif
                                </div>
                            </div>
                            <div class="text-center text-sm-left m-v-15 p-l-30">
                                <h2 class="m-b-5">{{$companydetail->title}}</h2>
                                <div class="row">
                                    <div class="d-md-block d-none border-left col-1"></div>
                                    <div class="col-md-12">
                                        <ul class="list-unstyled m-t-10">
                                            <li class="row">
                                                <p class="col-sm-4 col-4 font-weight-semibold text-dark m-b-5">
                                                    <i class="m-r-10 text-primary anticon anticon-mail"></i>
                                                    <span>Email: </span>
                                                </p>
                                                <p class="col font-weight-semibold">{{$companydetail->email}}</p>
                                            </li>
                                            <li class="row">
                                                <p class="col-sm-4 col-4 font-weight-semibold text-dark m-b-5">
                                                    <i class="m-r-10 text-primary anticon anticon-phone"></i>
                                                    <span>Phone: </span>
                                                </p>
                                                <p class="col font-weight-semibold">{{$companydetail->contact_number}}</p>
                                            </li>
                                        </ul>
                                            <div class="col-md-12">
                                                <hr>
                                                <h5>Description</h5>
                                                @if(isset($companydetail->description)) {!! $companydetail->description !!} @endif
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-md-flex align-items-center">
                            <div class="text-center text-sm-left m-v-15 p-l-30">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="text-center text-sm-left ">
                                            <div class="avatar avatar-image" style="width: 150px; height:150px">
                                                @if(isset($companydetail))
                                                <img src="{{ asset('uploads/setting/'.$companydetail->logo)}}" alt="">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10 " style="padding-left: 95px;">
                                        <div class="text-center text-sm-left ">
                                            <h2 class="m-b-5">Yahoo</h2>
                                            <a href="" target="_blank">{{isset($companydetail) ?  $companydetail->email : ""}}</a>
                                        </div>
                                    </div>
                                </div>
                                <hr>
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
                                                    <a href="mailTo:{{isset($companydetail->email)? $companydetail->email:'' }}">{{isset($companydetail->email)? $companydetail->email:'' }}</a>
                                                </p>
                                            </li>
                                            <li class="row">
                                                <p class="col-sm-4 col-4 font-weight-semibold text-dark m-b-5">
                                                    <i class="m-r-10 text-primary anticon anticon-phone"></i>
                                                    <span>Phone: </span>
                                                </p>
                                                <p class="col font-weight-semibold">
                                                    <a href="tel:+123456789">{{isset($companydetail->contact_number)?$companydetail->contact_number:''}}</a>
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-12">
                                        <hr>
                                        <h5>Description</h5>
                                        @if(isset($companydetail->description)) {!! $companydetail->description !!} @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</div>
@endsection