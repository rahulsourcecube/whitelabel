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
                <div class="d-flex my-3 align-itmes-end gap-3">
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
                            <div class="form-group col-md-2 mb-0">
                                <label class="font-weight-semibold" for="name">From Date:</label>
                                <input class="form-control datepicker-input"
                                    value="{{ isset(request()->from_date) ? request()->from_date : '' }}"
                                    placeholder="Select Date" name="from_date">
                            </div>
                            <div class="form-group col-md-2 mb-0">
                                <label class="font-weight-semibold" for="name">To Date:</label>
                                <input class="form-control datepicker-input"
                                    value="{{ isset(request()->two_date) ? request()->two_date : '' }}"
                                    placeholder="Select Date" name="two_date">
                            </div>
                            <div class="form-group col-md-3 mb-0">
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
                            <div class="form-group col-md-3 mb-0">
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
                            <div class="form-group col-md-2 mb-0" style="margin-top: 29px;">
                                <button type="submit" class="btn btn-success">Search</button>
                            </div>
                        </div>
                        <span class="err" style="display: none;color: red;">Please select any one column</span>
                    </form>
                    <div class="form-group mb-0  mt-auto" style="height: fit-content">
                        <a href="{{ route('user.progress.reward') }}"><button type="submit"
                                class="btn btn-success">Refresh</button></a>
                    </div>
                </div>
                <div class="m-t-15">
                    <table id="user_tables" class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Reward</th>
                                <th>Priority</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($filterResults as $data)
                                <?php $campaign_id = base64_encode($data->campaign_id); ?>

                                <tr>
                                    <td>{{ isset($data->getCampaign->title) ? $data->getCampaign->title : '-' }}</td>
                                    <td>{{ $data->text_reward ? Str::limit($data->text_reward, 15) : (isset($data->reward) ? \App\Helpers\Helper::getcurrency() . $data->reward : '0') }}
                                    </td>
                                    @php
                                        $priority = '-';
                                        switch ($data->getCampaign->priority) {
                                            case 1:
                                                $priority = "<span class='text-danger'>High</span>";
                                                break;
                                            case 2:
                                                $priority = "<span class='text-info'>Medium</span>";
                                                break;
                                            case 3:
                                                $priority = "<span class='text-success'>Low</span>";
                                                break;
                                        }
                                    @endphp
                                    <td>{!! isset($data->getCampaign->priority) ? $priority : '-' !!}</td>


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
                                    <td>{{ isset($data->getCampaign->task_type) ? $data->getCampaign->task_type : '-' }}
                                    </td>
                                    <td>
                                        @if (isset($data->status) && $data->status == 1)
                                            @if (isset($data->getCampaign->type) && $data->getCampaign->type != '1')
                                                <span class=" text-primary " role="button">Claim
                                                    reward</span>
                                            @else
                                                @if (isset($data->getCampaign->task_expired) && $data->getCampaign->task_expired == 'Expired')
                                                    <span class=" text-primary " role="button">Claim
                                                        reward</span>
                                                @else
                                                    <span class=" text-primary " role="button">Claim
                                                        reward</span>
                                                @endif
                                            @endif
                                        @endif
                                        @if (isset($data->status) && $data->status == 2)
                                            <span class="text-info ">Claim Pending</span>
                                        @elseif(isset($data->status) && $data->status == 5)
                                            <span class="text-danger ">Reopen</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-success  btn-sm"
                                            href="{{ route('user.campaign.view', $campaign_id) }}" role="button"
                                            title="View"><i class="fa fa-eye"></i></a>
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
                pageLength: 10,
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
