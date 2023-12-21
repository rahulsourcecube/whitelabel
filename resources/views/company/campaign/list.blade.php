@extends('company.layouts.master')
@section('title', 'Campaign List')
@section('main-content')
    <div class="main-content">
        @include('company.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">Campaign </span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Task List</h4>
                {{-- <a class="btn btn-primary float-right" href="{{ route('company.campaign.create') }}" role="button">Add
                    New</a> --}}
                <div class="m-t-25">
                    <table id="campaign_tables" class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Reward</th>
                                <th>Description</th>
                                <th>Type</th>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).ready(function() {
            var taskType = "{{ $type }}";
            var taskTypeString = "{{ $taskType }}";
            var url = "{{ route('company.campaign.tdlist', ':type') }}";
            url = url.replace(':type', taskType);
            var table = $('#campaign_tables').DataTable({
                "processing": false,
                "serverSide": true,
                responsive: true,
                pageLength: 25,
                'order': [
                    [0, 'desc']
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search Here",
                },
                "ajax": {
                    "url": url,
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
                }, 
                {
                    'targets': 5,
                    'visible': true,
                    'orderable': false,
                    'render': function(data, type, row) {
                        var status = row[5];
                        if(status == "Active"){
                            return '  <button class="btn btn-success ">'+status+'</button>'

                        }else{
                            return '  <button class="btn btn-danger ">'+status+'</button>'
                        }
                    },
                },{
                    'targets': 6,
                    'visible': true,
                    'orderable': false,
                    'render': function(data, type, row) {
                        console.log(taskTypeString);
                        var viewUrl = '{{ route('company.campaign.view', [':taskType' ,':id']) }}';
                        var editUrl = '{{ route('company.campaign.edit', [':taskType', ':id']) }}';
                        viewUrl = viewUrl.replace(':taskType', taskTypeString);
                        viewUrl = viewUrl.replace(':id', row[0]);
                        editUrl = editUrl.replace(':taskType', taskTypeString);
                        editUrl = editUrl.replace(':id', row[0]);
                        var deleteUrl = '{{ route('company.campaign.delete', ':del') }}';
                        deleteUrl = deleteUrl.replace(':del', row[0]);
                        return '<a class="btn btn-success  btn-sm" href="' + viewUrl +
                            '" role="button" title="View"><i class="fa fa-eye"></i></a> @can("task-edit") <a class="btn btn-primary btn-sm" href="' +
                            editUrl +
                            '" role="button"  title="Edit"><i class="fa fa-pencil"></i></a> @endcan @can("task-delete") <a class="btn btn-danger btn-sm" role="button"  href="javascript:void(0)" onclick="sweetAlertAjax(\'' +
                            deleteUrl + '\')"  title="Delete"><i class="fa fa-trash"></i></a>@endcan';

                    },
                }],
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
