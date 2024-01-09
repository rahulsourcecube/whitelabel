@extends('company.layouts.master')
@section('title', 'Users List')
@section('main-content')
    <div class="main-content">
        <style>
            .table>tbody>tr>td,
            .table>tbody>tr>th,
            .table>tfoot>tr>td,
            .table>tfoot>tr>th,
            .table>thead>tr>td,
            .table>thead>tr>th {

                max-width: 500px;
            }
        </style>
        @include('user.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('user.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">Notification </span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Notification</h4>
                <div class="m-t-25">
                    <table id="notificationList" class="table">
                        <thead>
                            <tr>

                                <th></th>
                                <th>Message</th>
                                <th>Date</th>

                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            var table = $('#notificationList').DataTable({
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
                    "url": "{{ route('company.notification.dtlist') }}",
                    "type": "POST",
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
                }],
            });
        });
    </script>

@endsection
