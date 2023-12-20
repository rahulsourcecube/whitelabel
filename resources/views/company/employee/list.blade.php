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
            <a class="btn btn-primary float-right" href="{{route('company.employee.create')}}" role="button">Add New</a>
            <div class="m-t-25">
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
                        {{-- <tr>
                            <td>John Doe</td>
                            <td>John@mailinataor.com</td>
                            <td>Manager</td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="{{route('company.employee.create')}}"
                                    role="button" title="Edit"><i class="fa fa-pencil"></i></a>
                                <a class="btn btn-danger btn-sm" role="button" href="javascript:void(0)"
                                    onclick="sweetAlertAjax()"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>Tom Jery</td>
                            <td>tom@mailinataor.com</td>
                            <td>Staff</td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="{{route('company.employee.create')}}"
                                    role="button" title="Edit"><i class="fa fa-pencil"></i></a>
                                    <a class="btn btn-danger btn-sm" role="button" href="javascript:void(0)"
                                    onclick="sweetAlertAjax()"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr> --}}

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>


    $(document).ready(function() {
        // alert('hello');
        var table = $('#user_tables').DataTable({
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

            // {
            //     'targets': 5,
            //     'visible': true,
            //     'orderable': false,
            //     'render': function(data, type, row) {
            //         var status = row[5];
            //         if(status == "Active"){
            //             return '  <button class="btn btn-success ">'+status+'</button>'

            //         }else{
            //             return '  <button class="btn btn-danger ">'+status+'</button>'
            //         }
            //     },
            // },
             {
                'targets': 4,
                'visible': true,
                'orderable': false,
                'render': function(data, type, row) {
                    var editUrl = '{{ route('company.employee.edit', ':package') }}';
                    editUrl = editUrl.replace(':package', row[0]);
                    var deleteUrl = '{{ route('company.employee.delete', ':del') }}';
                    deleteUrl = deleteUrl.replace(':del', row[0]);
                    return '<a class="btn btn-primary btn-sm" href="' +
                        editUrl +
                        '" role="button"  title="Edit"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger btn-sm" role="button"  href="javascript:void(0)" onclick="sweetAlertAjax(\'' +
                        deleteUrl + '\')"  title="Delete"><i class="fa fa-trash"></i></a>';

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

                        if (response.status == 'error') {
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
                        swal({
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

