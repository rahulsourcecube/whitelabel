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
                    'visible': true,
                    'orderable': false,
                    'render': function(data, type, row) {
                        var actionUrl = '{{ route("company.campaign.action") }}';
                        var id = row[0];
                        return '<button class="btn btn-success  btn-sm" data-action="accept" onclick="Accept(\'' + actionUrl + '\',\'3\',\'' + id + '\')">Accept</button>' +
                            ' <button class="btn btn-danger btn-sm"   data-action="reject"  onclick="Accept(\'' + actionUrl + '\',\'4\',\'' + id + '\')">Reject</button> ';

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