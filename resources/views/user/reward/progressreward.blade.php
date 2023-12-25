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
                <form method="get" action="{{ route('user.progress.reward') }}" id="searchForm"
                    onsubmit="return validateForm()">

                    @if (isset(request()->status))
                        <input type="hidden" name="status" value="{{ request()->status }}">
                    @elseif(isset(request()->status))
                        <input type="hidden" name="from_date" value="{{ request()->from_date }}">
                    @elseif (isset(request()->type))
                        <input type="hidden" name="type" value="{{ request()->type }}">
                    @endif

                    <div class="form-row mt-3">
                        <div class="form-group col-md-2">
                            <label class="font-weight-semibold" for="name">From Date:</label>
                            <input class="form-control datepicker-input"
                                value="{{ isset(request()->from_date) ? request()->from_date : '' }}"
                                placeholder="Select Date" name="from_date">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-weight-semibold" for="name">Two Date:</label>
                            <input class="form-control datepicker-input"
                                value="{{ isset(request()->two_date) ? request()->two_date : '' }}"
                                placeholder="Select Date" name="two_date">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-weight-semibold" for="name">Type:</label>
                            <select name="type" class="form-control">
                                <option value="">Select Type</option>
                                <option value="1"
                                    {{ isset(request()->type) && request()->type === '1' ? 'selected' : '' }}>Referral
                                </option>
                                <option value="2"
                                    {{ isset(request()->type) && request()->type === '2' ? 'selected' : '' }}>Social
                                </option>
                                <option value="3"
                                    {{ isset(request()->type) && request()->type === '3' ? 'selected' : '' }}>Custom
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-weight-semibold" for="name">Status:</label>
                            <select name="status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="1"
                                    {{ isset(request()->status) && request()->status === '1' ? 'selected' : '' }}>Claim
                                    Reward</option>
                                <option value="2"
                                    {{ isset(request()->status) && request()->status === '2' ? 'selected' : '' }}>Claim
                                    Pending</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2" style="margin-top: 29px;">
                            <button type="submit" class="btn btn-success">Search</button>
                        </div>
                    </div>
                    <span class="err" style="display: none;color: red;">Please select any one column</span>
                </form>
                <div class="form-group col-md-2">
                    <a href="{{ route('user.progress.reward') }}"
                        style="margin-left: 773px;margin-top: -65px;position: absolute;"><button type="submit"
                            class="btn btn-success">Refresh</button></a>
                </div>
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
                            @foreach ($filterResults as $data)
                                <tr>
                                    <td>{{ isset($data->getCampaign->title) ? $data->getCampaign->title : '' }}</td>
                                    <td>{{ isset($data->reward) ? $data->reward : '' }}</td>
                                    <td>
                                        @if (isset($data->getCampaign->description))
                                            <span class="truncated-description" style="cursor: pointer;"
                                                data-full-description="{!! strip_tags($data->getCampaign->description) !!}">
                                                {!! \Illuminate\Support\Str::limit(strip_tags($data->getCampaign->description), 10) !!}
                                            </span>
                                        @else
                                            -
                                        @endif

                                        <div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog"
                                            aria-labelledby="descriptionModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="descriptionModalLabel">Full
                                                            Description</h5>
                                                        <button type="button" class="close" data- dismiss="modal"
                                                            aria-label="Close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p id="fullDescription"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    {{-- <td>{!! isset($data->getCampaign->description) ? $data->getCampaign->description : '' !!}</td> --}}
                                    <td>{{ isset($data->getCampaign->task_type) ? $data->getCampaign->task_type : '' }}
                                    </td>
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


        function validateForm() {
            var fromValue = $("input[name='from_date']").val();
            var twoValue = $("input[name='two_date']").val();
            var typeValue = $("select[name='type']").val();
            var statusValue = $("select[name='status']").val();

            if (typeValue === '' && statusValue === '' && fromValue === '' && twoValue === '') {
                $('.err').css("display", "block");
                setTimeout(function() {
                    $(".err").css("display", "none");
                }, 3000);
                return false;
            }

            return true;
        }
    </script>

    <script>
        $(document).ready(function() {
            $('.truncated-description').click(function() {
                var fullDescription = $(this).data('full-description');
                $('#fullDescription').text(fullDescription);
                $('#descriptionModal').modal('show');
            });
        });
    </script>
@endsection
