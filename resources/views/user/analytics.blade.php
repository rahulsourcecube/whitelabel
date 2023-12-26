@extends('user.layouts.master')
@section('title', 'Analytics')
@section('main-content')

<input type="hidden" value="{{ json_encode($monthlyReferrals) }}" class="monthlyReferrals">

<input type="hidden" value="{{ json_encode($topUserReferral) }}" class="topUserReferral">

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
                                            <input type="date" class="form-control" id="userName"
                                                placeholder="From Date">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="font-weight-semibold" for="phoneNumber">To Date:</label>
                                            <input type="date" class="form-control" id="phoneNumber">
                                        </div>
                                        <div class="col-md-12">
                                            <button class="btn btn-primary m-t-30">Filter</button>

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
                                            <input type="date" class="form-control" id="userName"
                                                placeholder="From Date">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="font-weight-semibold" for="phoneNumber">To Date:</label>
                                            <input type="date" class="form-control" id="phoneNumber">
                                        </div>
                                        <div class="col-md-12">
                                            <button class="btn btn-primary m-t-30">Filter</button>

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

        var monthlyReferrals = $('.monthlyReferrals').val();
        monthlyReferrals = JSON.parse(monthlyReferrals);
        var countArray = [];
       
        for(let i = 1; i<=12; i++){
            let obj = monthlyReferrals.find(x => x.month == i);
            if(obj && obj != ''){
                countArray.push(obj.user_count);
            }
            else{
                countArray.push(0);
            }
        }
        var monthRefChart = new Chartist.Bar('#horizontal-bar', {
            labels: ['January', 'February', 'March', 'April', 'May','Jun','July','August','September','October','November','December'],
            series: [
                countArray
            ]
        }, {
            seriesBarDistance: 10,
            reverseData: true,
            horizontalBars: true,
            axisY: {
                offset: 70
            }
        });

        var topUserReferral = $('.topUserReferral').val();
        topUserReferral = JSON.parse(topUserReferral);

        var rewardUserName = [];
        var rewardCount = [];
        
        for(let i=0; i<topUserReferral.length; i++){
            rewardUserName.push(topUserReferral[i].getuser.first_name)
            rewardCount.push(topUserReferral[i].sum)
        }
        
        var topRefUserCahrt = new Chartist.Bar('#stacked-bar', {
            labels: rewardUserName,
            series: [
                rewardCount
            ]
        }, {
            stackBars: true,
            axisY: {
                labelInterpolationFnc: function(value) {
                    return value;
                }
            }
        }).on('draw', function(data) {
            if (data.type === 'bar') {
                data.element.attr({
                    style: 'stroke-width: 30px'
                });
            }
        });
    </script>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            var chartUser = $(".chartUser").val();
            chartUser = JSON.parse(chartUser);

            var ctx = document.getElementById('horizontal-bar').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: xArray,
                    datasets: [{
                        label: {
                            display: false,
                        },
                        borderColor: '#3f87f5',
                        data: yArray,
                    }]
                },
            });
        });
    </script>
@endsection
