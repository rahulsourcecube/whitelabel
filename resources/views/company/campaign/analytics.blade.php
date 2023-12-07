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

                                            <button class="btn btn-success m-t-30">Export</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-9">
                                    <div class="ct-chart" id="simple-line-referral"></div>
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
                                            <input type="date" class="form-control" id="userName"
                                                placeholder="From Date">
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
                                            <input type="date" class="form-control" id="userName"
                                                placeholder="From Date">
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
    </script>
@endsection
