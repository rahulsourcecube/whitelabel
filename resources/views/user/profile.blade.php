@extends('user.layouts.master')
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
                        <div class="row">
                            <div class="col-md-12 col-lg-12">

                                <div class="d-md-flex align-items-center">
                                    <div class="text-center text-sm-left ">
                                        <div class="avatar avatar-image" style="width: 150px; height:150px">
                                            @if (isset($userData) &&
                                                    !empty($userData->profile_image) &&
                                                    file_exists('uploads/user/user-profile/' . $userData->profile_image))
                                                <img
                                                    src="{{ asset('uploads/user/user-profile/' . $userData->profile_image) }}">
                                            @else
                                                <img src="{{ asset('assets/images/profile_image.jpg') }}">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-center text-sm-left m-v-15 p-l-30">
                                        <h2 class="m-b-5">
                                            {{ isset($userData->first_name) ? $userData->first_name : '' }}
                                            {{ isset($userData->last_name) ? $userData->last_name : '' }}
                                        </h2>
                                        <div class="row">
                                            <div class="d-md-block d-none border-left col-1"></div>
                                            <div class="col-md-12">
                                                <ul class="list-unstyled m-t-10">
                                                    <li class="row">
                                                        <p class="font-weight-semibold text-dark m-b-5">
                                                            <i class="m-r-8 text-primary anticon anticon-mail"></i>
                                                            {{-- <span>:</span> --}}
                                                        </p>
                                                        <p class="col font-weight-semibold">
                                                            {{ isset($userData->email) ? $userData->email : '-' }}</p>
                                                    </li>

                                                    <li class="row">
                                                        <p class="font-weight-semibold text-dark m-b-5">
                                                            <i class="m-r-8 text-primary anticon anticon-phone"></i>
                                                            {{-- <span>:</span> --}}
                                                        </p>
                                                        <p class="col font-weight-semibold">
                                                            {{ isset($userData->contact_number) ? $userData->contact_number : '-' }}
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
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>MY Referral Conected</h5>
                        </div>
                        <div class="m-t-30">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Customer</th>
                                            <th>Date</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($referralUser as $data)
                                            <tr>
                                                <td>#{{ isset($i) ? $i : '' }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-image"
                                                                style="height: 30px; min-width: 30px; max-width:30px">
                                                                @if (isset($data->profile_image) &&
                                                                        !empty($userData->profile_image) &&
                                                                        file_exists('uploads/user/user-profile/' . $userData->profile_image))
                                                                    <img
                                                                        src="{{ asset('uploads/user/user-profile/' . $data->profile_image) }}">
                                                                @else
                                                                    <img
                                                                        src="{{ asset('assets/images/profile_image.jpg') }}">
                                                                @endif
                                                            </div>
                                                            <h6 class="m-l-10 m-b-0">
                                                                {{ isset($data->first_name) ? $data->first_name : '' }}
                                                                &nbsp;
                                                                {{ isset($data->last_name) ? $data->last_name : '' }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ isset($data->created_at) ? $data->created_at->format('Y-m-d') : '' }}
                                                </td>
                                                {{-- <td>{{ isset($data->created_at) ? $data->created_at : '' }}</td> --}}
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
