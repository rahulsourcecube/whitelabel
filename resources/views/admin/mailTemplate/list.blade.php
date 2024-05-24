@extends('admin.layouts.master')
@section('title', 'Mail Template List')
@section('main-content')

    <div class="main-content">

        @include('company.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">Mail Template List</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Mail Template List</h4>
                <a class="btn btn-primary float-right " href="{{ route('admin.mail.create') }}" role="button">Add New</a>
                <div>
                    <table id="mailtemplate" class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Type</th>
                                <th>Subject</th>
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
    <div class="modal fade send-mail-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4">Send mail</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="anticon anticon-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <input type="hidden" class="form-control " name="add_more_count" id="add-more-count" value="0"
                            placeholder="">
                        <form id="sendMail" method="POST" action="{{ route('admin.mail.sendMail') }}"
                            data-parsley-validate="">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-10">
                                    <label for="title" class=" col-form-label">Email addres</label>
                                    <input type="email" class="form-control" name="mail[]" id="" value=""
                                        placeholder="Enter mail">
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
                                    {{-- <button type="button" class="btn btn-primary all-mail-send d-none"
                                        onclick="sendAllMail(this)">All</button> --}}
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/parsley.min.js?v=' . time()) }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        $(document).ready(function() {

            $('#submit').click(function() {

                $('#sendMail').validate({
                    rules: {

                        'mail[]': {
                            required: true
                        }
                    },
                    messages: {

                        'mail[]': {
                            required: "Please enter a email",
                        }
                    }
                });
            });
        });
        $(document).ready(function() {

            $('#submit').click(function() {

                $('#sendMail').validate({
                    rules: {

                        'mail[]': {
                            required: true
                        }
                    },
                    messages: {

                        'mail[]': {
                            required: "Please enter a email",
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
                        <label for="" class=" col-form-label">Email addres</label>
                        <input type="email" class="form-control" name="mail[]" id="email` + nc + `" value=""
                            placeholder="Enter mail" required >
                    </div>
                    <div class=" col-md-2">
                        <label for="title" class="col-form-label"></label>

                        <button type="button" onclick="removeMore(this)" class="btn btn-danger"
                            style="margin-top:35px"><li class="fa fa-trash"></li></button>
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
            $('.send-mail-modal').modal('show');
            if (type === 'custom') {

                $(".all-mail-send").removeClass("d-none");

            } else {
                $(".all-mail-send").addClass("d-none");
            }

        }

        function sendAllMail(e) {


            var message = 'Send mail'

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to ' + message + ', right?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, ' + message + ' !'
            }).then((result) => {

                if (result.isConfirmed) {
                    var type = $('input[name="template_type"]').val();

                    var user_id = $('input[name="template_id"]').val();

                    $.ajax({
                        url: "{{ route('admin.mail.send.all') }}",
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
                    "url": "{{ route('admin.mail.template.list') }}",
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
                        'targets': 3,
                        'visible': true,
                        'orderable': false,
                        'render': function(data, type, row) {
                            var editUrl = '{{ route('admin.mail.template.edit', ':package') }}';
                            editUrl = editUrl.replace(':package', row[0]);
                            // var deleteUrl = '{{ route('company.employee.delete', ':del') }}';
                            // deleteUrl = deleteUrl.replace(':del', row[0]);
                            return '<a class="btn btn-primary btn-sm" href="' +
                                editUrl +
                                '" role="button"  title="Edit"><i class="fa fa-pencil"></i></a> <a class="btn btn-primary btn-sm " href="javascript: void(0); " onclick="openModels(\'' +
                                row[0] + '\',\'' + row[3] +
                                '\')"    role=" button "  title="Send Mail">Send Mail</a> ';

                        },
                    }
                ],
            });
        });
    </script>

@endsection
