@extends('admin.layouts.master')
@section('title', 'Package view')
@section('main-content')
    <div class="main-content">
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>

                    <a href="{{ route('admin.package.list') }}" class="breadcrumb-item">Package</a>
                    <span class="breadcrumb-item active">Detail</span>
                </nav>
            </div>
        </div>
        <div class="container1">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        @if (file_exists(base_path() . '/uploads/package/' . $package->image))
                            <div class="col-md-4">
                                <img class="img-fluid" src="{{ asset('uploads/package/' . $package->image) }}"
                                    alt="" style="width: 100%; max-height: 100%;">
                            </div>
                        @endif
                        <div class="col-md-8">
                            <h4 class="m-b-10">{{ $package->title }}</h4>
                            <div class="d-flex align-items-center m-t-5 m-b-15">
                                <div class="">

                                </div>
                                <div class="m-l-10">
                                    <h1>Price :- {{ \App\Helpers\Helper::getcurrency() . $package->price }} </h1>
                                    <span class="text-gray font-weight-semibold"></span>
                                    <span class="text-gray">
                                        {{ $package->duration }}
                                        @if ($package->type == '1')
                                            Day Free
                                        @elseif ($package->type == '2')
                                            Month
                                        @elseif ($package->type == '3')
                                            Year
                                        @endif
                                    </span>
                                    <br>
                                    <span class="text-gray font-weight-bold">No Of Campaign :-</span>
                                    <span class="text-gray font-weight-bold">{{ $package->no_of_campaign }}</span>
                                    <br>
                                    <span class="text-gray  font-weight-bold">No Of User :-</span>
                                    <span class="text-gray font-weight-bold">{{ $package->no_of_user }}</span>
                                    <br>
                                    <span class="text-gray font-weight-bold">No Of Employee :-</span>
                                    <span class="text-gray font-weight-bold">{{ $package->no_of_employee }}</span>
                                    @if (!empty($package) && $package->survey_status == '1')
                                        <br>
                                        <br>
                                        <span class="text-gray font-weight-bold">
                                            <i
                                                class="mr-2 fa fa-{{ $package->survey_status == '1' ? 'check text-success' : 'close text-danger' }}"></i>
                                            Survey </span>
                                        <span class="text-gray font-weight-bold">
                                        </span>
                                        <br>
                                        <span class="text-gray font-weight-bold ">No Of Survey :-</span>
                                        <span class="text-gray font-weight-bold">{{ $package->no_of_survey }}</span>
                                    @endif
                                    @if (!empty($package) && $package->community_status == '1')
                                        <br>
                                        <br>
                                        <span class="text-gray  font-weight-bold">
                                            <i
                                                class="mr-2 fa fa-{{ $package->survey_status == '1' ? 'check text-success' : 'close text-danger' }}"></i>
                                            Community</span>
                                    @endif
                                    @if (!empty($package) && $package->mail_temp_status == '1')
                                        <br>
                                        <span class="text-gray font-weight-bold">
                                            <i
                                                class="mr-2 fa fa-{{ $package->mail_temp_status == '1' ? 'check text-success' : 'close text-danger' }}"></i>

                                            Mail Template</span>
                                    @endif
                                    @if (!empty($package) && $package->sms_temp_status == '1')
                                        <br>
                                        <span class="text-gray font-weight-bold">
                                            <i
                                                class="mr-2 fa fa-{{ $package->sms_temp_status == '1' ? 'check text-success' : 'close text-danger' }}"></i>

                                            SMS Template :-</span>
                                    @endif

                                </div>
                            </div>
                            <p class="m-b-20">
                                {{ $description = html_entity_decode(strip_tags(preg_replace('/\s+/', ' ', $package->description))) }}
                            </p>
                            <div class="text-right">
                                <a class="btn btn-hover font-weight-semibold" href="blog-post.html">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
