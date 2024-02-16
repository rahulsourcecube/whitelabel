@extends('user.layouts.master')
@section('title', 'notification List')
@section('main-content')

<div class="main-content">

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
                <table id="user_tables" class="table">
                    <thead>
                        <tr>
                            <th>Message</th>
                            <th>Date</th>

                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($notifications) && count($notifications)>0)
                        @foreach ($notifications as $data)
                        <tr>
                            <td>{!! isset($data->message) ? $data->message : '' !!}</td>
                            <td>{{ isset($data->created_at) ? App\Helpers\Helper::Dateformat($data->created_at) : '' }}</td>

                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan='2' align='center'>No data available in table</td>
                        </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
            var table = $('#user_tables').DataTable({
                // Processing indicator
                "processing": false,
                // DataTables server-side processing mode
                "serverSide": false,
                responsive: true,
                pageLength: 10,
                // Initial no order.
                'order': [],
                language: {
                    search: "",
                    searchPlaceholder: "Search Here",
                },
            });
        });
</script>

@endsection