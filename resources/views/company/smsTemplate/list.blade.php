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
                <span class="breadcrumb-item active">Sms Template List</span>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4>Sms Template List</h4>
           <a class="btn btn-primary float-right " href="{{route('company.sms.create')}}" role="button">Add New</a> 
            <div >
                <table id="mailtemplate" class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Type</th>                           
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
                "url": "{{ route('company.sms.template.list') }}",
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
                'targets': 2,
                'visible': true,
                'orderable': false,
                'render': function(data, type, row) {
                    var editUrl = '{{ route('company.sms.template.edit', ':package') }}';
                    editUrl = editUrl.replace(':package', row[0]);
                    // var deleteUrl = '{{ route('company.employee.delete', ':del') }}';
                    // deleteUrl = deleteUrl.replace(':del', row[0]);
                    return '<a class="btn btn-primary btn-sm" href="' +
                        editUrl +
                        '" role="button"  title="Edit"><i class="fa fa-pencil"></i></a>  ';

                },
            }
        ],
        });
    });



</script>

@endsection

