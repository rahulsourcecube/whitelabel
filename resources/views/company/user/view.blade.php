@extends('company.layouts.master')
@section('title', 'User Detials')
@section('main-content')
    <!-- Content Wrapper START -->
    <div class="main-content">
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <a class="breadcrumb-item" href="{{ route('company.user.list') }}">User</a>
                    <span class="breadcrumb-item active">Profile</span>
                </nav>
            </div>
        </div>
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <div class="d-md-flex align-items-center">
                                <div class="text-center text-sm-left ">
                                    <div class="avatar avatar-image" style="width: 150px; height:150px">
                                        <img src="{{ asset('assets/images/avatars/thumb-3.jpg') }}" alt="">
                                    </div>
                                </div>
                                <div class="text-center text-sm-left m-v-15 p-l-30">
                                    <h2 class="m-b-5">Marshall Nichols</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="row">
                                <div class="d-md-block d-none border-left col-1"></div>
                                <div class="col">
                                    <ul class="list-unstyled m-t-10">
                                        <li class="row">
                                            <p class="col-sm-4 col-4 font-weight-semibold text-dark m-b-5">
                                                <i class="m-r-10 text-primary anticon anticon-mail"></i>
                                                <span>Email: </span>
                                            </p>
                                            <p class="col font-weight-semibold"> Marshall123@gmail.com</p>
                                        </li>
                                        <li class="row">
                                            <p class="col-sm-4 col-4 font-weight-semibold text-dark m-b-5">
                                                <i class="m-r-10 text-primary anticon anticon-phone"></i>
                                                <span>Phone: </span>
                                            </p>
                                            <p class="col font-weight-semibold"> +12-123-1234</p>
                                        </li>
                                        <li class="row">
                                            <p class="col-sm-4 col-5 font-weight-semibold text-dark m-b-5">
                                                <i class="m-r-10 text-primary anticon anticon-compass"></i>
                                                <span>Location: </span>
                                            </p>
                                            <p class="col font-weight-semibold"> Los Angeles, CA</p>
                                        </li>
                                    </ul>
                                    <div class="d-flex font-size-22 m-t-15">
                                        <a href="" class="text-gray p-r-20">
                                            <i class="anticon anticon-facebook"></i>
                                        </a>
                                        <a href="" class="text-gray p-r-20">
                                            <i class="anticon anticon-twitter"></i>
                                        </a>
                                        <a href="" class="text-gray p-r-20">
                                            <i class="anticon anticon-behance"></i>
                                        </a>
                                        <a href="" class="text-gray p-r-20">
                                            <i class="anticon anticon-dribbble"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Content Wrapper END -->
@endsection
