@extends('user.layouts.master')
@section('title', 'Progress reward List')
@section('main-content')

    <div class="main-content">

        @include('user.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('user.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">Progress Reward </span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Progress Reward</h4>
                <form method="post" action="{{ route('user.progress.search') }}">
                    @csrf
                    <div class="form-row mt-3">
                        <div class="form-group col-md-2">
                            <label class="font-weight-semibold" for="name">From Date:</label>
                            <input class="form-control datepicker-input" placeholder="Select Date" name="from_date"
                                readonly>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-weight-semibold" for="name">Two Date:</label>
                            <input class="form-control datepicker-input" placeholder="Select Date" name="two_date" readonly>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-weight-semibold" for="name">Type:</label>
                            <select name="type" class="form-control">
                                <option value="">Select Type</option>
                                <option value="1">Referral</option>
                                <option value="2">Social</option>
                                <option value="3">Custom</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-weight-semibold" for="name">Status:</label>
                            <select name="status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="1">Claim Reward</option>
                                <option value="2">Claim Pending</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2" style="margin-top: 29px;">
                            <button type="submit" class="btn btn-success">Search</button>
                        </div>
                    </div>
                </form>
                <div class="m-t-15">
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
                            @foreach ($progressReward as $data)
                                <tr>
                                    <td>{{ isset($data->getCampaign->title) ? $data->getCampaign->title : '' }}</td>
                                    <td>{{ isset($data->reward) ? $data->reward : '' }}</td>
                                    <td>{!! isset($data->getCampaign->description) ? $data->getCampaign->description : '' !!}</td>
                                    <td>{{ isset($data->getCampaign->task_type) ? $data->getCampaign->task_type : '' }}</td>
                                    <td>
                                        @if (isset($data->status) && $data->status == 1)
                                            <form method="post"
                                                action="{{ route('user.progress.claimReward', $data->id) }}">
                                                @csrf
                                                <button class="btn btn-primary  btn-sm" role="button">Claim reward</button>
                                            </form>
                                        @endif
                                        @if (isset($data->status) && $data->status == 2)
                                            <span class="btn btn-info btn-sm">Claim Pending</span>
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

@endsection
@section('js')
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
