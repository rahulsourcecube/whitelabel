@extends('admin.layouts.master')
@section('title', 'Dashboard')
@section('main-content')
    @php
        $currentDate = Carbon\Carbon::now();
        $currentMonth = $currentDate->format('m/Y');
    @endphp
    <!-- Page Container START -->
    <!-- Content Wrapper START -->
    <div class="main-content">
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="avatar avatar-icon avatar-lg avatar-blue">
                                <i class="anticon anticon-dollar"></i>
                            </div>
                            <div class="m-l-15">
                                <h2 class="m-b-0">{{ $total_comapny }}</h2>
                                <p class="m-b-0 text-muted">Company</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="avatar avatar-icon avatar-lg avatar-cyan">
                                <i class="anticon anticon-line-chart"></i>
                            </div>
                            <div class="m-l-15">
                                <h2 class="m-b-0">{{ $total_user }}</h2>
                                <p class="m-b-0 text-muted">Users</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="avatar avatar-icon avatar-lg avatar-gold">
                                <i class="anticon anticon-profile"></i>
                            </div>
                            <div class="m-l-15">
                                <h2 class="m-b-0">{{ $total_campaign }}</h2>
                                <p class="m-b-0 text-muted">Tasks</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="avatar avatar-icon avatar-lg avatar-purple">
                                <i class="anticon anticon-user"></i>
                            </div>
                            <div class="m-l-15">
                                <h2 class="m-b-0">{{ $total_package }}</h2>
                                <p class="m-b-0 text-muted">Packages</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-group"style="margin-bottom: 0px;">
                                <label> <b> Total Revenue </b></label>
                                <br>
                                <div class="d-flex align-items-center">
                                    <div class="form-group col-md-6">
                                        <label for="company">Company</label>
                                        <select id="company" class="form-control" name="company"
                                            onchange="fetchDataAndRenderChart()">
                                            @if($company->count() != 0)
                                            @foreach ($company as $item)
                                                <option value="{{ $item->user_id }}">{{ $item->company_name }}</option>
                                            @endforeach
                                            @else
                                                <option value="">non company available</option>
                                            @endif
                                        </select>
                                    </div>
                                    <input type="text" class="form-control datepicker-input readonly" id="month"
                                        name="month" placeholder="Select month" value="{{ $currentMonth ?? '' }}" readonly
                                        onchange="fetchDataAndRenderChart()" maxlength="50" style="background-color: white">

                                </div>
                            </div>
                        </div>
                        <div class="m-t-50">
                            <canvas class="chart" id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="m-b-0">Company</h5>
                        <div class="m-v-60 text-center" style="height: 280px;">
                                <div class="ct-chart" id="donut-chart"></div>
                        </div>
                        <div class="row border-top p-t-25">
                            <div class="col-4">
                                <div class="d-flex justify-content-center">
                                    <div class="media align-items-center">
                                        <span class="badge badge-success badge-dot m-r-10"></span>
                                        <div class="m-l-5">
                                            <input type="hidden" id="new_user"
                                                value="{{ isset($new_company) ? $new_company : '0' }}">
                                            <h4 class="m-b-0">{{ isset($new_company) ? $new_company : '' }}</h4>
                                            <p class="m-b-0 muted">New</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-flex justify-content-center">
                                    <div class="media align-items-center">
                                        <span class="badge badge-primary badge-dot m-r-10"></span>
                                        <div class="m-l-5">
                                            <input type="hidden" id="old_user"
                                                value="{{ isset($old_company) ? $old_company : '0' }}">
                                            <h4 class="m-b-0">{{ isset($old_company) ? $old_company : '' }}</h4>
                                            <p class="m-b-0 muted">Existing</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-flex justify-content-center">
                                    <div class="media align-items-center">
                                        <span class="badge badge-warning badge-dot m-r-10"></span>
                                        <div class="m-l-5">
                                            <h4 class="m-b-0">{{ isset($total_comapny) ? $total_comapny : '' }}</h4>
                                            <p class="m-b-0 muted">Total</p>
                                        </div>
                                    </div>
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
        $('.datepicker-input').datepicker({
            format: 'mm/yyyy', // Set the date format
            minViewMode: 'months', // Enable only month selection
            autoclose: true // Close the datepicker when a date is selected
        });
    </script>
    <script>
        var new_user = $("#new_user").val();
        var old_user = $("#old_user").val();

        // Use a small non-zero default value
        // var default_value = 0.01;

        new Chartist.Pie('#donut-chart', {
            series: [Math.max(old_user, default_value), Math.max(new_user, default_value)]
        }, {
            donut: true,
            donutWidth: 60,
            donutSolid: true,
            startAngle: 270,
            showLabel: true
        });

    </script>
    <script>
        $(document).ready(function() {
            // Call the function to fetch data and render the chart
            fetchDataAndRenderChart();
        });

        // Function to make AJAX request and render the chart
        function fetchDataAndRenderChart() {
            var month = $("#month").val();
            var company = $("#company").val();
            console.log(month, company);
            // Make an AJAX request using jQuery
            $.ajax({
                url: '{{ route('admin.CompanyRevenue') }}',
                type: 'GET',
                data: {
                    month,
                    company
                },
                success: function(data) {
                    // Extract labels and values
                    var labels = data.map(function(item) {
                        return item.label;
                    });

                    var values = data.map(function(item) {
                        return item.value;
                    });
                    // Create a line chart with an area
                    var ctx = document.getElementById('myChart').getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Company Revenue',
                                data: values,
                                // fill: true, // Fill the area under the line
                                backgroundColor: 'transparent', // Area color
                                borderColor: '#3F87F5', // Line color
                                // borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
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
@endsection
