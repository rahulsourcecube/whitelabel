@extends('company.layouts.master')
@section('title', 'Employee List')
@section('main-content')

    <div class="main-content">

        @include('company.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">Employee List</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Employee List</h4>
                <div class="float-right">
                    @can('employee-management-list')
                        <a class="btn btn-primary " href="javascript: void(0);" onclick="openModels()" role="button">Import</a>
                    @endcan
                    @if (isset($totalData) && count($totalData) > 0)
                        @can('employee-management-list')
                            <a class="btn btn-primary " href="{{ route('company.employee.export') }}" role="button">Export</a>
                        @endcan
                    @endif
                    @can('employee-management-create')
                        <a class="btn btn-primary  " href="{{ route('company.employee.create') }}" role="button">Add
                            New</a>
                    @endcan
                </div>
                <div>
                    <table id="user_tables" class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Email Address</th>
                                <th>Role</th>
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
                    <h5 class="modal-title h4">Import Employees</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="anticon anticon-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="container">
                            <div class="col-md-8 col-md-offset-2">
                                <h3>Import Employees</h3>
                                <form id="import_form" method="POST" action="{{ route('company.employee.import') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <div class="input-group input-file">
                                            <input id="import_file" type="file" class="form-control" name="import_file"
                                                accept=".xlsx , .csv" />
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                                <div class="form-group">
                                    <a href="{{ asset('public/download/employee-sample.xlsx') }}" class="btn btn-success"
                                        download="employee-sample.xlsx" title="Sample.xlsx Download">Sample.xlsx
                                        download</a>
                                    <a href="{{ asset('public/download/employee-sample.csv') }}" class="btn btn-success"
                                        download="employee-sample.csv" title="Sample.csv Download">Sample.csv download</a>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Preview Excel icon
            $('#import_file').change(function() {
                var input = this;
                var fileName = input.files[0].name;
                var ext = fileName.split('.').pop().toLowerCase();
                if (ext === 'xlsx' || ext === 'csv') {
                    $('#excel_icon').show();
                } else {
                    $('#excel_icon').hide();
                    alert('Please select a valid .xlsx or .csv file.');
                    $(this).val(''); // Clear the file input field
                }
            });

            // Validate file type
            $('#import_form').submit(function() {
                var fileName = $('#import_file').val();
                var ext = fileName.split('.').pop().toLowerCase();
                if (ext !== 'xlsx' && ext !== 'csv') {
                    alert('Please select a valid .xlsx or .csv file.');
                    return false;
                }
            });
        });
    </script>
    <script>
        function openModels() {
            var fileName = $('#import_file').val();

            $('.add-import-file').modal('show');
        }
        $(document).ready(function() {
            var table = $('#user_tables').DataTable({
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
                    "url": "{{ route('company.employee.elist') }}",
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
                        'targets': 4,
                        'visible': true,
                        'orderable': false,
                        'render': function(data, type, row) {
                            var editUrl = '{{ route('company.employee.edit', ':package') }}';
                            editUrl = editUrl.replace(':package', row[0]);
                            var deleteUrl = '{{ route('company.employee.delete', ':del') }}';
                            deleteUrl = deleteUrl.replace(':del', row[0]);
                            return '@can('employee-management-edit')<a class="btn btn-primary btn-sm" href="' +
                                editUrl +
                                '" role="button"  title="Edit"><i class="fa fa-pencil"></i></a> @endcan @can('employee-management-delete')<a class="btn btn-danger btn-sm" role="button"  href="javascript:void(0)" onclick="sweetAlertAjax(\'' +
                                deleteUrl +
                                '\')"  title="Delete"><i class="fa fa-trash"></i></a> @endcan';

                        },
                    }
                ],
            });
        });


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
