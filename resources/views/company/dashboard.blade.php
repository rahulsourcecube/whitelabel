@extends('company.layouts.master')
@section('title', 'Dashboard')
@section('main-content')
    <!-- Page Container START -->
    <!-- Content Wrapper START -->
    @php $currency = App\Helpers\Helper::getcurrency() @endphp
    <div class="main-content">
        @include('admin.includes.message')
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="avatar avatar-icon avatar-lg avatar-blue">
                                <i class="anticon anticon-dollar"></i>
                            </div>
                            <div class="m-l-15">
                                <h2 class="m-b-0">{{ isset($total_user) ? $total_user : '' }}</h2>
                                {{-- <p class="m-b-0 text-muted">Total Point's Earned</p> --}}
                                <p class="m-b-0 text-muted">Total Number Users</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="avatar avatar-icon avatar-lg avatar-blue">
                                <i class="anticon anticon-dollar"></i>
                            </div>
                            <div class="m-l-15">
                                <h2 class="m-b-0">{{ isset($total_campaign) ? $total_campaign : '' }}</h2>
                                <p class="m-b-0 text-muted">Number of Active Tasks</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="avatar avatar-icon avatar-lg avatar-gold">
                                <i class="anticon anticon-profile"></i>
                            </div>
                            <div class="m-l-15">
                                <h2 class="m-b-0">{{ isset($total_campaignReq) ? $total_campaignReq : '' }}</h2>
                                <p class="m-b-0 text-muted">Campaign Approval Request</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (Auth::user()->hasPermissionTo('task-list'))
            <div class="row">
                <div class="col-md-12 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="m-b-0">Referral Tasks</h5>
                                <div>
                                    @can('task-list')
                                        <a href="{{ route('company.campaign.list', \App\Helpers\Helper::taskType(\App\Models\CampaignModel::TYPE['REFERRAL'])) }}"
                                            class="btn btn-sm btn-info">View All</a>
                                    @endcan
                                    @can('task-create')
                                        <a href="{{ route('company.campaign.create', \App\Helpers\Helper::taskType(\App\Models\CampaignModel::TYPE['REFERRAL'])) }}"
                                            class="btn btn-sm btn-success">Add New</a>
                                    @endcan
                                </div>
                            </div>
                            <div class="m-t-30">
                                @if (!empty($referral_tasks) && count($referral_tasks) > 0)
                                    @foreach ($referral_tasks as $referral_task)
                                        <div class="m-b-25">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="media align-items-center">
                                                    <div class="m-l-15">
                                                        <h6 class="m-b-0">
                                                            <a class="text-dark"
                                                                href="javascript:void(0);">{{ isset($referral_task->title) ? $referral_task->title : '' }}</a>
                                                        </h6>
                                                        <p class="text-muted m-b-0">
                                                            ${{ isset($referral_task->reward) ? $referral_task->reward : '' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="m-b-0">Social Share Tasks</h5>
                                <div>
                                    <div>
                                        @can('task-list')
                                            <a href="{{ route('company.campaign.list', \App\Helpers\Helper::taskType(\App\Models\CampaignModel::TYPE['SOCIAL'])) }}"
                                                class="btn btn-sm btn-info">View All</a>
                                        @endcan
                                        @can('task-create')
                                            <a href="{{ route('company.campaign.create', \App\Helpers\Helper::taskType(\App\Models\CampaignModel::TYPE['SOCIAL'])) }}"
                                                class="btn btn-sm btn-success">Add New</a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            <div class="m-t-30">
                                @if (!empty($social_share_tasks) && count($social_share_tasks) > 0)
                                    @foreach ($social_share_tasks as $social_share_task)
                                        <div class="m-b-25">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="media align-items-center">
                                                    <div class="m-l-15">
                                                        <h6 class="m-b-0">
                                                            <a class="text-dark"
                                                                href="javascript:void(0);">{{ isset($social_share_task->title) ? $social_share_task->title : '' }}</a>
                                                        </h6>
                                                        <p class="text-muted m-b-0">
                                                            ${{ isset($social_share_task->reward) ? $social_share_task->reward : '' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="m-b-0">Custom Tasks</h5>
                                <div>
                                    @can('task-list')
                                        <a href="{{ route('company.campaign.list', \App\Helpers\Helper::taskType(\App\Models\CampaignModel::TYPE['CUSTOM'])) }}"
                                            class="btn btn-sm btn-info">View All</a>
                                    @endcan
                                    @can('task-create')
                                        <a href="{{ route('company.campaign.create', \App\Helpers\Helper::taskType(\App\Models\CampaignModel::TYPE['CUSTOM'])) }}"
                                            class="btn btn-sm btn-success">Add New</a>
                                    @endcan
                                </div>
                            </div>
                            <div class="m-t-30">
                                @if (!empty($custom_tasks) && count($custom_tasks) > 0)
                                    @foreach ($custom_tasks as $custom_task)
                                        <div class="m-b-25">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="media align-items-center">
                                                    <div class="m-l-15">
                                                        <h6 class="m-b-0">
                                                            <a class="text-dark"
                                                                href="javascript:void(0);">{{ isset($custom_task->title) ? $custom_task->title : '' }}</a>
                                                        </h6>
                                                        <p class="text-muted m-b-0">
                                                            ${{ isset($custom_task->reward) ? $custom_task->reward : '' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Total Revenue</h5>
                            <div>
                                <div class="btn-group">
                                    <button class="btn btn-default active">
                                        <span>Month</span>
                                    </button>
                                    <button class="btn btn-default">
                                        <span>Year</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="m-t-50" style="height: 330px">
                            <canvas class="chart" id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="m-b-0">Users</h5>
                        <div class="text-center"
                            style="height: 200px;margin-top: 5px !important;margin-bottom: 115px !important;">
                            {{-- <canvas class="chart" id="customers-chart"></canvas> --}}
                            @if ($total_user != 0)
                                <div class="ct-chart" id="donut-chart"></div>
                            @endif
                        </div>
                        <div class="row border-top p-t-25">
                            <div class="col-4">
                                <div class="d-flex justify-content-center">
                                    <div class="media align-items-center">
                                        <span class="badge badge-success badge-dot m-r-10"></span>
                                        <div class="m-l-5">
                                            <input type="hidden" id="new_user"
                                                value="{{ isset($new_user) ? $new_user : '0' }}">
                                            <h4 class="m-b-0">{{ isset($new_user) ? $new_user : '' }}</h4>
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
                                                value="{{ isset($old_user) ? $old_user : '0' }}">
                                            <h4 class="m-b-0">{{ isset($old_user) ? $old_user : '' }}</h4>
                                            <p class="m-b-0 muted">Returning</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-flex justify-content-center">
                                    <div class="media align-items-center">
                                        <span class="badge badge-warning badge-dot m-r-10"></span>
                                        <div class="m-l-5">
                                            <h4 class="m-b-0">{{ isset($total_user) ? $total_user : '' }}</h4>
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
        var chartdata = {!! json_encode($user_reward_and_days) !!};
        chartdata = JSON.parse(chartdata);
        var currency = "{{ $currency }}";
    </script>
    <script>
        var new_user = $("#new_user").val();
        var old_user = $("#old_user").val();
        new Chartist.Pie('#donut-chart', {
            series: [old_user, new_user]
        }, {
            donut: true,
            donutWidth: 60,
            donutSolid: true,
            startAngle: 270,
            showLabel: true
        });
    </script>
@endsection
