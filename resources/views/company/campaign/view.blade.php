@extends('company.layouts.master')
@section('title', 'Campaign List')
@section('main-content')
    <!-- Content Wrapper START -->
    <div class="main-content">
        @include('company.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item">Campaign</span>
                    <span class="breadcrumb-item active">VIew </span>
                </nav>
            </div>
        </div>
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if (isset($task) && $task->image != '' && file_exists(base_path('uploads/company/campaign/' . $task->image)))
                                <img class="card-img-top" src="{{ asset('uploads/company/campaign/' . $task->image) }}"
                                    class="w-100 img-responsive">
                            @else
                                <img src="{{ asset('assets/images/others/No_image_available.png') }}"
                                    class="w-100 img-responsive">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4 class="m-b-10">{{ $task->title ?? '' }}</h4>
                            <div class="d-flex align-items-center m-t-5 m-b-15">
                                <div class="m-l-10">
                                    <span class="text-gray font-weight-semibold">
                                        @if ($task->type == '1')
                                            {{ 'Referral' }}
                                        @elseif($task->type == '2')
                                            {{ 'Social Share' }}
                                        @else
                                            {{ 'Custom' }}
                                        @endif
                                    </span>
                                    @if ($task->type == '1')
                                    <span class="m-h-5 text-gray">|</span>
                                    <span class="text-gray"> <b>No of referral users: </b>{{ $task->no_of_referral_users ?? '' }}</span>
                                    @endif
                                    <span class="m-h-5 text-gray">|</span>
                                    <span class="text-gray">{{  App\Helpers\Helper::Dateformat($task->expiry_date) ?? '' }}</span>
                                </div>
                            </div>
                            <p class="m-b-20">{!! $task->description !!}</p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="m-t-25">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item my-table-tab" data-status="1">
                                    <a class="nav-link active" id="user-tab" data-toggle="pill" href="#Joined_user"
                                        role="tab" aria-controls="user" aria-selected="true">Recently Joined User </a>
                                </li>
                                <li class="nav-item my-table-tab" data-status="2">
                                    <a class="nav-link" id="request-tab" data-toggle="pill" href="#request" role="tab"
                                        aria-controls="request" aria-selected="false">Approval Requset</a>
                                </li>
                                <li class="nav-item my-table-tab" data-status="5">
                                    <a class="nav-link" id="reopen-tab" data-toggle="pill" href="#reopen" role="tab"
                                        aria-controls="reopen" aria-selected="false">Reopen</a>
                                </li>
                                <li class="nav-item my-table-tab" data-status="3">
                                    <a class="nav-link" id="accept-tab" data-toggle="pill" href="#accept" role="tab"
                                        aria-controls="accept" aria-selected="false">Accepted</a>
                                </li>
                                <li class="nav-item my-table-tab" data-status="4">
                                    <a class="nav-link" id="reject-tab" data-toggle="pill" href="#reject" role="tab"
                                        aria-controls="reject" aria-selected="false">Rejected</a>
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
            <div class="modal-content addmodle">

            </div>

        </div>
    </div>



@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        var id = "{{ $taskId }}";
        var url = "{{ route('company.campaign.statuswiselist') }}";
        var table1 = $('#user_joind').DataTable({
            // Processing indicator
            "processing": false,
            // DataTables server-side processing mode
            "serverSide": true,
            responsive: true,
            pageLength: 10,
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
                        var viewUrl = '{{ route('company.campaign.userDetails', [':id']) }}';
                        viewUrl = viewUrl.replace(':id', row[0]);
                        return '<a class="btn btn-primary btn-sm" href="' +
                            viewUrl +
                            '" role="button"  title="View"><i class="fa fa-eye"></i>';

                    },
                }

            ],
        });

        table1.column(6).visible(false);
        $(document).on("click", ".my-table-tab", function() {
            $('#status').val($(this).data('status'));
            table1.draw();
            if ($(this).data('status') == '2' || $(this).data('status') == '5') {
                table1.column(6).visible(true);
            } else {
                table1.column(6).visible(false);
            }
        });


        function openViewModal(url, action, id) {

            $.ajax({
                url: url,
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'action': action,
                    'id': id,
                },
                success: (response) => {

                    $('#view-modal').modal('show');
                    $('.addmodle').html(" ");
                    $('.addmodle').html(response.message);

                }
            });
        }

        $(document).on("click", ".action", function() {
            action = $(this).data('action');
            id = $(this).data('id');
            url = "{{ route('company.campaign.action') }}";
            $.ajax({
                url: url,
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'action': action,
                    'id': id,
                    'dataType': 'json'
                },
                success: (response) => {

                    if (response.success == 'error') {
                        Swal.fire({
                            text: response.messages,
                            icon: "error",
                            button: "Ok",
                        }).then(() => {
                            $('#view-modal').modal('hide');
                        });
                    } else {
                        Swal.fire({
                            text: response.messages,
                            icon: "success",
                            button: "Ok",
                        }).then(() => {
                            location.reload(true);
                            $('#view-modal').modal('hide');
                        });
                    }
                },
                error: (xhr, status, error) => {
                    console.error(xhr.responseText);
                    Swal.fire({
                        text: 'An error occurred while processing your request.',
                        icon: "error",
                        button: "Ok",
                    });
                }
            });
        });
    </script>
    <script>
        // Get the scroll position of a specific element or class
        function getScrollPosition() {
            var element = document.querySelector('.your-class'); // replace 'your-class' with your actual class name
            return element.scrollTop;
        }

        // Set the scroll position of a specific element or class
        function setScrollPosition(position) {
            var element = document.querySelector('.your-class'); // replace 'your-class' with your actual class name
            element.scrollTop = position;
        }

        // Refresh the page while keeping the scroll position
        function refreshPage() {
            var scrollPosition = getScrollPosition();

            // Perform the page refresh
            location.reload(true);

            // Set the scroll position back after the refresh (use a timeout to ensure the DOM is ready)
            setTimeout(function() {
                setScrollPosition(scrollPosition);
            }, 0);
        }

        // Call the refreshPage function as needed, for example, on a button click
        document.getElementById('refreshButton').addEventListener('click', refreshPage);
    </script>
@endsection
