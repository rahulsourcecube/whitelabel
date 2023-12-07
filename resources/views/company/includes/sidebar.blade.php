<script src="{{ asset('assets/vendors/chartist/chartist.min.js') }}"></script>


<div class="side-nav">
    <div class="side-nav-inner">
        <ul class="side-nav-menu scrollable">
            <li class="nav-item dropdown open">
                <a class="dropdown-toggle" href="{{ route('company.dashboard') }}">
                    <span class="icon-holder">
                        <i class="anticon anticon-dashboard"></i>
                    </span>
                    <span class="title">Analytics</span>
                </a>
            </li>
            <li class="nav-item dropdown open">
                <a class="dropdown-toggle" href="{{ route('company.user.list') }}">
                    <span class="icon-holder">
                        <i class="anticon anticon-schedule"></i>
                    </span>
                    <span class="title">User</span>
                </a>
            </li>
            {{-- <li class="nav-item dropdown open">
                <a class="dropdown-toggle" href="{{ route('company.campaign.list') }}">
                    <span class="icon-holder">
                        <i class="anticon anticon-safety-certificate"></i>
                    </span>
                    <span class="title">Campaign</span>
                </a>
            </li> --}}
            <li class="nav-item dropdown">
                <a class="dropdown-toggle" href="javascript:void(0);">
                    <span class="icon-holder">
                        <i class="anticon anticon-safety-certificate"></i>
                    </span>
                    <span class="title">Campaigns</span>
                    <span class="arrow">
                        <i class="arrow-icon"></i>
                    </span>
                </a>
                
                <ul class="dropdown-menu">
                    <li @if(request()->segment(3)=='list' && request()->segment(2)=='campaign') class='active' @endif >
                        <a href="{{ route('company.campaign.list') }}">Tasks</a>
                    </li>
                    <li @if(request()->segment(3)=='referral') class='active' @endif >
                        <a href="{{ route('company.campaign.referral.list') }}">Referral Tasks</a>
                    </li>
                    <li @if(request()->segment(3)=='social') class='active' @endif >
                        <a href="{{ route('company.campaign.social.list') }}">Social Share</a>
                    </li>
                    <li @if(request()->segment(3)=='custom') class='active' @endif >
                        <a href="{{ route('company.campaign.custom.list') }}">Custom Tasks </a>
                    </li>
                    
                </ul>
            </li>
            {{-- <li class="nav-item dropdown open">
                <a class="dropdown-toggle" href="{{ route('company.campaign.history.list') }}">
                    <span class="icon-holder">
                        <i class="anticon anticon-safety-certificate"></i>
                    </span>
                    <span class="title">Campaign History</span>
                </a>
            </li> --}}
            <li class="nav-item dropdown open">
                <a class="dropdown-toggle" href="{{ route('company.campaign.analytics') }}">
                    <span class="icon-holder">
                        <i class="anticon anticon-build"></i>
                    </span>
                    <span class="title">Task Analytics</span>
                <a class="dropdown-toggle" href="{{ route('company.package.list') }}">
                    <span class="icon-holder">
                        <i class="anticon anticon-shopping-cart"></i>
                    </span>
                    <span class="title">Buy Package</span>
                </a>
            </li>
            <li class="nav-item dropdown open">
                <a class="dropdown-toggle" href="{{ route('company.setting.index') }}">
                    <span class="icon-holder">
                        <i class="anticon anticon-setting"></i>
                    </span>
                    <span class="title">Setting</span>
                </a>
            </li>
            <li class="nav-item dropdown open">
                <a class="dropdown-toggle" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="icon-holder">
                        <i class="anticon anticon-dashboard"></i>
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
