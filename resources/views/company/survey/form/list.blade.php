@extends('company.layouts.master')
@section('title', 'Employee List')
@section('main-content')

    <div class="main-content">

        @include('company.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">Survey Form List</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Survey Form List</h4>
                <a class="btn btn-primary float-right" href="{{ route('company.survey.form.create') }}" role="button">Add New</a>
                <div>
                    <table id="surveyform" class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Title</th>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).ready(function() {
            var table = $('#surveyform').DataTable({
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
                    "url": "{{ route('company.survey.form.list') }}",
                    "type": "GET",
                    "headers": {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    "data": function(d) {
                        // Additional data if needed
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
                    // Adjust column indexes based on your data structure
                    {
                        'targets': 2, // Assuming the second column in your data corresponds to this action column
                        'visible': true,
                        'orderable': false,
                        'render': function(data, type, row) {
                            var view = '{{ route('company.survey.form.view', ':survey') }}';
                            view = view.replace(':survey', row[0]);
                            var editUrl = '{{ route('company.survey.form.edit', ':survey') }}';
                            editUrl = editUrl.replace(':survey', row[0]);
                            var deleteUrl = '{{ route('company.survey.form.delete', ':survey') }}';
                            deleteUrl = deleteUrl.replace(':survey', row[0]);
                            return '</a><a class="btn btn-success btn-sm" href="' +
                                view +
                                '" role="button"  title="Field View"><i class="fa fa-eye"></i></a>  <a class="btn btn-primary btn-sm" href="' +
                                editUrl +
                                '" role="button"  title="Edit"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger btn-sm" role="button"  href="javascript:void(0)" onclick="sweetAlertAjax(\'' +
                                deleteUrl + '\')"  title="Delete"><i class="fa fa-trash"></i></a> ';
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
