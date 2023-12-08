@extends('company.layouts.master')
@section('title', 'Social Sheare List')
@section('main-content')

    <div class="main-content">

        @include('company.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">Social Sheare </span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Social Sheare List</h4>
                <a class="btn btn-primary float-right" href="{{route('company.campaign.create')}}" role="button">Add New</a>
                <div class="m-t-25">
                    <table id="user_tables" class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Reward</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>Join Our Facebook Page</td>
                            <td>$500</td>
                            <td>Now you can browse privately, and other people who ...</td>
                            <td>Social </td>

                            <td><a class="btn btn-success  btn-sm" href="" role="button" title="View">Active</a></td>
                            <td>
                                <a class="btn btn-success  btn-sm" href="{{route('company.campaign.view')}}"
                                    role="button" title="View"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-primary btn-sm" href="{{route('company.campaign.create')}}"
                                    role="button" title="Edit"><i class="fa fa-pencil"></i></a>
                                <a class="btn btn-danger btn-sm" role="button" href="javascript:void(0)"
                                    onclick="sweetAlertAjax()"><i class="fa fa-trash"></i></a>
                            </td>
                            </tr>
                            <tr>
                                <td>Join Our Facebook Page</td>
                                <td>$500</td>
                                <td>Now you can browse privately, and other people who ...</td>
                                <td>Social </td>
    
                                <td><a class="btn btn-danger  btn-sm" href="" role="button" title="View">Active</a></td>
                                <td>
                                    <a class="btn btn-success  btn-sm" href="{{route('company.campaign.view')}}"
                                        role="button" title="View"><i class="fa fa-eye"></i></a>
                                    <a class="btn btn-primary btn-sm" href="{{route('company.campaign.create')}}"
                                        role="button" title="Edit"><i class="fa fa-pencil"></i></a>
                                    <a class="btn btn-danger btn-sm" role="button" href="javascript:void(0)"
                                        onclick="sweetAlertAjax()"><i class="fa fa-trash"></i></a>
                                </td>
                                </tr>
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
