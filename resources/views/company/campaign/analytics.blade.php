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
										<label>Date:</label>
										<div class="input-affix m-b-10">
											<i class="prefix-icon anticon anticon-calendar"></i>
											<input type="text" class="form-control datepicker2 attribute"
												   placeholder="Pick a date" id="referral_from_date"
												   value="{{ $startDate }}">
											<input type="hidden" class="form-control " id="referral_from_date2"
												   value="{{ $startDate }}-{{ $endDate }}">

										</div>
										<label id="referral_task_date_range">Date: <b>From</b> {{ $startDate }} <b>to</b>
											{{ $endDate }}</label>
									</div>
									<div class="col-md-12">
                                        <button id="filterdata" class="btn btn-primary m-t-30" disabled>Filter <span
                                                class="spinner"></span></button>

                                    </div>
								</div>
                                <div class="col-md-9">
                                    <div class="ct-chart" id="simple-line-referral"></div>
                                    {{-- <div class="ct-chart" id="simple-line-referral-filter"></div> --}}
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
                                                    <option value="{{ $item->id }}">{{ Str::limit($item->title, 35) }}
                                                    </option>
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
                                    @php
                                        $start = Carbon\Carbon::now()->startOfMonth()->format('m/d/Y');
                                        $end = Carbon\Carbon::now()->format('m/d/Y');
                                    @endphp
                                    <div class="form-group col-md-12">
                                        <label>From Date:</label>
                                        <div class="input-affix m-b-10">
                                            <i class="prefix-icon anticon anticon-calendar"></i>
                                            <input type="text" class="form-control datepicker" placeholder="Pick a date"
                                                id="ref_from_date" value="{{ $start }}">
                                            <input type="hidden" class="form-control " id="ref_from_date2"
                                                value="{{ $start }}">

                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>To Date:</label>
                                        <div class="input-affix m-b-10">
                                            <i class="prefix-icon anticon anticon-calendar"></i>
                                            <input type="text" class="form-control datepicker" placeholder="Pick a date"
                                                id="ref_to_date" value="{{ $end }}">
                                            <input type="hidden" class="form-control" id="ref_to_date2"
                                                value="{{ $end }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button class="btn btn-primary m-t-30"
                                            id="fetchSocialDataAndRenderChart">Filter</button>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <table id="campaign_tables" class="table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Name</th>
                                                <th>Count</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
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

    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <script>
        $('.datepicker').datepicker();
        var endDate = '{{$startDate}}';

        $(".datepicker2").datepicker({
            endDate: endDate
        });

        var user_total = {!! json_encode($user_total) !!};
        user_total = JSON.parse(user_total);
        new Chartist.Line('#simple-line-referral', {
            labels: user_total.day,
            series: [
                user_total.total_user,
            ],
            low: 0
        }, {
            showArea: true,
            fullWidth: true,
            chartPadding: {
                right: 50
            },
            low: 0,
            axisY: {
                labelInterpolationFnc: function(value) {
                    return Math.round(value);
                },
                onlyInteger: true
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

        $('.attribute').on('change', function() {
			var fromDate = $("#referral_from_date").val();

			// Split the date string into day, month, and year components
			var parts = fromDate.split('/');
			var day = parseInt(parts[1], 10);
			var month = parseInt(parts[0], 10) - 1; // Months are zero-based in JavaScript
			var year = parseInt(parts[2], 10);

			// Create a new Date object with the extracted components
			var fromDateObj = new Date(year, month, day);

			// Add 6 days to the date
			fromDateObj.setDate(fromDateObj.getDate() + 6);

			// Format the resulting date back into the desired format
			var formattedMonth = String(fromDateObj.getMonth() + 1).padStart(2, '0');
			var formattedDay = String(fromDateObj.getDate()).padStart(2, '0');
			var formattedYear = fromDateObj.getFullYear();

			var formattedDate = formattedMonth + '/' + formattedDay + '/' + formattedYear;

			 $("#referral_from_date2").val(fromDate + '-' + formattedDate);

			$("#referral_task_date_range").html('Date: <b>From</b> ' + fromDate + ' <b>to</b> ' + formattedDate);
            $('#filterdata').removeAttr("disabled");
        });

        $('#date_filter').daterangepicker({
            startDate: moment().subtract(6, 'days'), // Start date is 6 days ago
            endDate: moment(), // End date is today
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
                    $(".spinner").html('');
                    var user_total = data;
                    new Chartist.Line('#simple-line-referral', {
                        labels: user_total.day,
                        series: [
                            user_total.total_user,
                        ],
                        low: 0
                    }, {
                        showArea: true,
                        fullWidth: true,
                        chartPadding: {
                            right: 50
                        },
                        low: 0,
                        axisY: {
                            labelInterpolationFnc: function(value) {
                                return Math.round(value);
                            },
                            onlyInteger: true
                        }
                    });
                    // if (data.total_user == "" ) {
                    //     alert(null);
                    // //     $('#filterdata').prop('disabled', true);
                    // // $(".spinner").html('');
                    // //     var user_total = {!! json_encode($user_total) !!};
                    // //     user_total = JSON.parse(user_total);
                    // //     new Chartist.Line('#simple-line-referral', {
                    // //         labels: user_total.day,
                    // //         series: [
                    // //             user_total.total_user,
                    // //         ],
                    // //         low: 0
                    // //     }, {
                    // //         showArea: true,
                    // //         fullWidth: true,
                    // //         chartPadding: {
                    // //             right: 50
                    // //         },
                    // //         low: 0,
                    // //         axisY: {
                    // //             labelInterpolationFnc: function(value) {
                    // //                 return Math.round(value);
                    // //             },
                    // //             onlyInteger: true
                    // //         }
                    // //     });

                    // // $('#simple-line-referral').remove();
                    // $(".spinner").html('');
                    // $('#filterdata').prop('disabled', true);

                    // var user_total = {!! json_encode($user_total) !!};
                    // user_total = JSON.parse(user_total);
                    // new Chartist.Line('#simple-line-referral', {
                    //     labels: user_total.day,
                    //     series: [
                    //         user_total.total_user,
                    //     ]
                    // }, {
                    //     showArea: true,
                    //     fullWidth: true,
                    //     chartPadding: {
                    //         right: 50
                    //     },
                    //     low: 0,
                    //     axisY: {
                    //         labelInterpolationFnc: function(value) {
                    //             return Math.round(value);
                    //         },
                    //         onlyInteger: true
                    //     }
                    // });

                    // } else {



                    // $('#simple-line-referral').remove();
                    //     $(".spinner").html('');
                    //     $('#filterdata').prop('disabled', true);
                    //     var user_total = data;
                    //     new Chartist.Line('#simple-line-referral-filter', {
                    //         labels: user_total.day,
                    //         series: [
                    //             user_total.total_user,
                    //         ]
                    //     }, {
                    //         showArea: true,
                    //         fullWidth: true,
                    //         chartPadding: {
                    //             right: 50
                    //         },
                    //         low: 0,
                    //         axisY: {
                    //             labelInterpolationFnc: function(value) {
                    //                 return Math.round(value);
                    //             },
                    //             onlyInteger: true
                    //         }
                    //     });
                    // // }
                },
                error: function() {

                    // $('#simple-line-referral').remove();
                    $(".spinner").html('');
                    $('#filterdata').prop('disabled', false);

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
                        },
                        low: 0,
                        axisY: {
                            labelInterpolationFnc: function(value) {
                                return Math.round(value);
                            },
                            onlyInteger: true
                        }
                    });
                }
            });
        });

        $('.datepicker-input').datepicker({
            minViewMode: 2,
            format: 'yyyy'
        });

        $(document).ready(function() {
            // Call the function to fetch data and render the chart
            fetchDataAndRenderChart();
        });

        // Function to make AJAX request and render the chart
        function fetchDataAndRenderChart() {
            var year = $("#year").val();
            var title = $("#Tasks").val();

            // Make an AJAX request using jQuery
            $.ajax({
                url: '{{ route('company.campaign.custom') }}',
                type: 'POST',
                data: {
                    year,
                    title,
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                // "headers": {
                //     "X-CSRF-TOKEN": "{{ csrf_token() }}"
                // },
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
                                label: 'Completed',
                                data: total_completeds,
                                backgroundColor: 'transparent', // Area color
                                borderColor: '#3F87F5', // Line color
                            }, {
                                label: 'Joined',
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

                    console.log('Error fetching data:', error);
                }
            });
        }

        var _token = $('input[name="_token"]').val();

        var campaign_tables = $('#campaign_tables').DataTable({
            "processing": true,
            "serverSide": true,
            "searchable": true,
            "responsive": true,
            pageLength: 10,
            'order': [
                [0, 'desc']
            ],
            // Load data from an Ajax source
            "ajax": {
                "url": '{{ route('company.campaign.getSocialAnalytics') }}',
                "type": "post",
                "headers": {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                "data": function(d) {
                    d.from_date = $('#ref_from_date2').val();
                    d.to_date = $('#ref_to_date2').val();
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


        // var campaign_tables = $('#campaign_tables').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     ajax: {
        //         url:
        //         type: 'POST',
        //         data: {
        //             _token: "{{ csrf_token() }}"
        //         }
        //     },
        //     "data": function(d) {

        //     },
        //     // headers: {
        //     //     "X-CSRF-TOKEN": "{{ csrf_token() }}"
        //     // },
        //     columns: [{
        //             data: 'title',
        //             name: 'Name'
        //         },
        //         {
        //             data: 'social_task_user_count',
        //             name: 'Count'
        //         },
        //     ]
        // });
        $(document).on("click", "#fetchSocialDataAndRenderChart", function() {
            $("#ref_to_date2").val($("#ref_to_date2").val());
            $("#ref_from_date2").val($("#ref_from_date").val());
            campaign_tables.draw();
        })
    </script>
@endsection
