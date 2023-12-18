@extends('company.layouts.master')
@section('title', 'Campaign List')
@section('main-content')
    <!-- Content Wrapper START -->
    <div class="main-content">
        @include('company.includes.message')
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
                            <img class="img-fluid" src="{{asset('uploads/company/campaign').'/'.$task->image}}" alt="">
                        </div>
                        <div class="col-md-8">
                            <h4 class="m-b-10">{{$task->title??""}}</h4>
                            <div class="d-flex align-items-center m-t-5 m-b-15">
                                {{-- <div class="avatar avatar-image avatar-sm">
                                    <img src="{{ asset('assets/images/avatars/thumb-2.jpg') }}" alt="">
                                </div> --}}
                                <div class="m-l-10">
                                    <span class="text-gray font-weight-semibold">@if($task->type=='1') {{'Referral'}} @elseif($task->type=='2'){{'Social Share'}} @else {{'Custom'}} @endif</span>
                                    <span class="m-h-5 text-gray">|</span>
                                    <span class="text-gray">{{$task->expiry_date??""}}</span>
                                </div>
                            </div>
                            <p class="m-b-20">{!! $task->description !!}</p>
                            <div class="text-right">
                                {{-- <a class="btn btn-hover font-weight-semibold" href="blog-post.html">
                                    <span>Read More</span>
                                </a> --}}
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        {{-- <h4>Tabs With Pill</h4>
                        <p>Tabs also works with pills.</p> --}}
                        <div class="m-t-25">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="user-tab" data-toggle="pill" href="#Joined_user"
                                        role="tab" aria-controls="user" aria-selected="true">Recently User Joined</a>
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
                    <div class="tab-pane fade show active" id="Joined_user" role="tabpanel" aria-labelledby="user-tab">
                        <h4>Recently User Joined List</h4>
                        <div class="m-t-25">
                            <table id="user_joind" class="table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Join Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="request" role="tabpanel" aria-labelledby="request-tab">
                        <h4>User Request list</h4>
                        <div class="m-t-25">
                            <table id="requests" class="table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Reward</th>
                                        <th>Completed Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
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
                                        <th></th>
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
                                        <th></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                {{-- <tbody>
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
                                </tbody> --}}
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var id = "{{ $taskId }}";
            var url = "{{ route('company.campaign.joined', ':id') }}";
            var urlrequest = "{{ route('company.campaign.request', ':id') }}";
            var urlaccept = "{{ route('company.campaign.accept', ':id') }}";
            var urlreject = "{{ route('company.campaign.accept', ':id') }}";
            url = url.replace(':id', id);
            urlrequest = urlrequest.replace(':id', id);
            urlaccept = urlaccept.replace(':id', id);
            urlreject = urlreject.replace(':id', id);
            
            var table1 = $('#user_joind').DataTable({
                // Processing indicator
                "processing": false,
                // DataTables server-side processing mode
                "serverSide": true,
                responsive: true,
                pageLength: 25,
                // Initial no order.
                'order': [
                    [0, 'desc']
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search Here",
                },
                // Load data from an Ajax source
                "ajax": {
                    "url": url,
                    "type": "GET",
                    "headers": {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    "data": function(d) {
                        // d.search_name = $('#search_name').val();
                    }
                },
                'columnDefs': [{
                    'targets': 0,
                    'width': 'auto',
                    'visible': false,
                    'orderable': false,
                    'render': function(data, type, row) {
                        return '<input type="checkbox" name="chk_row" value="' + row[0] +
                            '" class="chk-row">';
                    },
                
                }],
            });

            var table2 = $('#requests').DataTable({
                // Processing indicator
                "processing": false,
                // DataTables server-side processing mode
                "serverSide": true,
                responsive: true,
                pageLength: 25,
                // Initial no order.
                'order': [
                    [0, 'desc']
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search Here",
                },
                // Load data from an Ajax source
                "ajax": {
                    "url": urlrequest,
                    "type": "GET",
                    "headers": {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    "data": function(d) {
                        // d.search_name = $('#search_name').val();
                    }
                },
                'columnDefs': [{
                    'targets': 0,
                    'width': 'auto',
                    'visible': false,
                    'orderable': false,
                    'render': function(data, type, row) {
                        return '<input type="checkbox" name="chk_row" value="' + row[0] +
                            '" class="chk-row">';
                    },
                
                }],
            });
            var table3 = $('#accept_user').DataTable({
                // Processing indicator
                "processing": false,
                // DataTables server-side processing mode
                "serverSide": true,
                responsive: true,
                pageLength: 25,
                // Initial no order.
                'order': [
                    [0, 'desc']
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search Here",
                },
                // Load data from an Ajax source
                "ajax": {
                    "url": urlaccept,
                    "type": "GET",
                    "headers": {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    "data": function(d) {
                        // d.search_name = $('#search_name').val();
                    }
                },
                'columnDefs': [{
                    'targets': 0,
                    'width': 'auto',
                    'visible': false,
                    'orderable': false,
                    'render': function(data, type, row) {
                        return '<input type="checkbox" name="chk_row" value="' + row[0] +
                            '" class="chk-row">';
                    },
                
                }],
            });
            var table4 = $('#reject_user').DataTable({
                // Processing indicator
                "processing": false,
                // DataTables server-side processing mode
                "serverSide": true,
                responsive: true,
                pageLength: 25,
                // Initial no order.
                'order': [
                    [0, 'desc']
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search Here",
                },
                // Load data from an Ajax source
                "ajax": {
                    "url": urlreject,
                    "type": "GET",
                    "headers": {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    "data": function(d) {
                        // d.search_name = $('#search_name').val();
                    }
                },
                'columnDefs': [{
                    'targets': 0,
                    'width': 'auto',
                    'visible': false,
                    'orderable': false,
                    'render': function(data, type, row) {
                        return '<input type="checkbox" name="chk_row" value="' + row[0] +
                            '" class="chk-row">';
                    },
                
                }],
            });
        });
        </script>
@endsection
