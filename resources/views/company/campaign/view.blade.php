@extends('company.layouts.master')
@section('title', 'Campaign List')
@section('main-content')



<!-- Content Wrapper START -->
<div class="main-content">
    <div class="page-header">
        <h2 class="header-title">Blog List</h2>
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a href="#" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Home</a>
                <a class="breadcrumb-item" href="#">Pages</a>
                <span class="breadcrumb-item active">Blog List</span>
            </nav>
        </div>
    </div>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <img class="img-fluid" src="{{asset('assets/images/others/img-2.jpg')}}" alt="">
                    </div>
                    <div class="col-md-8">
                        <h4 class="m-b-10">You Should Know About Enlink</h4>
                        <div class="d-flex align-items-center m-t-5 m-b-15">
                            <div class="avatar avatar-image avatar-sm">
                                <img src="{{asset('assets/images/avatars/thumb-2.jpg')}}" alt="">
                            </div>
                            <div class="m-l-10">
                                <span class="text-gray font-weight-semibold">Darryl Day</span>
                                <span class="m-h-5 text-gray">|</span>
                                <span class="text-gray">Jan 2, 2019</span>
                            </div>
                        </div>
                        <p class="m-b-20">Jelly-o sesame snaps halvah croissant oat cake cookie. Cheesecake bear claw
                            topping. Chupa chups apple pie carrot cake chocolate cake caramels</p>
                        <div class="text-right">
                            {{-- <a class="btn btn-hover font-weight-semibold" href="blog-post.html">
                                <span>Read More</span>
                            </a> --}}
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="card">
                <div class="card-body">
                    <h4>Tabs With Pill</h4>
                    <p>Tabs also works with pills.</p>
                    <div class="m-t-25">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="user-tab" data-toggle="pill" href="#user" role="tab"
                                    aria-controls="user" aria-selected="true">Recently User Joined</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="request-tab" data-toggle="pill" href="#request" role="tab"
                                    aria-controls="request" aria-selected="false">User Requset</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="accept-tab" data-toggle="pill" href="#accept" role="tab"
                                    aria-controls="accept" aria-selected="false">Accepted</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="reject-tab" data-toggle="pill" href="#reject" role="tab"
                                    aria-controls="reject" aria-selected="false">Rejected</a>
                            </li>

                        </ul>
                    </div>

                </div>
            </div>
            <div class="card-body tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="user" role="tabpanel" aria-labelledby="user-tab">

                    <h4>Recently User Joined List</h4>

                    <div class="m-t-25">
                        <table id="user_complete" class="table">
                            <thead>
                                <tr>
                                    {{-- <th></th> --}}
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Join Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Doe</td>
                                    <td>john@mailinator.com</td>
                                    <td>1234567890</td>
                                    <td>2023-10-22</td>
                                    <td>
                                        <a class="btn btn-success  btn-sm" href="{{ route('company.user.view') }}"
                                            role="button" title="View"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>John Doe</td>
                                    <td>john@mailinator.com</td>
                                    <td>1234567890</td>
                                    <td>2023-10-22</td>
                                    <td>
                                        <a class="btn btn-success  btn-sm" href="{{ route('company.user.view') }}"
                                            role="button" title="View"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="request" role="tabpanel" aria-labelledby="request-tab">
                    <h4>User Request list</h4>
                    <div class="m-t-25">
                        <table id="user_tables" class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Reward</th>
                                    <th>Completed Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Doe</td>
                                    <td>john@mailinator.com</td>
                                    <td>1234567890</td>
                                    <td>$50</td>
                                    <td>2023/12/02</td>
                                    <td>
                                        <a class="btn btn-success  btn-sm" href="{{route('company.campaign.view')}}"
                                            role="button" title="View">Accept</a>

                                        <a class="btn btn-danger btn-sm" role="button" href="javascript:void(0)"
                                            onclick="sweetAlertAjax()">Reject</i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>John Doe</td>
                                    <td>john@mailinator.com</td>
                                    <td>1234567890</td>
                                    <td>$50</td>
                                    <td>2023/12/02</td>
                                    <td>
                                        <a class="btn btn-success  btn-sm" href="{{route('company.campaign.view')}}"
                                            role="button" title="View">Accept</a>

                                        <a class="btn btn-danger btn-sm" role="button" href="javascript:void(0)"
                                            onclick="sweetAlertAjax()">Reject</i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>John Doe</td>
                                    <td>john@mailinator.com</td>
                                    <td>1234567890</td>
                                    <td>$50</td>
                                    <td>2023/12/02</td>
                                    <td>
                                        <a class="btn btn-success  btn-sm" href="{{route('company.campaign.view')}}"
                                            role="button" title="View">Accepted</a>

                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td>John Doe</td>
                                    <td>john@mailinator.com</td>
                                    <td>1234567890</td>
                                    <td>$50</td>
                                    <td>2023/12/02</td>
                                    <td>
                                        <a class="btn btn-success  btn-sm" href="{{route('company.campaign.view')}}"
                                            role="button" title="View">Accept</a>

                                        <a class="btn btn-danger btn-sm" role="button" href="javascript:void(0)"
                                            onclick="sweetAlertAjax()">Reject</i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>John Doe</td>
                                    <td>john@mailinator.com</td>
                                    <td>1234567890</td>
                                    <td>$50</td>
                                    <td>2023/12/02</td>
                                    <td>
                                       

                                        <a class="btn btn-danger btn-sm" role="button" href="javascript:void(0)"
                                            onclick="sweetAlertAjax()">Rejected</i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade " id="accept" role="tabpanel" aria-labelledby="accept-tab">

                    <h4>Accepted List</h4>

                    <div class="m-t-25">
                        <table id="accept_user" class="table">
                            <thead>
                                <tr>
                                    {{-- <th></th> --}}
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Date</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Doe</td>
                                    <td>john@mailinator.com</td>
                                    <td>1234567890</td>
                                    <td>2023-10-22</td>                                    
                                </tr>

                                <tr>
                                    <td>John Doe</td>
                                    <td>john@mailinator.com</td>
                                    <td>1234567890</td>
                                    <td>2023-10-22</td>                                    
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade " id="reject" role="tabpanel" aria-labelledby="reject-tab">

                    <h4>Rejected List</h4>

                    <div class="m-t-25">
                        <table id="reject_user" class="table">
                            <thead>
                                <tr>
                                    {{-- <th></th> --}}
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Date</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Doe</td>
                                    <td>john@mailinator.com</td>
                                    <td>1234567890</td>
                                    <td>2023-10-22</td>                                    
                                </tr>

                                <tr>
                                    <td>John Doe</td>
                                    <td>john@mailinator.com</td>
                                    <td>1234567890</td>
                                    <td>2023-10-22</td>                                    
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection