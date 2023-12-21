@extends('company.layouts.master')
@section('title', 'Role view')
@section('main-content')

    <div class="main-content">

        @include('company.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">View Role </span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h1>Staff Role</h1>
                
               
                <div class="m-t-25">
                    <table id="data-table" class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>View</th>
                                <th>Add</th>
                                <th>Edit</th>
                                <th>Delete</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                             <tr>
                                <td>Dashboard</td>
                                <td><input type="checkbox"   checked  name="1"></td>
                                <td><input type="checkbox"   checked  name="1"></td>
                                <td><input type="checkbox"    checked name="1"></td>
                                <td><input type="checkbox"   checked  name="1"></td>
                            </tr>
                            <tr>
                                <td>User</td>
                                <td><input type="checkbox"  checked   name="1"></td>
                                <td><input type="checkbox" name="1"></td>
                                <td><input type="checkbox"   checked name="1"></td>
                                <td><input type="checkbox" name="1"></td>
                            </tr>
                            <tr>
                                <td>Create New Task</td>
                                <td><input type="checkbox"  checked  name="1"></td>
                                <td><input type="checkbox" name="1"></td>
                                <td><input type="checkbox" name="1"></td>
                                <td><input type="checkbox"   checked   name="1"></td>
                            </tr>
                            <tr>
                                <td>Task Analytics</td>
                                <td><input type="checkbox" name="1"></td>
                                <td><input type="checkbox" name="1"></td>
                                <td><input type="checkbox" name="1"></td>
                                <td><input type="checkbox" name="1"></td>
                            </tr>
                            <tr>
                                <td>Buy Package</td>
                                <td><input type="checkbox"  checked  name="1"></td>
                                <td><input type="checkbox" name="1"></td>
                                <td><input type="checkbox" name="1"></td>
                                <td><input type="checkbox" name="1"></td>
                            </tr>
                            <tr>
                                <td>Setting</td>
                                <td><input type="checkbox"   checked  name="1"></td>
                                <td><input type="checkbox" name="1"></td>
                                <td><input type="checkbox" name="1"></td>
                                <td><input type="checkbox" name="1"></td>
                            </tr>
                            <tr>
                                <td>Role Manage</td>
                                <td><input type="checkbox" name="1"></td>
                                <td><input type="checkbox" name="1"></td>
                                <td><input type="checkbox" name="1"></td>
                                <td><input type="checkbox" name="1"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        /*This is data table for partership Request */
        $(document).ready(function() {
            var table = $('#user_tables').DataTable({
                // Processing indicator
                "processing": false,
                // DataTables server-side processing mode
                "serverSide": false,
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

                // "ajax": {
                //     "url": "{{ route('company.user.dtlist') }}",
                //     "type": "POST",
                //     "headers": {
                //         "X-CSRF-TOKEN": "{{ csrf_token() }}"
                //     },
                //     "data": function(d) {
                //         // d.search_name = $('#search_name').val();
                //     }
                // },
                // 'columnDefs': [{
                //     'targets': 0,
                //     'visible': false,
                //     'orderable': false,
                //     'render': function(data, type, row) {
                //         return '<input type="checkbox" name="chk_row" value="' + row[0] +
                //             '" class="chk-row">';
                //     },
                // },  {
                //     'targets': 8,
                //     'visible': true,
                //     'orderable': false,
                //     'render': function(data, type, row) {
                //         var viewUrl = '{{ route('admin.package.view', ':id') }}';
                //         var editUrl = '{{ route('admin.package.edit', ':package') }}';
                //         // var viewUrl = '{{ route('admin.company.view', ':id') }}';
                //         viewUrl = viewUrl.replace(':id', row[0]);
                //         editUrl = editUrl.replace(':package', row[0]);

                //         var deleteUrl = '{{ route('admin.package.delete', ':del') }}';
                //         deleteUrl = deleteUrl.replace(':del', row[0]);

                //         return '<a class="btn btn-success  btn-sm" href="' + viewUrl +
                //             '" role="button" title="View"><i class="fa fa-eye"></i></a> <a class="btn btn-primary btn-sm" href="' +
                //             editUrl +
                //             '" role="button"  title="Edit"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger btn-sm" role="button"  href="javascript:void(0)" onclick="sweetAlertAjax(\'' +
                //             deleteUrl + '\')"  title="Delete"><i class="fa fa-trash"></i></a>';

                //     },
                // }],
            });
        });
    </script>

@endsection
