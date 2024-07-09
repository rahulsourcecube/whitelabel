{{-- <script src="{{ asset('assets/vendors/chartist/chartist.min.js') }}"></script> --}}

<?php
$user = Auth::user();
$ActivePackageData = App\Helpers\Helper::GetActivePackageData();
// $notificationCount = Notification::where('user_id', $user->id)->where('is_read','0')->get();
use App\Models\Notification;
$notificationCount = Notification::where('user_id', $user->id)
    ->where('type', '1')
    ->where('is_read', '0')
    ->get();
?>

<div class="side-nav">
    <div class="side-nav-inner">
        <ul class="side-nav-menu scrollable">
            <li class="nav-item dropdown open @if (request()->segment(2) == 'dashboard') active @endif ">
                <a class="dropdown-toggle" href="{{ route('user.dashboard') }}">
                    <span class="icon-holder">
                        <i class="anticon anticon-dashboard"></i>
                    </span>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item dropdown open @if (request()->segment(2) == 'campaign' && request()->segment(3) == '') active @endif ">
                <a class="dropdown-toggle" href="{{ route('user.campaign.list') }}">
                    <span class="icon-holder">
                        <i class="anticon anticon-safety-certificate"></i>
                    </span>
                    <span class="title">Campaign</span>
                </a>
            </li>
            <li class="nav-item dropdown open @if (request()->segment(2) == 'analytics') active @endif">
                <a class="dropdown-toggle" href="{{ route('user.analytics') }}">
                    <span class="icon-holder">
                        <i class="anticon anticon-build"></i>
                    </span>
                    <span class="title">Analytics Dashboard</span>
                </a>
            </li>


            <li class="nav-item dropdown">
                <a class="dropdown-toggle" href="javascript:void(0);">
                    <span class="icon-holder">
                        <i class="anticon anticon-safety-certificate"></i>
                    </span>
                    <span class="title">My Rewards</span>
                    <span class="arrow">
                        <i class="arrow-icon"></i>
                    </span>
                </a>

                <ul class="dropdown-menu">
                    <li @if (request()->segment(2) == 'my') class='active' @endif>
                        <a href="{{ route('user.my.reward') }}">My Available Reward</a>
                    </li>
                    <li @if (request()->segment(2) == 'progress') class='active' @endif>
                        <a href="{{ route('user.progress.reward') }}">Progress Reward</a>
                    </li>



                </ul>

            </li>
            <li class="nav-item dropdown">
                <a class="dropdown-toggle" href="javascript:void(0);">
                    <span class="icon-holder">
                        <i class="anticon anticon-setting"></i>
                    </span>
                    <span class="title">Profile Setting</span>
                    <span class="arrow">
                        <i class="arrow-icon"></i>
                    </span>
                </a>

                <ul class="dropdown-menu">

                    <li @if (request()->segment(2) == 'profile') class='active' @endif>
                        <a href="{{ route('user.profile') }}">Profile</a>
                    </li>
                    <li @if (request()->segment(2) == 'edit_profile') class='active' @endif>
                        <a href="{{ route('user.edit_profile') }}">Edit Profile</a>
                    </li>
                    <li @if (request()->segment(3) == 'notification') class='active' @endif>
                        <a href="{{ route('user.notification.setting') }}">Notification</a>
                    </li>
                </ul>

            </li>
            <li class="nav-item dropdown open @if (request()->segment(2) == 'notification') active @endif">
                <a class="dropdown-toggle" href="{{ route('user.notification') }}">
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
            @if ($ActivePackageData->community_status == '1' && !empty($ActivePackageData->community_status) &&  $siteSetting->community_status=='1')
                <li class="nav-item dropdown open @if (request()->segment(2) == 'community' && request()->segment(3) == '') active @endif ">
                    <a class="dropdown-toggle" href="{{ route('community') }}">
                        <span class="icon-holder">
                            <i class="anticon anticon-team"></i>
                        </span>
                        <span class="title">Community</span>
                    </a>
                </li>
            @endif
            @if ($ActivePackageData->survey_status == '1' && !empty($ActivePackageData->no_of_survey))
                <li class="nav-item dropdown open @if (request()->segment(2) == 'survey' && request()->segment(3) == '') active @endif ">
                    <a class="dropdown-toggle" href="{{ route('user.survey') }}">
                        <span class="icon-holder">
                            <i class="anticon anticon-safety-certificate"></i>
                        </span>
                        <span class="title">Survey</span>
                    </a>
                </li>
            @endif
            <li class="nav-item dropdown open">
                <a class="dropdown-toggle" href="{{ route('user.logout') }}">
                    <span class="icon-holder">
                        <i class="anticon opacity-04 font-size-16 anticon-logout"></i>
                    </span>
                    <span class="title">Logout</span>
                </a>
            </li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </ul>
    </div>
</div>
