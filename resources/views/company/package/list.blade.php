@extends('company.layouts.master')
@section('title', 'Buy Package')
@section('main-content')
<div class="main-content">
    <div class="page-header">
        <h2 class="header-title">Campaign</h2>
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">              
                <span class="breadcrumb-item active">Campaign</span>
            </nav>
        </div>
    </div>
    <div class="container">
        <div class="text-center m-t-30 m-b-40">
            <h2>Campaign plans</h2>
            <p class="w-45 m-h-auto m-b-30">Climb leg rub face on everything give attitude nap all day for under the bed. Chase mice attack feet but rub face.</p>
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
                                    <span class="text-dark font-weight-semibold">No Of Days-15</span>
                                    <div class="text-success font-size-16">
                                        <i class="anticon anticon-check"></i>
                                    </div>
                                </div>
                            </li>
                            <li class="m-b-20">
                                <div class="d-flex justify-content-between">
                                    <span class="text-dark font-weight-semibold">No of Campaigns -50</span>
                                    <div class="text-success font-size-16">
                                        <i class="anticon anticon-check"></i>
                                    </div>
                                </div>
                            </li>
                           
                        </ul>
                        <div class="text-center">
                            <button class="btn btn-success">$ Buy</button>
                        </div>
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
                                        <span class="font-size-13 font-weight-semibold">/ month</span>
                                    </h2>
                                    <h4 class="m-b-0">Standard Plan</h4>
                                </div>
                            </div>
                        </div>
                        <ul class="list-unstyled m-v-30">
                            <li class="m-b-20">
                                <div class="d-flex justify-content-between">
                                    <span class="text-dark font-weight-semibold">No Of Months-15</span>
                                    <div class="text-success font-size-16">
                                        <i class="anticon anticon-check"></i>
                                    </div>
                                </div>
                            </li>
                            <li class="m-b-20">
                                <div class="d-flex justify-content-between">
                                    <span class="text-dark font-weight-semibold">No of Campaigns -100</span>
                                    <div class="text-success font-size-16">
                                        <i class="anticon anticon-check"></i>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="text-center">
                            <button class="btn btn-success">$ Buy</button>
                        </div>
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
                                        <span class="font-size-13 font-weight-semibold">/ Year</span>
                                    </h2>
                                    <h4 class="m-b-0">Premium Plan</h4>
                                </div>
                            </div>
                        </div>
                        <ul class="list-unstyled m-v-30">
                            <li class="m-b-20">
                                <div class="d-flex justify-content-between">
                                    <span class="text-dark font-weight-semibold">No Of Year-1</span>
                                    <div class="text-success font-size-16">
                                        <i class="anticon anticon-check"></i>
                                    </div>
                                </div>
                            </li>
                            <li class="m-b-20">
                                <div class="d-flex justify-content-between">
                                    <span class="text-dark font-weight-semibold">No of Campaigns -150</span>
                                    <div class="text-success font-size-16">
                                        <i class="anticon anticon-check"></i>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="text-center">
                            <button class="btn btn-success">$ Buy</button>
                        </div>
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
                                        <span class="font-size-13 font-weight-semibold">/ month</span>
                                    </h2>
                                    <h4 class="m-b-0">Standard Plan</h4>
                                </div>
                            </div>
                        </div>
                        <ul class="list-unstyled m-v-30">
                            <li class="m-b-20">
                                <div class="d-flex justify-content-between">
                                    <span class="text-dark font-weight-semibold">No Of Months-15</span>
                                    <div class="text-success font-size-16">
                                        <i class="anticon anticon-check"></i>
                                    </div>
                                </div>
                            </li>
                            <li class="m-b-20">
                                <div class="d-flex justify-content-between">
                                    <span class="text-dark font-weight-semibold">No of Campaigns -100</span>
                                    <div class="text-success font-size-16">
                                        <i class="anticon anticon-check"></i>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="text-center">
                            <button class="btn btn-success">$ Buy</button>
                        </div>
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
                                        <span class="font-size-13 font-weight-semibold">/ Year</span>
                                    </h2>
                                    <h4 class="m-b-0">Premium Plan</h4>
                                </div>
                            </div>
                        </div>
                        <ul class="list-unstyled m-v-30">
                            <li class="m-b-20">
                                <div class="d-flex justify-content-between">
                                    <span class="text-dark font-weight-semibold">No Of Year-1</span>
                                    <div class="text-success font-size-16">
                                        <i class="anticon anticon-check"></i>
                                    </div>
                                </div>
                            </li>
                            <li class="m-b-20">
                                <div class="d-flex justify-content-between">
                                    <span class="text-dark font-weight-semibold">No of Campaigns -150</span>
                                    <div class="text-success font-size-16">
                                        <i class="anticon anticon-check"></i>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="text-center">
                            <button class="btn btn-success">$ Buy</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
    </div>
</div>
  
@endsection
