@extends('user.layouts.master')
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
                        <div class="row">
                            <div class="col-md-12 col-lg-12">

                                <div class="d-md-flex align-items-center">
                                    <div class="text-center text-sm-left ">
                                        <div class="avatar avatar-image" style="width: 150px; height:150px">
                                            @if (isset($userData) && !empty($userData->profile_image) && file_exists(base_path() . '/uploads/user/user-profile/' . $userData->profile_image))
                                                <img src="{{ asset('uploads/user/user-profile/' . $userData->profile_image) }}">
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
                                                        </p>
                                                        <p class="col font-weight-semibold">
                                                            {{ isset($userData->email) ? $userData->email : '-' }}</p>
                                                    </li>

                                                    <li class="row">
                                                        <p class="font-weight-semibold text-dark m-b-5">
                                                            <i class="m-r-8 text-primary anticon anticon-phone"></i>
                                                        </p>
                                                        <p class="col font-weight-semibold">
                                                            {{ isset($userData->contact_number) ? $userData->contact_number : '-' }}
                                                        </p>
                                                    </li>

                                                    <li class="row">
                                                        <p class="font-weight-semibold text-dark m-b-5">
                                                            <i class="m-r-8 text-primary anticon anticon-home"></i>
                                                        </p>
                                                        <p class="col font-weight-semibold">
                                                            {{ isset($userData->city->name) ? $userData->city->name : '-' }}
                                                            {{ isset($userData->state->name) ? $userData->state->name : '-' }}
                                                            {{ isset($userData->country->name) ? $userData->country->name : '-' }}
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

                <div class="row col-lg-12">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>MY Referral Connected</h5>
                                </div>
                                <div class="m-t-30">
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="user_tables">
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
                                                                                file_exists(base_path().'/uploads/user/user-profile/' . $userData->profile_image))
                                                                            <img
                                                                                src="{{ asset('uploads/user/user-profile/' . $data->profile_image) }}">
                                                                        @else
                                                                            <img
                                                                                src="{{ asset('assets/images/profile_image.jpg') }}">
                                                                        @endif
                                                                    </div>
                                                                    <h6 class="m-l-10 m-b-0">
                                                                        {{ isset($data->first_name) ? $data->first_name.' '.$data->last_name : '' }}
                                                                        {{-- &nbsp; --}}
                                                                        {{-- {{ isset($data->last_name) ? $data->last_name : '' }} --}}
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ isset($data->created_at) ? App\Helpers\Helper::Dateformat($data->created_at) : '' }}
                                                        </td>
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
                    @if(!empty($progressions))
                        <div class="col-lg-4">
                            {{-- <div class="card">
                                <div class="card-body">
                                    <button class="btn btn-info float-right ml-8" data-toggle="modal" data-target=".bd-example-modal-lg">Info</button>
                                    <h4>Task progression</h4>
                                    <div class="m-t-30 text-center">
                                        <div class="avatar avatar-image" style="height: 100px; width: 100px;">
                                            <img src="{{ asset('uploads/company/progression/' . $progression['taskProgression']->image) }}" alt="">
                                        </div>
                                        <span>
                                            <h6 class=""> Title:- {{ !empty($progression['taskProgression']->title)?$progression['taskProgression']->title:""}}</h6>
                                            <p><b> Task:- {{!empty($progression->no_of_task)?$progression->no_of_task:""}}</b></p>
                                        </span>
                                    </div>
                                    <div class="text-center m-t-15">
                                    
                                    </div>
                                
                                </div>
                            </div> --}}
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
      
    
    <script>
        $(document).ready(function() {
            var table = $('#user_tables').DataTable({
                // Processing indicator
                "processing": false,
                // DataTables server-side processing mode
                "serverSide": false,
                responsive: true,
                pageLength: 10,
                // Initial no order.
                'order': [],
                language: {
                    search: "",
                    searchPlaceholder: "Search Here",
                },
            });
        });
    </script>
@endsection
