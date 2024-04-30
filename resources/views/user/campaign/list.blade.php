@extends('user.layouts.master')
@section('title', 'Campaign List')
@section('main-content')

    <div class="main-content">

        @include('user.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('user.dashboard') }}" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">Campaign </span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Campaign List</h4>
                <div class="d-flex my-3 align-items-end gap-3">
                    <form method="get" action="{{ route('user.campaign.list') }}" id="searchForm" onsubmit="return validateForm()">
                        <div class="row mt-3">
                            <div class="form-group col-md-3">
                                <label class="font-weight-semibold" for="country">Country:</label>
                                <select name="country" id="country" class="form-control">
                                    <option value="">Select Country</option>
                                    @if (!empty($countrys))
                                        @foreach ($countrys as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-semibold" for="state">State:</label>
                                <select name="state" id="state" class="form-control">
                                    <option value="">Select State</option>
                                    {{-- @if (!empty($states))
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    @endif --}}
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-semibold" for="city">City:</label>
                                <select name="city" id="city" class="form-control">
                                    <option value="">Select City</option>
                                    {{-- @if (!empty($citys))
                                        @foreach ($citys as $city)
                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                    @endif --}}
                                </select>
                            </div>
                            <div class="form-group col-md-3" style="margin-top: 29px;">
                                <button type="button" id="filter_button" class="btn btn-success">Search</button>
                                <a href="{{ route('user.campaign.list') }}" class="btn btn-success ms-2">Refresh</a>
                            </div>
                        </div>
                        {{-- <span class="err" style="display: none;color: red;">Please select any one column</span> --}}
                    </form>
                </div>

                <div class="m-t-25">
                    <table id="campaign_tables" class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Campaign</th>
                                <th data-orderable="false">Reward</th>
                                <th data-orderable="false">Priority</th>
                                <th data-orderable="false">Public</th>
                                <th data-orderable="false">Description</th>
                                <th data-orderable="false">Type</th>
                                <th data-orderable="false">Status</th>
                                <th data-orderable="false">Action</th>
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
            $('#filter_button').on('click', function() {

                // Re-draw the DataTable to apply updated filters
                table.ajax.reload();
            });
            var table = $('#campaign_tables').DataTable({
                // Processing indicator
                "processing": true,
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
                    "url": "{{ route('user.campaign.dtlist') }}",
                    "type": "GET",
                    "headers": {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    "data": function(d) {
                        if ($('#country').val() !== '') {
                            d.country = $('#country').val();
                        }
                        if ($('#state').val() !== '') {
                            d.state = $('#state').val();
                        }
                        if ($('#city').val() !== '') {
                            d.city = $('#city').val();
                        }
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
                        'targets': 3,
                        'visible': true,
                        'orderable': false,
                        'render': function(data, type, row) {
                            return row[6];
                        },
                    },
                    {
                        'targets': 4,
                        'visible': true,
                        'orderable': false,
                        'render': function(data, type, row) {
                            return row[7];
                        },
                    },
                    {
                        'targets': 5,
                        'visible': true,
                        'orderable': false,
                        'render': function(data, type, row) {
                            return row[3];
                        },
                    },
                    {
                        'targets': 6,
                        'visible': true,
                        'orderable': false,
                        'render': function(data, type, row) {
                            return row[4];
                        },
                    },
                    {
                        'targets': 7,
                        'visible': true,
                        'orderable': false,
                        'render': function(data, type, row) {
                            var url = "{{ route('user.campaign.getusercampaign', ':id') }}";
                            url = url.replace(':id', row[0]);
                            type = row[5];
                            var view = "{{ route('user.campaign.view', ':v_id') }}";
                            view = view.replace(':v_id', row[0]);

                            return '<button type="submit" class="btn btn-primary  btn-sm" onclick="showSuccessAlert(\'' + url + '\',\'' +
                                type + '\',\'' + view + '\')" role="button" title="View">Join</button>'

                        },
                    },
                    {
                        'targets': 8,
                        'visible': true,
                        'orderable': false,
                        'render': function(data, type, row) {
                            var viewUrl = "{{ route('user.campaign.view', ':id') }}";
                            viewUrl = viewUrl.replace(':id', row[0]);
                            return '<a class="btn btn-success  btn-sm" href="' + viewUrl +
                                '" role="button" title="View"><i class="fa fa-eye"></i></a>'
                        },
                    }
                ],
            });


        });

        function showSuccessAlert(url, type, view) {
            // Trigger a success sweet alert

            $.ajax({
                url: url,
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Campaign joined successfully',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        if (type == "Social") {
                            window.location.href = view;
                        } else {
                            $('#campaign_tables').DataTable().ajax.reload();
                        }
                    });
                }
            });
        }
    </script>
    <script>
        $(document).ready(function($) {

            $('#country').on('change', function() {
                var country_id = $(this).val();
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "{{ route('user.get_states') }}",
                    type: 'POST',
                    data: {
                        country_id: country_id,
                        _token: CSRF_TOKEN // Include CSRF token in the request data
                    },
                    success: function(response) {
                        $("#city").empty().append("<option value=''>Select City</option>");
                        $("#state").empty().append(response);
                    }
                });
            });

            $('#state').on('change', function() {
                var state_id = $(this).val();
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "{{ route('user.get_city') }}",
                    type: 'POST',
                    data: {
                        state_id: state_id,
                        _token: CSRF_TOKEN // Include CSRF token in the request data
                    },
                    success: function(response) {
                        $("#city").empty().append(response);
                    }
                });
            });
        });
    </script>

@endsection
