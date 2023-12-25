@extends('user.layouts.master')
@section('title', 'Campaign List')
@section('main-content')

<div class="main-content">

    @include('user.includes.message')
    <div class="page-header">
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a href="{{ route('user.dashboard') }}" class="breadcrumb-item"><i
                        class="anticon anticon-home m-r-5"></i>Dashboard</a>
                <span class="breadcrumb-item active">Campaign </span>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4>Campaign List</h4>
            <div class="m-t-25">
                <table id="campaign_tables" class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Campaign</th>
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function() {
        var table = $('#campaign_tables').DataTable({
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
                "url": "{{ route('user.campaign.dtlist') }}",
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
                    var url = '{{ route('user.campaign.getusercampaign', ':id') }}';
                    url = url.replace(':id', row[0]);
                    return '<button type="submit" class="btn btn-primary  btn-sm" onclick="showSuccessAlert(\''+url+'\')" role="button" title="View">Join</button>'
                    
                },
            }, {
                'targets': 6,
                'visible': true,
                'orderable': false,
                'render': function(data, type, row) {
                    var viewUrl = '{{ route('user.campaign.view',':id') }}';
                    viewUrl = viewUrl.replace(':id', row[0]);
                    return '<a class="btn btn-success  btn-sm" href="' + viewUrl +
                        '" role="button" title="View"><i class="fa fa-eye"></i></a>'
                },
            }],
        });

        
    });
    function showSuccessAlert(url) {
        // Trigger a success sweet alert
            $.ajax({
                url:url,
                method:"POST",
                data:{
                    "_token":"{{csrf_token()}}",
                },
                success:function(data){
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Campaign joined successfully',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                    $('#campaign_tables').DataTable().ajax.reload();
                }
            });
        }
</script>

@endsection