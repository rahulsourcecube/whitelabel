@extends('user.layouts.master')
@section('title', 'Analytics')
@section('main-content')
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
                    <h4>My Referral Activity </h4>
                    <p>Referral activity over time</p>

                    <div class="m-t-25">
                        <div class="row">
                            <div class="col-md-3">
                                <form>

                                    <div class="form-group col-md-12">
                                        <label class="font-weight-semibold" for="userName">From Date:</label>
                                        <input type="date" class="form-control" id="userName" placeholder="From Date">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="font-weight-semibold" for="phoneNumber">To Date:</label>
                                        <input type="date" class="form-control" id="phoneNumber">
                                    </div>
                                    <div class="col-md-12">
                                        <button class="btn btn-primary m-t-30">Filter</button>

                                        <button class="btn btn-success m-t-30">Export</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-9">
                                <div class="ct-chart" id="horizontal-bar"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4>Top Referrers </h4>
                    <div class="m-t-25">
                        <div class="row">
                            <div class="col-md-3">
                                <form>

                                    <div class="form-group col-md-12">
                                        <label class="font-weight-semibold" for="userName">From Date:</label>
                                        <input type="date" class="form-control" id="userName" placeholder="From Date">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="font-weight-semibold" for="phoneNumber">To Date:</label>
                                        <input type="date" class="form-control" id="phoneNumber">
                                    </div>
                                    <div class="col-md-12">
                                        <button class="btn btn-primary m-t-30">Filter</button>

                                        <button class="btn btn-success m-t-30">Export</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-9">
                                <div class="ct-chart" id="stacked-bar"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        {{-- <div class="col-md-12 hidden">

            <div class="card">
                <div class="card-body">
                    <h4>Conversion rates</h4>
                    <p>Conversion rates Analytics Of The Week</p>
                    <div class="m-t-25">
                        <div class="row">
                            <div class="col-md-3">
                                <form>

                                    <div class="form-group col-md-12">
                                        <label class="font-weight-semibold" for="userName">From Date:</label>
                                        <input type="date" class="form-control" id="userName" placeholder="From Date">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="font-weight-semibold" for="phoneNumber">To Date:</label>
                                        <input type="date" class="form-control" id="phoneNumber">
                                    </div>
                                    <div class="col-md-12">
                                        <button class="btn btn-primary m-t-30">Filter</button>

                                        <button class="btn btn-success m-t-30">Export</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-9">
                                <div class="ct-chart" id="simple-line-custom"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

    </div>



</div>
<script>
    new Chartist.Line('#simple-line-referral', {
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            series: [
                [2, 11, 6, 8, 15],
                [2, 8, 3, 4, 9]
            ]
        }, {
            fullWidth: true,
            chartPadding: {
                right: 40
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
    new Chartist.Bar('#horizontal-bar', {
    labels: ['user1', 'user2', 'user3', 'user4', 'user5'],
    series: [
        [5, 4, 3, 7, 5]
    ]
}, {
    seriesBarDistance: 10,
    reverseData: true,
    horizontalBars: true,
    axisY: {
        offset: 70
    }
});
new Chartist.Bar('#stacked-bar', {
    labels: ['Q1', 'Q2', 'Q3', 'Q4'],
    series: [
        [800000, 1200000, 1400000, 1300000],
        [200000, 400000, 500000, 300000],
        [100000, 200000, 400000, 600000]
    ]
}, {
    stackBars: true,
    axisY: {
        labelInterpolationFnc: function(value) {
            return (value / 1000) + 'k';
        }
    }
}).on('draw', function(data) {
    if(data.type === 'bar') {
        data.element.attr({
            style: 'stroke-width: 30px'
        });
    }
});
</script>
@endsection