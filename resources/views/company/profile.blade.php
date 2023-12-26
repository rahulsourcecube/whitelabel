@extends('company.layouts.master')
@section('title', 'Edit Profile')
@section('main-content')
<div class="main-content">
    <div class="page-header">
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item"><i
                        class="anticon anticon-home m-r-5"></i>Dashboard</a>
                <span class="breadcrumb-item active">Profile</span>
            </nav>
        </div>
    </div>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="align-items-center row">
                            <div class="text-center text-sm-left col-md-2">
                                <div class="avatar avatar-image" style="width: 150px; height:150px">
                                    @if(isset($profiledetail) && $profiledetail->profile_image == '')
                                    <img src="{{ asset('assets/images/default-company.jpg') }}" alt="">
                                    @else
                                    <img src="{{ asset('uploads/user-profile/'.$profiledetail->profile_image) }}"
                                        alt="">
                                    @endif
                                </div>
                            </div>
                            <div class="text-center text-sm-left m-v-15 p-l-30 col-md-4">
                                <h2 class="m-b-5">{{isset($profiledetail->first_name)?$profiledetail->first_name:'-'}}
                                    {{isset($profiledetail->last_name)?$profiledetail->last_name:'-'}}</h2>
                                <div class="row">
                                    <div class="d-md-block d-none border-left col-1"></div>
                                    <div class="col-md-12">
                                        <ul class="list-unstyled m-t-10">
                                            <li class="row">
                                                <p class="font-weight-semibold text-dark m-b-5">
                                                    <i class="m-r-8 text-primary anticon anticon-mail"></i>
                                                    {{-- <span>Email: </span> --}}
                                                </p>
                                                <p class="col font-weight-semibold">
                                                    {{isset($profiledetail->email)?$profiledetail->email:'-'}}</p>
                                            </li>
                                            <li class="row">
                                                <p class="font-weight-semibold text-dark m-b-5">
                                                    <i class="m-r-8 text-primary anticon anticon-phone"></i>
                                                    {{-- <span>Phone: </span> --}}
                                                </p>
                                                <p class="col font-weight-semibold">
                                                    {{isset($profiledetail->contact_number)?$profiledetail->contact_number:'-'}}
                                                </p>
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
        <b>
            <h2>Company Detail</h2>
        </b>
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="row align-items-center">
                            <div class="text-center text-sm-left col-md-2">
                                <div class="">
                                    @if(empty($companydetail->logo))
                                    <img src="{{ asset('assets/images/logo/logo.png')}}" alt="">
                                    @else
                                    <img src="{{ asset('uploads/setting/'.$companydetail->logo)}}" alt="" class="w-100">
                                    @endif
                                </div>
                            </div>
                            <div class="text-center text-sm-left m-v-15 p-l-30 col-md-4">
                                <h2 class="m-b-5">
                                    {{!empty($companydetail) &&
                                    !empty($companydetail->title)?$companydetail->title:$companyname->company_name}}
                                </h2>
                                <div class="row">
                                    <div class="d-md-block d-none border-left col-1"></div>
                                    <div class="col-md-12">
                                        <ul class="list-unstyled m-t-10">
                                            <li class="row">
                                                <p class="font-weight-semibold text-dark m-b-5">
                                                    <i class="m-r-8 text-primary anticon anticon-mail"></i>
                                                    {{-- <span>Email: </span> --}}
                                                </p>
                                                <p class="col font-weight-semibold">
                                                    {{!empty($companydetail) &&
                                                    isset($companydetail->email)?$companydetail->email:'-'}}</p>
                                            </li>
                                            <li class="row">
                                                <p class="font-weight-semibold text-dark m-b-5">
                                                    <i class="m-r-8 text-primary anticon anticon-phone"></i>
                                                    {{-- <span>Phone: </span> --}}
                                                </p>
                                                <p class="col font-weight-semibold">
                                                    {{!empty($companydetail) &&
                                                    isset($companydetail->contact_number)?$companydetail->contact_number:'-'}}
                                                </p>
                                            </li>
                                        </ul>
                                        <div class="col-md-12">
                                            <hr>
                                            <h5>Description</h5>
                                            @if(!empty($companydetail) && isset($companydetail->description)) {!!
                                            $companydetail->description !!}
                                            @else -
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection