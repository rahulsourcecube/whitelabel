@extends('company.layouts.master')
@section('title', 'Buy Package')
@section('main-content')
    <div class="main-content">
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">Package </span>
                </nav>
            </div>
        </div>
        <div class="container">
            <div class="text-center m-t-30 m-b-40">
                <h2>Purchase Package</h2>
                <p class="w-45 m-h-auto m-b-30">Climb leg rub face on everything give attitude nap all day for under the
                    bed. Chase mice attack feet but rub face.</p>
                <div class="btn-group">
                    <button type="button" id="monthly-btn" class="btn btn-default active">
                        <span>Day</span>
                    </button>
                    <button type="button" id="annual-btn" class="btn btn-default">
                        <span>Monthly</span>
                    </button>
                    <button type="button" id="annual-btn" class="btn btn-default">
                        <span>Yearly</span>
                    </button>
                </div>
            </div>
            <div class="row align-items-center" id="monthly-view">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between p-b-20 border-bottom">
                                <div class="media align-items-center">
                                    <div class="avatar avatar-blue avatar-icon" style="height: 55px; width: 55px;">
                                        <i class="anticon anticon-coffee font-size-25" style="line-height: 55px"></i>
                                    </div>
                                    <div class="m-l-15">
                                        <h2 class="font-weight-bold font-size-30 m-b-0">
                                            Free
                                        </h2>
                                        <h4 class="m-b-0">Basic Plan</h4>
                                    </div>
                                </div>
                            </div>
                            <ul class="list-unstyled m-v-30">
                                <li class="m-b-20">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-dark font-weight-semibold">15 Days</span>
                                        <div class="text-success font-size-16">
                                            <i class="anticon anticon-check"></i>
                                        </div>
                                    </div>
                                </li>
                                <li class="m-b-20">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-dark font-weight-semibold">50 Campaigns</span>
                                        <div class="text-success font-size-16">
                                            <i class="anticon anticon-check"></i>
                                        </div>
                                    </div>
                                </li>

                            </ul>
                            @can('package-create')
                                <div class="text-center">
                                    <button class="btn btn-success " onclick="showSuccessAlert()">Buy Package</button>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between p-b-20 border-bottom">
                                <div class="media align-items-center">
                                    <div class="avatar avatar-cyan avatar-icon" style="height: 55px; width: 55px;">
                                        <i class="anticon anticon-shop font-size-25" style="line-height: 55px"></i>
                                    </div>
                                    <div class="m-l-15">
                                        <h2 class="font-weight-bold font-size-30 m-b-0">
                                            $400
                                            <span class="font-size-13 font-weight-semibold"></span>
                                        </h2>
                                        <h4 class="m-b-0">Standard Plan</h4>
                                    </div>
                                </div>
                            </div>
                            <ul class="list-unstyled m-v-30">
                                <li class="m-b-20">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-dark font-weight-semibold">175 Days</span>
                                        <div class="text-success font-size-16">
                                            <i class="anticon anticon-check"></i>
                                        </div>
                                    </div>
                                </li>
                                <li class="m-b-20">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-dark font-weight-semibold">250 Campaigns</span>
                                        <div class="text-success font-size-16">
                                            <i class="anticon anticon-check"></i>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            @can('package-create')
                                <div class="text-center">
                                    <button class="btn btn-success " onclick="showSuccessAlert()">Buy Package</button>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between p-b-20 border-bottom">
                                <div class="media align-items-center">
                                    <div class="avatar avatar-gold avatar-icon" style="height: 55px; width: 55px;">
                                        <i class="anticon anticon-experiment font-size-25" style="line-height: 55px"></i>
                                    </div>
                                    <div class="m-l-15">
                                        <h2 class="font-weight-bold font-size-30 m-b-0">
                                            $1000
                                            <span class="font-size-13 font-weight-semibold"></span>
                                        </h2>
                                        <h4 class="m-b-0">Premium Plan</h4>
                                    </div>
                                </div>
                            </div>
                            <ul class="list-unstyled m-v-30">
                                <li class="m-b-20">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-dark font-weight-semibold">365 Days</span>
                                        <div class="text-success font-size-16">
                                            <i class="anticon anticon-check"></i>
                                        </div>
                                    </div>
                                </li>
                                <li class="m-b-20">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-dark font-weight-semibold">500 Campaigns</span>
                                        <div class="text-success font-size-16">
                                            <i class="anticon anticon-check"></i>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            @can('package-create')
                                <div class="text-center">
                                    <button class="btn btn-success " onclick="showSuccessAlert()">Buy Package</button>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function showSuccessAlert() {
            // Trigger a success sweet alert
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Package is activated successful.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        }
    </script>>
@endsection
