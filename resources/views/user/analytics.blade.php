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
                        <h4>My User Referral </h4>
                        <p>My Referral user joined over time</p>

                        <div class="m-t-25">
                            <div class="row">
                                <div class="col-md-3">
                                    @if (isset(request()->from_date))
                                        <input type="hidden" name="from_date" value="{{ request()->from_date }}">
                                    @elseif(isset(request()->to_date))
                                        <input type="hidden" name="to_date" value="{{ request()->to_date }}">
                                    @endif

                                    <div class="form-group col-md-12">
                                        <label class="font-weight-semibold" for="from_date">From Date:</label>
                                        <input type="date" class="form-control" id="from_date" placeholder="From Date"
                                            name="from_date"
                                            value="{{ isset(request()->from_date) ? request()->from_date : '' }}">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="font-weight-semibold" for="to_date">To Date:</label>
                                        <input type="date" class="form-control" id="to_date" placeholder="To Date"
                                            name="to_date"
                                            value="{{ isset(request()->to_date) ? request()->to_date : '' }}">
                                    </div>
                                    <div class="col-md-12">
                                        <button class="btn btn-primary m-t-30 filter" type="button"
                                            id="referralActivityButtone">Filter</button>

                                    </div>
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

                                    @if (isset(request()->top_from_date))
                                        <input type="hidden" name="top_from_date" value="{{ request()->top_from_date }}">
                                    @elseif(isset(request()->top_to_date))
                                        <input type="hidden" name="top_to_date" value="{{ request()->top_to_date }}">
                                    @endif

                                    <div class="form-group col-md-12">
                                        <label class="font-weight-semibold" for="from_date">From Date:</label>
                                        <input type="date" class="form-control" id="top_from_date"
                                            placeholder="From Date"
                                            value="{{ isset(request()->top_from_date) ? request()->top_from_date : '' }}"
                                            name="top_from_date">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="font-weight-semibold" for="to_date">To Date:</label>
                                        <input type="date" class="form-control" id="top_to_date"
                                            value="{{ isset(request()->top_to_date) ? request()->top_to_date : '' }}"
                                            name="top_to_date">
                                    </div>
                                    <div class="col-md-12">
                                        <button class="btn btn-primary m-t-30 filter" type="button"
                                            id="topReferrerlsButtone">Filter</button>

                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="ct-chart" id="stacked-bar"></div>
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
    <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
    <script>
        function chartAjax() {        


            var monthlyReferrals = $('.monthlyReferrals').val();
            monthlyReferrals = JSON.parse(monthlyReferrals);
            var countArray = [];

            for (let i = 1; i <= 12; i++) {
                let obj = monthlyReferrals.find(x => x.month == i);
                if (obj && obj != '') {
                    countArray.push(obj.user_count);
                } else {
                    countArray.push(0);
                }
            }
            var monthRefChart = new Chartist.Line('#horizontal-bar', {
                labels: ['January', 'February', 'March', 'April', 'May', 'Jun', 'July', 'August', 'September',
                    'October', 'November', 'December'
                ],
                series: [
                    countArray
                ]
            }, {
                seriesBarDistance: 10,
                reverseData: true,
                horizontalBars: true,
                axisY: {
                    onlyInteger: true,
                    // offset: 1
                }
            });

            var topUserReferral = $('.topUserReferral').val();
            topUserReferral = JSON.parse(topUserReferral);

            var rewardUserName = [];
            var rewardCount = [];

            for (let i = 0; i < topUserReferral.length; i++) {
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
        }
    </script>


    <script>
        $(document).ready(function() {
            chartAjax();
        });

        $('.filter').on('click', function() {
            $.ajax({
                url: '{{ route('user.analytics') }}',
                type: 'GET',
                data: {
                    from_date: $("#from_date").val(),
                    to_date: $("#to_date").val(),
                    top_from_date: $("#top_from_date").val(),
                    top_to_date: $("#top_to_date").val(),
                },
                success: function(response) {
                    $('.monthlyReferrals').val(JSON.stringify(response.monthlyReferrals));
                    $('.topUserReferral').val(JSON.stringify(response.topUserReferral));
                    chartAjax();
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
    </script>
@endsection
