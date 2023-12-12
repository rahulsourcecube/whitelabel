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
            <a class="btn btn-primary float-right" href="{{ route('company.user.create') }}" role="button">Add New</a>
            <div class="m-t-25">
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

@endsection
@section('js')
<script>
    $(document).ready(function () {
        $(document).ready(function() {
            var table = $('#user_tables').DataTable({
                // Processing indicator
                "processing": true,
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
                    'visible': true,
                    'orderable': false,
                    'render': function(data, type, row) {
                        var imagurl = row[4];
                        if(imagurl == ""){
                            return "-";
                        }else{
                            return ' <img id="" class="packageimage" src="' + imagurl +
                                '" style="height: 100px; width: 100px;">';
                        }
                    },
                }, {
                    'targets': 6,
                    'visible': true,
                    'orderable': false,
                    'render': function(data, type, row) {
                        var viewUrl = '{{ route('company.user.edit', ':id') }}';
                        var editUrl = '{{ route('company.user.edit', ':package') }}';
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
                }],
            });
        });
    })
</script>
@endsection