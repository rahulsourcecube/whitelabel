@extends('company.layouts.master')
@section('title', 'Analytics')
@section('main-content')
    @php
        $currentYear = date('Y');
    @endphp
    <div class="main-content">
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">Analytics</span>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4>Referral Tasks</h4>
                        <p>Referral Tasks Analytics Of The Week</p>

                        <div class="m-t-25">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group col-md-12">
                                        <label class="font-weight-semibold" for="date_filter">Date:</label>
                                        <input type="text" class="form-control attribute" name="date_range_filter"
                                            id="date_filter" placeholder="From Date">
                                    </div>
                                    {{-- <div class="form-group col-md-12">
                                    <label class="font-weight-semibold" for="phoneNumber">To Date:</label>
                                    <input type="date" class="form-control" id="phoneNumber">
                                </div> --}}
                                    <div class="col-md-12">
                                        <button id="filterdata" class="btn btn-primary m-t-30" disabled>Filter <span
                                                class="spinner"></span></button>

                                        {{-- <button class="btn btn-success m-t-30">Export</button> --}}
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="ct-chart" id="simple-line-referral"></div>
                                    <div class="ct-chart" id="simple-line-referral-filter"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4>Custom Tasks</h4>
                        <p>Custom Tasks Analytics Of The Year</p>
                        <div class="m-t-25">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group col-md-12">
                                        <label class="font-weight-semibold" for="userName">Select Year :</label>
                                        <input type="text" class="form-control datepicker-input" id="year"
                                            name="year" placeholder="Select Year" value="{{ $currentYear ?? '' }}"
                                            readonly>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="Tasks">Tasks</label>
                                        <select id="Tasks" class="form-control" name="Tasks">
                                            @if (count($customTasks) > 0)
                                                @foreach ($customTasks as $item)
                                                    <option value="{{ $item->id }}">{{ Str::limit($item->title,35) }}</option>
                                                @endforeach
                                            @else
                                                <option value="">Task not found</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <button class="btn btn-primary m-t-30"
                                            onclick="fetchDataAndRenderChart()">Filter</button>

                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <canvas class="ct-chart" id="myChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4>Social Share Tasks</h4>
                        <p>Social Share Tasks Analytics Of The Week</p>
                        <div class="m-t-25">
                            <div class="row">
                                <div class="col-md-3">
                                    {{-- <form> --}}
                                    @php
                                        $start = Carbon\Carbon::now()
                                            ->startOfMonth()
                                            ->format('m/d/Y');
                                        $end = Carbon\Carbon::now()->format('m/d/Y');
                                        // dd($start,$end)
                                    @endphp
                                    <div class="form-group col-md-12">
                                        <label>From Date:</label>
                                        <div class="input-affix m-b-10">
                                            <i class="prefix-icon anticon anticon-calendar"></i>
                                            <input type="text" class="form-control datepicker" placeholder="Pick a date"
                                                id="from_date" value="{{ $start }}">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>To Date:</label>
                                        <div class="input-affix m-b-10">
                                            <i class="prefix-icon anticon anticon-calendar"></i>
                                            <input type="text" class="form-control datepicker" placeholder="Pick a date"
                                                id="to_date" value="{{ $end }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button class="btn btn-primary m-t-30"
                                            onclick="fetchSocialDataAndRenderChart()">Filter</button>
                                    </div>
                                    {{-- </form> --}}
                                </div>
                                <div class="col-md-9">
                                    <table id="campaign_tables" class="table">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Count</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    {{-- <div class="ct-chart" id="simple-line-social-share"></div> --}}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('js')
    <script>
        $('.datepicker').datepicker();

        var user_total = {!! json_encode($user_total) !!};
        user_total = JSON.parse(user_total);
        new Chartist.Line('#simple-line-referral', {
            labels: user_total.day,
            series: [
                user_total.total_user,
            ]
        }, {
            showArea: true,
            fullWidth: true,
            chartPadding: {
                right: 50
            }
        });

        new Chartist.Line('#simple-line-social-share', {
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            series: [
                [2, 11, 6, 8, 15, 4, 8],
                [2, 8, 3, 4, 9, 0, 2]
            ]
        }, {
            fullWidth: true,
            chartPadding: {
                right: 40
            }
        });

        new Chartist.Line('#simple-line-custom', {
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            series: [
                [2, 11, 6, 8, 15, 0],
                [2, 8, 3, 4, 9, 78]
            ]
        }, {
            fullWidth: true,
            chartPadding: {
                right: 40
            }
        });
    </script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <script>
        $('.attribute').on('click', function() {
            $('#filterdata').removeAttr("disabled");
        });
        $('#date_filter').daterangepicker({
            dateLimit: {
                days: 7
            },
            locale: {
                format: 'YYYY/MM/DD'
            },
        });
        $('#filterdata').on('click', function() {

            var date_range_filter = $('#date_filter').val();
            $.ajax({
                url: "{{ route('company.campaign.fetch_data') }}",
                method: "POST",
                data: {
                    date_range_filter: date_range_filter,
                },
                "headers": {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    $(".spinner").html('<i class="fa-solid fa-spinner"></i>');
                    $('#filterdata').prop('disabled', true);
                },
                success: function(data) {
                    $('#simple-line-referral').remove();
                    $(".spinner").html('');
                    $('#filterdata').prop('disabled', false);
                    var user_total = data;
                    console.log(user_total);
                    new Chartist.Line('#simple-line-referral-filter', {
                        labels: user_total.day,
                        series: [
                            user_total.total_user,
                        ]
                    }, {
                        showArea: true,
                        fullWidth: true,
                        chartPadding: {
                            right: 50
                        }
                    });
                },
                error: function() {
                    var user_total = {!! json_encode($user_total) !!};
                    user_total = JSON.parse(user_total);
                    new Chartist.Line('#simple-line-referral', {
                        labels: user_total.day,
                        series: [
                            user_total.total_user,
                        ]
                    }, {
                        showArea: true,
                        fullWidth: true,
                        chartPadding: {
                            right: 50
                        }
                    });
                }
            });
        });
    </script>




    <script>
        $('.datepicker-input').datepicker({
            minViewMode: 2,
            format: 'yyyy'
        });
    </script>

    <script>
        $(document).ready(function() {
            // Call the function to fetch data and render the chart
            fetchDataAndRenderChart();
            fetchSocialDataAndRenderChart();
        });

        // Function to make AJAX request and render the chart
        function fetchDataAndRenderChart() {
            var year = $("#year").val();
            var title = $("#Tasks").val();

            // Make an AJAX request using jQuery
            $.ajax({
                url: '{{ route('company.campaign.Custom') }}',
                type: 'POST',
                data: {
                    year,
                    title
                },
                "headers": {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                success: function(data) {
                    // Extract labels and values
                    var labels = data.map(function(item) {
                        return item.label;
                    });

                    var total_completeds = data.map(function(item) {
                        return item.total_completed;
                    });
                    var total_joineds = data.map(function(item) {
                        return item.total_joined;
                    });
                    // Create a line chart with an area
                    var ctx = document.getElementById('myChart').getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Completeds',
                                data: total_completeds,
                                // fill: true, // Fill the area under the line
                                backgroundColor: 'transparent', // Area color
                                borderColor: '#3F87F5', // Line color
                                // borderWidth: 1
                            }, {
                                label: 'joineds',
                                data: total_joineds,
                                backgroundColor: 'transparent',
                                borderColor: 'cyan',
                                pointBackgroundColor: 'cyan',
                                pointBorderColor: 'white',
                                pointHoverBackgroundColor: 'cyanLight',
                                pointHoverBorderColor: 'cyanLight',
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                },
                                yAxes: [{
                                    ticks: {
                                        stepSize: 1
                                    }
                                }],
                            }
                        }
                    });
                },
                error: function(xhr, status, error) {

                    console.error('Error fetching data:', error);
                }
            });
        }
    </script>
    <script>
        function fetchSocialDataAndRenderChart() {

            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();

            var _token = $('input[name="_token"]').val();

            $('#campaign_tables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('company.campaign.getSocialAnalytics') }}',
                    type: 'POST',
                    data: {
                        from_date: from_date,
                        to_date: to_date,
                        _token: "{{ csrf_token() }}"
                    }
                },
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                columns: [{
                        data: 'title',
                        name: 'Name'
                    },
                    {
                        data: 'social_task_user_count',
                        name: 'Count'
                    },
                ]
            });
        };
    </script>
@endsection
