@extends('admin.layouts.master')
@section('title', 'SMS Template List')
@section('main-content')

    <div class="main-content">

        @include('admin.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">Sms Template List</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Sms Template List</h4>
                <a class="btn btn-primary float-right " href="{{ route('admin.sms.create') }}" role="button">Add New</a>
                <div>
                    <table id="mailtemplate" class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Type</th>
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
    <div class="modal fade send-sms-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4">Send SMS</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="anticon anticon-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <input type="hidden" class="form-control " name="add_more_count" id="add-more-count" value="0"
                            placeholder="=">
                        <form id="smsMail" method="POST" action="{{ route('admin.sms.sendSms') }}"
                            data-parsley-validate="">
                            @csrf
                            {{-- <div class="col-md-4 mb-3 all-sms-send d-none">
                                <div class="form-group align-items-center">
                                    <label for="expiry_date"> Send SMS All User</label>
                                    <div class="switch m-r-10">
                                        <input type="checkbox" id="public-1" data-toggle="switch"
                                            onclick="sendAllSms(this)" name="public" value="true">
                                        <label for="public-1"></label>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row mb-3">
                                <div class="col-md-10">
                                    <label for="contact_number" class=" col-form-label">Contact Number</label>
                                    <input type="text" class="form-control" id="contact" placeholder="Contact Number"
                                        maxlength="10" name="contact_number[]" value=""
                                        onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                </div>
                                <div class=" col-md-2">
                                    <label for="title" class="col-form-label"></label>

                                    <button type="button" onclick="addMore(this)" class="btn btn-primary"
                                        style="margin-top:35px">Add</button>
                                </div>
                            </div>
                            <div class="add-more">

                            </div>
                            <div class="row mb-2">
                                <input type="hidden" class="" name="template_id" id="template_id" value="">
                                <input type="hidden" class="" name="template_type" id="" value="">
                                <div class="col-md-12">
                                    <button type="submit" id="submit" class="btn btn-primary mr-2">Submit</button>
                                    {{-- <button type="button" class="btn btn-primary all-sms-send d-none"
                                        onclick="sendAllSms(this)">All</button> --}}
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).ready(function() {
            var table = $('#mailtemplate').DataTable({
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
                    "url": "{{ route('admin.sms.template.list') }}",
                    "type": "get",
                    "headers": {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    "data": function(d) {
                        // d.search_name = $('#search_name').val();
                    }
                },
                'columnDefs': [{
                        'targets': 0,
                        'visible': false,
                        'orderable': false,
                        'render': function(data, type, row) {
                            return '<input type="checkbox" name="chk_row" value="' + row[0] +
                                '" class="chk-row">';
                        },
                    },
                    {
                        'targets': 2,
                        'visible': true,
                        'orderable': false,
                        'render': function(data, type, row) {
                            var editUrl = '{{ route('admin.sms.template.edit', ':package') }}';
                            editUrl = editUrl.replace(':package', row[0]);

                            return '<a class="btn btn-primary btn-sm" href="' +
                                editUrl +
                                '" role="button"  title="Edit"><i class="fa fa-pencil"></i></a><a class="btn btn-primary btn-sm " href="javascript: void(0); " onclick="openModels(\'' +
                                row[3] + '\',\'' + row[2] +
                                '\')"    role=" button "  title="sms ">Send SMS</a>  ';

                        },
                    }
                ],
            });
        });
    </script>
    <script src="{{ asset('assets/js/parsley.min.js?v=' . time()) }}"></script>
    <script>
        $(document).ready(function() {

            $('#submit').click(function() {

                $('#smsMail').validate({
                    rules: {

                        'contact_number[]': {
                            required: true,
                            minlength: '10',
                            digits: true
                        }
                    },
                    messages: {

                        'contact_number[]': {
                            required: "Please enter a contact number",
                        }
                    }
                });
            });
        });

        function addMore(e) {
            count = 1;
            oldCount = $('#add-more-count').val()
            nc = parseInt(oldCount) + 1
            newCount = $('#add-more-count').val(nc)


            html = `<div  class="row mb-3 remove-div">
             <div class="col-md-10">
                 <label for="" class=" col-form-label">Contact Number</label>
                 <input type="text" class="form-control" id="contact` + nc + `"
                                                         placeholder="Contact Number" maxlength="10"
                                                         name="contact_number[]" value="" required
                                                         onkeypress="return event.charCode >= 48 && event.charCode <= 57">
             </div>
             <div class=" col-md-2">
                 <label for="title" class="col-form-label"></label>

                 <button type="button" onclick="removeMore(this)" class="btn btn-danger" style="margin-top:35px">
                     <li class="fa fa-trash"></li>
                 </button>
             </div>
         </div>`;
            $('.add-more').append(html);


        }

        function removeMore(e) {
            $(e).parent().parent().remove()
        }

        function openModels(id, type) {
            $('.remove-div').remove()
            $('input[type="email"]').val("");
            $('input[name="template_type"]').val(type);
            $('input[name="template_id"]').val(id);
            $('.send-sms-modal').modal('show');
            if (type == 'custom') {


                $(".all-sms-send").removeClass("d-none");

            } else {
                $(".all-sms-send").addClass("d-none");
            }

        }

        function sendAllSms(e) {


            var message = 'Send Sms'

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to ' + message + ', right?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, ' + message + ' !'
            }).then((result) => {
                e.checked = result.isConfirmed ? isChecked : !isChecked;
                if (result.isConfirmed) {
                    var type = $('input[name="template_type"]').val();

                    var user_id = $('input[name="template_id"]').val();

                    $.ajax({
                        url: "{{ route('admin.sms.send.all') }}",
                        type: 'POST',
                        data: {
                            id: user_id,
                            type: type,
                            filed: 'sms',
                            _token: "{{ csrf_token() }}"
                        },
                        success: (response) => {

                            if (!response.success) {
                                // Handle error case
                                Swal.fire({
                                    text: response.message,
                                    icon: "error",
                                    button: "Ok",
                                }).then(() => {
                                    // Reload the page or take appropriate action
                                    location.reload();
                                });
                            } else {

                                // Handle success case
                                Swal.fire({
                                    text: response.message,
                                    icon: "success",
                                    button: "Ok",
                                }).then(() => {
                                    // Reload the page or take appropriate action
                                    location.reload();
                                });
                            }
                        },
                        error: (xhr, status, error) => {
                            // Handle AJAX request error
                            console.error(xhr.responseText);
                            Swal.fire({
                                text: 'An error occurred while processing your request.',
                                icon: "error",
                                button: "Ok",
                            });
                        }

                    });
                }
            });

        }
    </script>

@endsection
