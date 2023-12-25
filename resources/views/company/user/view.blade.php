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
                    <div class="col-md-12">
                        <div class="row align-items-center">
                            <div class="text-center text-sm-left col-md-2">
                                <div class="avatar avatar-image" style="width: 150px; height:150px">
                                    @if (isset($user) && !empty($user->profile_image) &&
                                    file_exists('uploads/company/user-profile/' . $user->profile_image))
                                    <img src="{{ asset('uploads/company/user-profile/' . $user->profile_image) }}"
                                        alt="">
                                    @else
                                    <img src="{{ asset('assets/images/default-user.jpg') }}" alt="">
                                    @endif
                                </div>
                            </div>
                            <div class="text-center text-sm-left m-v-15 p-l-30">
                                <h2 class="m-b-5">{{ isset($user) ? $user->full_name : "" }}</h2>
                                <div class="row">
                                    <div class="d-md-block d-none border-left col-1"></div>
                                    <div class="col-md-12">
                                        <ul class="list-unstyled m-t-10">
                                            <li class="row">
                                                <p class="col-sm-4 col-4 font-weight-semibold text-dark m-b-5">
                                                    <i class="m-r-10 text-primary anticon anticon-mail"></i>
                                                    <span>Email: </span>
                                                </p>
                                                <p class="col font-weight-semibold">{{ isset($user) ? $user->email : ""
                                                    }}</p>
                                            </li>
                                            <li class="row">
                                                <p class="col-sm-4 col-4 font-weight-semibold text-dark m-b-5">
                                                    <i class="m-r-10 text-primary anticon anticon-phone"></i>
                                                    <span>Phone: </span>
                                                </p>
                                                <p class="col font-weight-semibold">{{ isset($user) ?
                                                    $user->contact_number : "" }}</p>
                                            </li>
                                            {{-- <li class="row">
                                                <p class="col-sm-4 col-5 font-weight-semibold text-dark m-b-5">
                                                    <i class="m-r-10 text-primary anticon anticon-compass"></i>
                                                    <span>Location: </span>
                                                </p>
                                                <p class="col font-weight-semibold">{{ isset($user) ? $user->location :
                                                    "" }}
                                                </p>
                                            </li> --}}
                                        </ul>
                                        <div class="d-flex font-size-22 m-t-15">
                                            <a href="{{ isset($user->facebook_link) ? $user->facebook_link : " #" }}"
                                                target="blank" class="text-gray p-r-20">
                                                <i class="anticon anticon-facebook"></i>
                                            </a>
                                            <a href="{{ isset($user->instagram_link) ? $user->instagram_link : " #" }}"
                                                target="blank" class="text-gray p-r-20">
                                                <i class="anticon anticon-instagram"></i>
                                            </a>
                                            <a href="{{ isset($user->twitter_link) ? $user->twitter_link : " #" }}"
                                                target="blank" class="text-gray p-r-20">
                                                <i class="anticon anticon-twitter"></i>
                                            </a>
                                            <a href="{{ isset($user->youtube_link) ? $user->youtube_link : " #" }}"
                                                target="blank" class="text-gray p-r-20">
                                                <i class="anticon anticon-youtube"></i>
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
        <div class="card">
            <div class="card-body">
                <h2>Bank Detail:</h2>
                <div class="table-responsive">
                    <table class="product-info-table m-t-20">
                        <tbody>
                            <tr>
                                <td>Bank Name:</td>
                                <td> {{ isset($user->bank_name) ? $user->bank_name : "-" }}</td>
                            </tr>
                            <tr>
                                <td>Bank Holder : </td>
                                <td>{{ isset($user->ac_holder) ? $user->ac_holder : "-" }}</td>
                            </tr>
                            <tr>
                                <td>IFSC Code :</td>
                                <td>{{ isset($user->ifsc_code) ? $user->ifsc_code : "-" }}</td>
                            </tr>
                            <tr>
                                <td>Account No :</td>
                                <td> {{ isset($user->ac_no) ? $user->ac_no : "-" }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Content Wrapper END -->
@endsection