@extends('company.layouts.master')
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
                    <h4>Social Share Tasks</h4>
                    <p>Social Share Tasks Analytics Of The Week</p>
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
                                <div class="ct-chart" id="simple-line-social-share"></div>
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
                    <p>Custom Tasks Analytics Of The Week</p>
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
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
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
    $('.attribute').on('click',function(){
        $('#filterdata').removeAttr("disabled");
    });
    $('#date_filter').daterangepicker({
        dateLimit:{days:7},
        locale:{
            format:'YYYY/MM/DD'
        },
    });
    $('#filterdata').on('click',function(){
        var _token = $('input[name="_token"]').val();
        var date_range_filter = $('#date_filter').val();
        $.ajax({
            url:"{{ route('company.campaign.fetch_data') }}",
            method:"POST",
            data:{date_range_filter:date_range_filter, _token:_token},
            beforeSend: function() {
                $(".spinner").html('<i class="fa-solid fa-spinner"></i>');
                $('#filterdata').prop('disabled',true);
            },
            success:function(data){
                $('#simple-line-referral').remove();
                $(".spinner").html('');
                $('#filterdata').prop('disabled',false);
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
            error:function(){
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
@endsection