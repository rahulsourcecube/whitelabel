@extends('company.layouts.master')
@section('title', 'User Detials')
@section('main-content')
    <!-- Content Wrapper START -->
    <div class="main-content">
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <a class="breadcrumb-item" href="{{ route('company.user.list') }}">User</a>
                    <span class="breadcrumb-item active">Profile</span>
                </nav>
            </div>
        </div>
        <div class="container1">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="row align-items-center">
                                <div class="text-center text-sm-left col-md-2">
                                    <div class="avatar avatar-image" style="width: 150px; height:150px">
                                        @if (isset($user) && !empty($user->profile_image) && file_exists(base_path() . '/uploads/company/user-profile/' . $user->profile_image))
                                            <img src="{{ asset('uploads/company/user-profile/' . $user->profile_image) }}" alt="">
                                        @else
                                            <img src="{{ asset('assets/images/default-user.jpg') }}" alt="">
                                        @endif
                                    </div>
                                </div>
                                <div class="text-center text-sm-left m-v-15 p-l-30">
                                    <h2 class="m-b-5">{{ isset($user) ? $user->full_name : '' }}</h2>
                                    <div class="row">
                                        <div class="d-md-block d-none border-left col-1"></div>
                                        <div class="col-md-12">
                                            <ul class="list-unstyled m-t-10">
                                                <li class="row">
                                                    <p class=" font-weight-semibold text-dark m-b-5">
                                                        <i class="m-r-10 text-primary anticon anticon-mail"></i>
                                                        <span>Email: </span>
                                                    </p>
                                                    <p class="col font-weight-semibold">
                                                        {{ isset($user) ? $user->email : '' }}</p>
                                                </li>
                                                <li class="row">
                                                    <p class=" font-weight-semibold text-dark m-b-5">
                                                        <i class="m-r-10 text-primary anticon anticon-phone"></i>
                                                        <span>Phone: </span>
                                                    </p>
                                                    <p class="col font-weight-semibold">
                                                        {{ isset($user) ? $user->contact_number : '' }}
                                                    </p>
                                                </li>
                                                <li class="row">
                                                    <p class="font-weight-semibold text-dark m-b-5">
                                                        <i class="m-r-8 text-primary anticon anticon-home"></i>
                                                    </p>
                                                    <p class="col font-weight-semibold">
                                                        {{ isset($user->city->name) ? $user->city->name : '-' }}
                                                        {{ isset($user->state->name) ? $user->state->name : '-' }}
                                                        {{ isset($user->country->name) ? $user->country->name : '-' }}
                                                    </p>
                                                </li>

                                            </ul>
                                            <div class="d-flex font-size-22 m-t-15">
                                                @if (isset($user->facebook_link) && !empty($user->facebook_link))
                                                    <a href="{{ isset($user->facebook_link) ? $user->facebook_link : ' #' }}" target="blank"
                                                        class="text-gray p-r-20">
                                                        <i class="anticon anticon-facebook"></i>
                                                    </a>
                                                @endif
                                                @if (isset($user->instagram_link) && !empty($user->instagram_link))
                                                    <a href="{{ isset($user->instagram_link) ? $user->instagram_link : ' #' }}" target="blank"
                                                        class="text-gray p-r-20">
                                                        <i class="anticon anticon-instagram"></i>
                                                    </a>
                                                @endif
                                                @if (isset($user->twitter_link) && !empty($user->twitter_link))
                                                    <a href="{{ isset($user->twitter_link) ? $user->twitter_link : ' #' }}" target="blank"
                                                        class="text-gray p-r-20">
                                                        <i class="anticon anticon-twitter"></i>
                                                    </a>
                                                @endif
                                                @if (isset($user->youtube_link) && !empty($user->youtube_link))
                                                    <a href="{{ isset($user->youtube_link) ? $user->youtube_link : ' #' }}" target="blank"
                                                        class="text-gray p-r-20">
                                                        <i class="anticon anticon-youtube"></i>
                                                    </a>
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
            
            <div class="row col-md-12">
                <div class=" col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h2>Payout Detail:</h2>
                            <div class="table-responsive">
                                <table class="product-info-table m-t-20">
                                    <tbody>
                                        <tr>
                                            <td><b>Paypal Id : </b> {{ $user->paypal_id ?? $user->paypal_id }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Stripe Id : </b> {{ $user->stripe_id ?? $user->stripe_id }}</td>
                                        </tr>
                                        <tr>
                                            <td>Bank Name:</td>
                                            <td> {{ isset($user->bank_name) ? $user->bank_name : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Bank Holder : </td>
                                            <td>{{ isset($user->ac_holder) ? $user->ac_holder : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>IFSC Code :</td>
                                            <td>{{ isset($user->ifsc_code) ? $user->ifsc_code : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Account No :</td>
                                            <td> {{ isset($user->ac_no) ? $user->ac_no : '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                </div>
            </div>
            @if(!empty($progressions))
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="m-b-0">Task progression</h5>                           
                        </div>
                        <div class="m-t-5">
                            <div class="overflow-y-auto scrollable relative" style="max-height: 300px; margin: 0 auto;">
                                <ul class="timeline p-t-5 p-l-5">
                                    @foreach ($progressions as $key => $progression )
                                   {{-- @dd($progression->taskProgressionHistory[$key]->image); --}}
                                        <li class="timeline-item">
                                                <div class="m-b-10 p-b-10 border-bottom">
                                                    <div class="media align-items-center m-b-15">
                                                        <div class="avatar avatar-image" style="width: 150px; height: 100px;">
                                                            <img src="{{ asset('uploads/company/progression/'.$progression->taskProgressionHistory[$key]->image) }}" alt="">
                                                        </div>
                                                        <div class="media-body m-l-20">
                                                            <b>
                                                            Tiitle:-  <a href="#" class="text-dark">{{ !empty($progression->taskProgressionHistory[$key]->title)?$progression->taskProgressionHistory[$key]->title:""}}</a>
                                                            </b>
                                                            <b>Task:-   <a href="#" class="text-dark">{{ !empty($progression->no_of_task)?$progression->no_of_task:""}}</a></b>
                                                        </div>
                                                    </div>
                                                </div>                                                    
                                            </li>
                                            
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        </div>
    </div>
    <!-- Content Wrapper END -->
@endsection
