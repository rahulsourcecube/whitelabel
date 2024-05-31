@extends('company.layouts.master')
@section('title', 'Users List')

@section('main-content')
    <div class="main-content">
        @include('company.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">User</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Users List</h4>
                {{-- @if (isset($totalData) && count($totalData) > 0) --}}
                {{-- @endif --}}
                <div class="float-right">
                    @can('user-list')
                        <a class="btn btn-primary " href="javascript: void(0);" onclick="openModels()" role="button">Import</a>
                    @endcan
                    @if (isset($totalUsers) && count($totalUsers) > 0)
                        @can('user-list')
                            <a class="btn btn-primary " href="{{ route('company.user.export') }}" role="button">Export</a>
                        @endcan
                    @endif
                    @can('user-create')
                        <a class="btn btn-primary " href="{{ route('company.user.create') }}" role="button">Add New</a>
                    @endcan
                </div>
                <div>
                    <table id="user_tables" class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Profile</th>
                                <th>Status</th>
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
    <div class="modal fade add-import-file">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4">Import User</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="anticon anticon-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="container">
                            <div class="col-md-8 col-md-offset-2">
                                <h3>Import User </h3>
                                <form id="import_form" method="POST" action="{{ route('company.user.import') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <div class="input-group input-file">
                                            <input id="import_file" type="file" class="form-control" name="import_file"
                                                accept=".xlsx" />
                                        </div>
                                        {{-- <div id="excel_icon" style="display: none;">
                                            <i class="fas fa-file-excel"></i> <!-- FontAwesome Excel icon -->
                                        </div> --}}
                                    </div>
                                    <div class="form-group">
                                        <a href="{{ asset('public/download/users-sample.xlsx') }}" class="btn btn-success"
                                            download="users-sample.xlsx" title="Sample Download">Sample download</a>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).ready(function() {
            // Preview Excel icon
            $('#import_file').change(function() {
                var input = this;
                var fileName = input.files[0].name;
                var ext = fileName.split('.').pop().toLowerCase();
                if (ext === 'xlsx') {
                    $('#excel_icon').show();
                } else {
                    $('#excel_icon').hide();
                    alert('Please select a valid .xlsx file.');
                    $(this).val(''); // Clear the file input field
                }
            });

            // Validate file type
            $('#import_form').submit(function() {
                var fileName = $('#import_file').val();
                var ext = fileName.split('.').pop().toLowerCase();
                if (ext !== 'xlsx') {
                    alert('Please select a valid .xlsx file.');
                    return false;
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            var table = $('#user_tables').DataTable({
                // Processing indicator
                "processing": true,
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
                    "url": "{{ route('company.user.dtlist') }}",
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
                        'visible': false,
                        'orderable': false,
                        'render': function(data, type, row) {
                            return '<input type="checkbox" name="chk_row" value="' + row[0] +
                                '" class="chk-row">';
                        },
                    }, {
                        'targets': 4,
                        'visible': false,
                        'orderable': false,
                        'render': function(data, type, row) {
                            var imagurl = row[4];
                            if (imagurl == "") {
                                return "-";
                            } else {
                                return ' <img id="" class="packageimage" src="' + imagurl +
                                    '" style="height: 100px; width: 100px;">';
                            }
                        },
                    },
                    {
                        'targets': 5,
                        'visible': true,
                        'orderable': true,
                        'render': function(data, type, row) {
                            var status = row[5];
                            if (status == "Active") {
                                return '  <button class="btn btn-success btn-sm">' + status +
                                    '</button>'

                            } else {
                                return '  <button class="btn btn-danger btn-sm">' + status +
                                    '</button>'
                            }
                        },
                    }, {
                        'targets': 6,
                        'visible': true,
                        'orderable': false,
                        'render': function(data, type, row) {
                            var viewUrl = '{{ route('company.user.view', ':id') }}';
                            var editUrl = '{{ route('company.user.edit', ':package') }}';
                            viewUrl = viewUrl.replace(':id', row[0]);
                            editUrl = editUrl.replace(':package', row[0]);
                            var deleteUrl = '{{ route('company.user.delete', ':del') }}';
                            deleteUrl = deleteUrl.replace(':del', row[0]);
                            return '<a class="btn btn-success  btn-sm" href="' + viewUrl +
                                '" role="button" title="View"><i class="fa fa-eye"></i></a> @can('user-edit') <a class="btn btn-primary btn-sm  " href="' +
                                editUrl +
                                '" role="button"  title="Edit"><i class="fa fa-pencil"></i></a> @endcan @can('user-delete')<a class="btn btn-danger btn-sm  " role="button"  href="javascript:void(0)" onclick="sweetAlertAjax(\'' +
                                deleteUrl +
                                '\')"  title="Delete"><i class="fa fa-trash"></i></a> @endcan';
                        },
                    }
                ],
            });
        });

        function openModels() {

            $('.add-import-file').modal('show');
            var fileName = $('#import_file').val();
        }


        function sweetAlertAjax(deleteUrl) {
            // Use SweetAlert for confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If the user confirms, proceed with AJAX deletion
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: (response) => {

                            if (response.success == 'error') {
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
