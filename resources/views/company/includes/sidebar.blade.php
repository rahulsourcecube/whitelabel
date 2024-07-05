<style>
    /* .side-nav .side-nav-inner .side-nav-menu li.active {
    background-color: #ff0000;
    } */
</style>

{{-- <script src="{{ asset('assets/vendors/chartist/chartist.min.js') }}"></script> --}}
@php
    $user = Auth::user();
    $ActivePackageData = App\Helpers\Helper::GetActivePackageData();
    $isActivePackage = App\Helpers\Helper::isActivePackage();
    use App\Models\Notification;

    $companyId = App\Helpers\Helper::getCompanyId();
    $notificationCount = Notification::where('company_id', $companyId)
        ->where('is_read', '0')
        ->where('type', '2')
        ->get();

@endphp
<div class="side-nav">
    <div class="side-nav-inner">
        <ul class="side-nav-menu scrollable">
            @if ($isActivePackage)
                <li class="nav-item dropdown open @if (request()->segment(2) == 'dashboard') active @endif ">
                    <a class="dropdown-toggle" href="{{ route('company.dashboard') }}">
                        <span class="icon-holder">
                            <i class="anticon anticon-dashboard"></i>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>
            @endif
            @if ($isActivePackage)
                @can('user-list')
                    <li class="nav-item dropdown open @if (request()->segment(2) == 'user') active @endif ">
                        <a class="dropdown-toggle" href="{{ route('company.user.list') }}">
                            <span class="icon-holder">
                                <i class="anticon anticon-schedule"></i>
                            </span>
                            <span class="title">User</span>
                        </a>
                    </li>
                @endcan
            @endif
            @if ($isActivePackage)
                @can('task-list')
                    <li class="nav-item dropdown">
                        <a class="dropdown-toggle" href="javascript:void(0);">
                            <span class="icon-holder">
                                <i class="anticon anticon-safety-certificate"></i>
                            </span>
                            <span class="title">Task</span>
                            <span class="arrow"><i class="arrow-icon"></i>
                            </span>
                        </a>
                        <ul class="dropdown-menu">
                            <li @if (request()->segment(2) == 'campaign' && request()->segment(3) == 'list' && request()->segment(4) == 'Referral') class='active' @endif>
                                <a
                                    href="{{ route('company.campaign.list', \App\Helpers\Helper::taskType(\App\Models\CampaignModel::TYPE['REFERRAL'])) }}">Referral
                                    Tasks</a>
                            </li>
                            <li @if (request()->segment(2) == 'campaign' &&
                                    request()->segment(3) == 'list' &&
                                    request()->segment(4) == \App\Helpers\Helper::taskType(\App\Models\CampaignModel::TYPE['SOCIAL'])) class='active' @endif>
                                <a
                                    href="{{ route('company.campaign.list', \App\Helpers\Helper::taskType(\App\Models\CampaignModel::TYPE['SOCIAL'])) }}">Social
                                    Tasks</a>
                            </li>
                            <li @if (request()->segment(2) == 'campaign' &&
                                    request()->segment(3) == 'list' &&
                                    request()->segment(4) == \App\Helpers\Helper::taskType(\App\Models\CampaignModel::TYPE['CUSTOM'])) class='active' @endif>
                                <a
                                    href="{{ route('company.campaign.list', \App\Helpers\Helper::taskType(\App\Models\CampaignModel::TYPE['CUSTOM'])) }}">Custom
                                    Tasks </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            @endif
            @if ($isActivePackage)
                @can('task-create')
                    <li class="nav-item dropdown">
                        <a class="dropdown-toggle" href="javascript:void(0);">
                            <span class="icon-holder">
                                <i class="anticon anticon-safety-certificate"></i>
                            </span>
                            <span class="title">Create New Task</span>
                            <span class="arrow">
                                <i class="arrow-icon"></i>
                            </span>
                        </a>
                        <ul class="dropdown-menu">
                            <li @if (request()->segment(2) == 'campaign' &&
                                    request()->segment(3) == 'create' &&
                                    request()->segment(4) == \App\Helpers\Helper::taskType(\App\Models\CampaignModel::TYPE['REFERRAL'])) class='active' @endif>
                                <a
                                    href="{{ route('company.campaign.create', \App\Helpers\Helper::taskType(\App\Models\CampaignModel::TYPE['REFERRAL'])) }}">Referral
                                    Task</a>
                            </li>
                            <li @if (request()->segment(2) == 'campaign' &&
                                    request()->segment(3) == 'create' &&
                                    request()->segment(4) == \App\Helpers\Helper::taskType(\App\Models\CampaignModel::TYPE['SOCIAL'])) class='active' @endif>
                                <a
                                    href="{{ route('company.campaign.create', \App\Helpers\Helper::taskType(\App\Models\CampaignModel::TYPE['SOCIAL'])) }}">Social
                                    Task</a>
                            </li>
                            <li @if (request()->segment(2) == 'campaign' &&
                                    request()->segment(3) == 'create' &&
                                    request()->segment(4) == \App\Helpers\Helper::taskType(\App\Models\CampaignModel::TYPE['CUSTOM'])) class='active' @endif>
                                <a
                                    href="{{ route('company.campaign.create', \App\Helpers\Helper::taskType(\App\Models\CampaignModel::TYPE['CUSTOM'])) }}">Custom
                                    Task </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            @endif
            <li class="nav-item dropdown open @if (request()->segment(3) == 'analytics') active @endif ">
                @if ($isActivePackage)
                    @can('task-analytics-list')
                        <a class="dropdown-toggle  " href="{{ route('company.campaign.analytics') }}  ">
                            <span class="icon-holder">
                                <i class="anticon anticon-build"></i>
                            </span>
                            <span class="title">Task Analytics</span>
                        </a>
                    @endcan
                @endif
            </li>
            <li class=" nav-item dropdown open @if (request()->segment(2) == 'package') active @endif">
                @can('package-list')
                    <a class="dropdown-toggle   " href="{{ route('company.package.list', 'Free') }}">
                        <span class="icon-holder">
                            <i class="anticon anticon-shopping-cart"></i>
                        </span>
                        <span class="title">Buy Package</span>
                    </a>
                @endcan
            </li>
            @if ($isActivePackage)
                @if (Auth::user()->hasPermissionTo('general-setting-list') ||
                        Auth::user()->hasPermissionTo('employee-management-list') ||
                        Auth::user()->hasPermissionTo('role-list') ||
                        Auth::user()->hasPermissionTo('billing-and-payment-list'))
                    <li class="nav-item dropdown">
                        <a class="dropdown-toggle" href="javascript:void(0);">
                            <span class="icon-holder">
                                <i class="anticon anticon-setting"></i>
                            </span>
                            <span class="title">Settings</span>
                            <span class="arrow">
                                <i class="arrow-icon"></i>
                            </span>
                        </a>
                        <ul class="dropdown-menu">
                            @can('general-setting-list')
                                <li @if (request()->segment(2) == 'setting') class='active' @endif>
                                    <a href="{{ route('company.setting.index') }}">General Setting</a>
                                </li>
                            @endcan
                            @can('employee-management-list')
                                <li @if (request()->segment(2) == 'employee') class='active' @endif>
                                    <a href="{{ route('company.employee.list') }}">Employee Management</a>
                                </li>
                            @endcan
                            @can('role-list')
                                <li @if (request()->segment(2) == 'role') class='active' @endif>
                                    <a href="{{ route('company.role.rolelist') }}">Role Management</a>
                                </li>
                            @endcan
                            @can('billing-and-payment-list')
                                <li @if (request()->segment(2) == 'billing') class='active' @endif>
                                    <a href="{{ route('company.billing.billing') }}">Billing and Payment</a>
                                </li>
                            @endcan
                            <li @if (request()->segment(2) == 'progression') class='active' @endif>
                                <a href="{{ route('company.progression.index') }}">Task Progression</a>
                            </li>

                            @if (!empty($ActivePackageData->mail_temp_status) && $ActivePackageData->mail_temp_status == '1')
                                <li @if (request()->segment(2) == 'mail') class='active' @endif>
                                    <a href="{{ route('company.mail.index') }}">Mail Template</a>
                                </li>
                            @endif
                            @if (!empty($ActivePackageData->sms_temp_status) && $ActivePackageData->sms_temp_status == '1')
                                <li @if (request()->segment(2) == 'sms') class='active' @endif>
                                    <a href="{{ route('company.sms.index') }}">SMS Template</a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif
                @if (!empty($ActivePackageData->no_of_survey) && $ActivePackageData->survey_status == '1')
                    <li class="nav-item dropdown">
                        <a class="dropdown-toggle" href="javascript:void(0);">
                            <span class="icon-holder">
                                <i class="anticon anticon-setting"></i>
                            </span>
                            <span class="title">Survey Setting</span>
                            <span class="arrow">
                                <i class="arrow-icon"></i>
                            </span>
                        </a>
                        <ul class="dropdown-menu">

                            <li @if (request()->segment(2) == 'survey') class='active' @endif>
                                <a href="{{ route('company.survey.form.index') }}">Survey Form</a>
                            </li>
                            {{-- <li @if (request()->segment(2) == 'setting') class='active' @endif>
                        <a href="{{ route('company.setting.index') }}">Survey list</a>
                    </li> --}}
                        </ul>
                    </li>
                @endif
                @if (!empty($ActivePackageData->community_status) && $ActivePackageData->community_status == '1' &&  $siteSetting->community_status=='1')
                    <li class="nav-item dropdown">
                        <a class="dropdown-toggle" href="javascript:void(0);">
                            <span class="icon-holder">
                                <i class="anticon anticon-setting"></i>
                            </span>
                            <span class="title">Community</span>
                            <span class="arrow">
                                <i class="arrow-icon"></i>
                            </span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ route('community') }}">Go to Community</a>
                            </li>
                            <li @if (request()->segment(2) == 'category') class='active' @endif>
                                <a href="{{ route('company.channel.index') }}">Category</a>
                            </li>
                            <li @if (request()->segment(2) == 'reply') class='active' @endif>
                                <a href="{{ route('company.reply.index') }}">Reply</a>
                            </li>
                            {{-- <li @if (request()->segment(2) == 'setting') class='active' @endif>
                        <a href="{{ route('company.setting.index') }}">Survey list</a>
                    </li> --}}
                        </ul>
                    </li>
                @endif
            @endif

            @can('notification-list')
                <li class="nav-item dropdown open @if (request()->segment(2) == 'notification') active @endif">
                    <a class="dropdown-toggle" href="{{ route('company.notification.list') }}">
                        <span class="icon-holder">
                            <i class="anticon anticon-bell"></i>
                        </span>
                        <span class="title">Notification </span>
                        @if ($notificationCount->count() != 0)
                            <span class="badge badge-pill badge-danger">
                                {{ isset($notificationCount) ? $notificationCount->count() : 0 }}
                            </span>
                        @endif
                    </a>
                </li>
            @endcan
            <li class="nav-item dropdown open">
                <a class="dropdown-toggle" href="{{ route('company.logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="icon-holder">
                        <i class="anticon opacity-04 font-size-16 anticon-logout"></i>
                    </span>
                    <span class="title">Logout</span>
                </a>
            </li>
            <form id="logout-form" action="{{ route('company.logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </ul>
    </div>
</div>
