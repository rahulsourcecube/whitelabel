@extends('user.layouts.master')
@section('title', 'my Reward List')
@section('main-content')

    <div class="main-content">

        @include('user.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('user.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">My Reward </span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>my Reward</h4>
                <div class="m-t-25">
                    <table id="user_tables" class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Reward</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Status</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($myReward as $data)
                                <tr>
                                    <td>{{ isset($data->getCampaign->title) ? $data->getCampaign->title : '' }}</td>
                                    <td>{{ isset($data->reward) ? $data->reward : '' }}</td>
                                    <td>{!! isset($data->getCampaign->description) ? $data->getCampaign->description : '' !!}</td>
                                    <td>{{ isset($data->getCampaign->task_type) ? $data->getCampaign->task_type : '' }}</td>
                                    <td>
                                        @if (isset($data->status) && $data->status == 3)
                                            <span class="btn btn-success  btn-sm">Completed</span>
                                        @elseif (isset($data->status) && $data->status == 4)
                                            <span class="btn btn-danger  btn-sm">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
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
                pageLength: 25,
                // Initial no order.
                'order': [
                    [0, 'desc']
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search Here",
                },
            });
        });
    </script>
@endsection
