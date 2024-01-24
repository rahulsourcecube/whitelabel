@extends('admin.layouts.master')
@section('title', 'Package List')
@section('main-content')

    <div class="main-content">

        @include('admin.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">Package</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Package List</h4>

                <a class="btn btn-primary float-right" href="{{ route('admin.package.create') }}" role="button">Add New</a>
                <div>
                    <table id="package_tbales" class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Duration</th>
                                <th>Price</th>
                                <th>No Of Campaign</th>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        /*This is data table for partership Request */
        $(document).ready(function() {
            var table = $('#package_tbales').DataTable({
                "processing": true,
                "serverSide": true,
                responsive: true,
                pageLength: 10,
                'order': [
                    [0, 'desc']
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search Here",
                },
                // Load data from an Ajax source
                "ajax": {
                    "url": "{{ route('admin.package.dtlist') }}",
                    "type": "POST",
                    "headers": {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    "data": function(d) {}
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
                        'targets': 6,
                        'visible': false,
                        'orderable': false,
                        'render': function(data, type, row) {
                            var imagurl = '{{ asset('uploads/package') }}/' + row[6];
                            return ' <img id="" class="packageimage" src="' + imagurl +
                                '" style="height: 100px; width: 100px;">';

                        },
                    },
                    {
                        'targets': 7,
                        'visible': true,
                        'orderable': false,
                        'render': function(data, type, row) {
                            var viewUrl = '{{ route('admin.package.view', ':id') }}';
                            var editUrl = '{{ route('admin.package.edit', ':package') }}';
                            viewUrl = viewUrl.replace(':id', row[0]);
                            editUrl = editUrl.replace(':package', row[0]);

                            var deleteUrl = '{{ route('admin.package.delete', ':del') }}';
                            deleteUrl = deleteUrl.replace(':del', row[0]);

                            return '<a class="btn btn-success  btn-sm" href="' + viewUrl +
                                '" role="button" title="View"><i class="fa fa-eye"></i></a> <a class="btn btn-primary btn-sm" href="' +
                                editUrl +
                                '" role="button"  title="Edit"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger btn-sm" role="button"  href="javascript:void(0)" onclick="sweetAlertAjax(\'' +
                                deleteUrl + '\')"  title="Delete"><i class="fa fa-trash"></i></a>';

                        },
                    }
                ],
            });
        });
    </script>
    <script>
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
                            if (response.status === 'error') {
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
