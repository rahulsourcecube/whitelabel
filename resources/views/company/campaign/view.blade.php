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
                        <li class="nav-item my-table-tab" data-status="1">
                            <a class="nav-link active" id="user-tab" data-toggle="pill" href="#Joined_user" role="tab" aria-controls="user" aria-selected="true">Recently Joined User </a>
                        </li>
                        <li class="nav-item my-table-tab" data-status="2">
                            <a class="nav-link" id="request-tab" data-toggle="pill" href="#request" role="tab" aria-controls="request" aria-selected="false">Approval Requset</a>
                        </li>
                        <li class="nav-item my-table-tab" data-status="3">
                            <a class="nav-link" id="accept-tab" data-toggle="pill" href="#accept" role="tab" aria-controls="accept" aria-selected="false">Accepted</a>
                        </li>
                        <li class="nav-item my-table-tab" data-status="4">
                            <a class="nav-link" id="reject-tab" data-toggle="pill" href="#reject" role="tab" aria-controls="reject" aria-selected="false">Rejected</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card-body tab-content" id="pills-tabContent">

            <h4>Recently User Joined List</h4>
            <div class="m-t-25">
                <table id="user_joind" class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Reward</th>
                            <th>Join Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
</div>
<input type="hidden" id="status" value="1">
<!-- return '<button class="btn btn-success  btn-sm" data-action="accept" onclick="Accept(\'' + actionUrl + '\',\'3\',\'' + id + '\')">Accept</button>' + -->
<div class="modal fade bd-example-modal-lg" id="view-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h4">View</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="anticon anticon-close"></i>
                </button>
            </div>
            <div class="main-content">
                    <div class="page-header">
                        <h2 class="header-title"></h2>
                        <div class="header-sub-title">
                           
                        </div>
                    </div>
                    <div class="container">
                        <div class="card addmodle ">
                            <!-- <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-12">
                                        <div class="d-md-flex align-items-center">
                                            <div class="text-center text-sm-left ">
                                                <div class="avatar avatar-image" style="width: 150px; height:150px">
                                                    <img id="image" src="assets/images/avatars/thumb-3.jpg" alt="">
                                                </div>
                                            </div>
                                            <div class="text-center text-sm-left m-v-15 p-l-30">
                                                <h2 class="m-b-5 name" >Marshall Nichols</h2>
                                                <p class="text-opacity font-size-13">@Marshallnich</p>
                                                <p class="text-dark m-b-10">Frontend Developer, UI/UX Designer</p>
                                                <button class="btn btn-primary btn-tone">Contact</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="d-md-block d-none border-left col-1"></div>
                                            <div class="col">
                                                <ul class="list-unstyled m-t-10">
                                                    <li class="row">
                                                        <p class="col-sm-4 col-4 font-weight-semibold text-dark m-b-5">
                                                            <i class="m-r-10 text-primary anticon anticon-mail"></i>
                                                            <span>Email: </span> 
                                                        </p>
                                                        <p class="col font-weight-semibold"> Marshall123@gmail.com</p>
                                                    </li>
                                                    <li class="row">
                                                        <p class="col-sm-4 col-4 font-weight-semibold text-dark m-b-5">
                                                            <i class="m-r-10 text-primary anticon anticon-phone"></i>
                                                            <span>Phone: </span> 
                                                        </p>
                                                        <p class="col font-weight-semibold"> +12-123-1234</p>
                                                    </li>
                                                    <li class="row">
                                                        <p class="col-sm-4 col-5 font-weight-semibold text-dark m-b-5">
                                                            <i class="m-r-10 text-primary anticon anticon-compass"></i>
                                                            <span>Location: </span> 
                                                        </p>
                                                        <p class="col font-weight-semibold"> Los Angeles, CA</p>
                                                    </li>
                                                </ul>
                                                <div class="d-flex font-size-22 m-t-15">
                                                    <a href="#" class="text-gray p-r-20">
                                                        <i class="anticon anticon-facebook"></i>
                                                    </a>        
                                                    <a href="#" class="text-gray p-r-20">    
                                                        <i class="anticon anticon-twitter"></i>
                                                    </a>
                                                    <a href="#" class="text-gray p-r-20">
                                                        <i class="anticon anticon-behance"></i>
                                                    </a> 
                                                    <a href="#" class="text-gray p-r-20">   
                                                        <i class="anticon anticon-dribbble"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>                       
                    </div>
                </div>

        </div>
    </div>
</div>                         <!-- ' <button class="btn btn-danger btn-sm"   data-action="reject"  onclick="Accept(\'' + actionUrl + '\',\'4\',\'' + id + '\')">Reject</button> '; -->
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function() {

        var id = "{{ $taskId }}";
        var url = "{{ route('company.campaign.statuswiselist') }}";
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
                "type": "POST",
                "headers": {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                "data": function(d) {
                    d.id = id; // $('#search_name').val();
                    d.status = $('#status').val();
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

                }, {
                    'targets': 6,
                    'visible': false,
                    'orderable': false,
                    'render': function(data, type, row) {
                        var viewUrl = '{{ route("company.campaign.userDetails") }}';
                        var id = row[7];
                        return '<button class="btn btn-success  btn-sm" data-action="accept"  data-user_id="'+id +'" title="View" "  onclick="openViewModal(\'' + viewUrl + '\',\'3\',\'' + id + '\')"><i class="fa fa-eye"></button>';                          

                    },
                }

            ],
        });

        table1.column(6).visible(false);
        $(".my-table-tab").on("click", function() {
            $('#status').val($(this).data('status'));
            table1.draw();

            if ($(this).data('status') == '2') {
                table1.column(6).visible(true);
            } else {
                table1.column(6).visible(false);
            }
        });


    });
    function openViewModal(url, action, id) { 
        $.ajax({
            url: url,
            method: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                'action': action,
                'id': id,
            },
            success: (response) => {
            console.log(response.message)
            // $('#view-modal').modal('show');
            $('#view-modal').modal('show');
            $('.addmodle').append("");
            $('.addmodle').append(response.message);
            }
        });
    }

    function Accept(url, action, id) {

        $.ajax({
            url: url,
            method: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                'action': action,
                'id': id,
            },
            success: (response) => {

                if (response.status == 'error') {
                    Swal.fire({
                        text: response.message,
                        icon: "error",
                        button: "Ok",
                    }).then(() => {
                        table1.draw();
                    });
                } else {
                    Swal.fire({
                        text: response.message,
                        icon: "success",
                        button: "Ok",
                    }).then(() => {
                        table1.draw();
                    });
                }
            },
            error: (xhr, status, error) => {
                console.error(xhr.responseText);
                swal({
                    text: 'An error occurred while processing your request.',
                    icon: "error",
                    button: "Ok",
                });
            }
        });
    }
</script>
@endsection