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
                                        <img src="{{ asset('assets/images/avatars/thumb-3.jpg') }}" alt="">
                                    </div>
                                </div>
                                <div class="text-center text-sm-left m-v-15 p-l-30">
                                    <h2 class="m-b-5">Marshall Nichols</h2>
                                    <div class="row">
                                        <div class="d-md-block d-none border-left col-1"></div>
                                        <div class="col-md-12">
                                            <ul class="list-unstyled m-t-10">
                                                <li class="row">
                                                    <p class="col-sm-4 col-4 font-weight-semibold text-dark m-b-5">
                                                        <i class="m-r-10 text-primary anticon anticon-mail"></i>
                                                        <span>Email: </span>
                                                    </p>
                                                    <p class="col font-weight-semibold">Marshall@gmail.com</p>
                                                </li>
                                                <li class="row">
                                                    <p class="col-sm-4 col-4 font-weight-semibold text-dark m-b-5">
                                                        <i class="m-r-10 text-primary anticon anticon-phone"></i>
                                                        <span>Phone: </span>
                                                    </p>
                                                    <p class="col font-weight-semibold">+1234567890</p>
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

                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#5331</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-image"
                                                        style="height: 30px; min-width: 30px; max-width:30px">
                                                        <img src="{{asset('assets/images/avatars/thumb-1.jpg')}}"
                                                            alt="">
                                                    </div>
                                                    <h6 class="m-l-10 m-b-0">Erin Gonzales</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>8 May 2019</td>

                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge badge-success badge-dot m-r-10"></span>
                                                <span>Approved</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#5375</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-image"
                                                        style="height: 30px; min-width: 30px; max-width:30px">
                                                        <img src="{{asset('assets/images/avatars/thumb-2.jpg')}}"
                                                            alt="">
                                                    </div>
                                                    <h6 class="m-l-10 m-b-0">Darryl Day</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>6 May 2019</td>

                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge badge-success badge-dot m-r-10"></span>
                                                <span>Approved</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#5762</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-image"
                                                        style="height: 30px; min-width: 30px; max-width:30px">
                                                        <img src="{{asset('assets/images/avatars/thumb-3.jpg')}}"
                                                            alt="">
                                                    </div>
                                                    <h6 class="m-l-10 m-b-0">Marshall Nichols</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>1 May 2019</td>

                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge badge-success badge-dot m-r-10"></span>
                                                <span>Approved</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#5865</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-image"
                                                        style="height: 30px; min-width: 30px; max-width:30px">
                                                        <img src="{{asset('assets/images/avatars/thumb-4.jpg')}}"
                                                            alt="">
                                                    </div>
                                                    <h6 class="m-l-10 m-b-0">Virgil Gonzales</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>28 April 2019</td>

                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge badge-primary badge-dot m-r-10"></span>
                                                <span>Pending</span>
                                            </div>
                                        </td>
                                    </tr>

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
