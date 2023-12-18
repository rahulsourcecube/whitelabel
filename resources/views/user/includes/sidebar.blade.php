<script src="{{ asset('assets/vendors/chartist/chartist.min.js') }}"></script>


<div class="side-nav">
    <div class="side-nav-inner">
        <ul class="side-nav-menu scrollable">
            <li class="nav-item dropdown open">
                <a class="dropdown-toggle" href="{{ route('user.dashboard') }}">
                    <span class="icon-holder">
                        <i class="anticon anticon-dashboard"></i>
                    </span>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item dropdown open">
                <a class="dropdown-toggle" href="{{ route('user.campaign.list') }}">
                    <span class="icon-holder">
                        <i class="anticon anticon-safety-certificate"></i>
                    </span>
                    <span class="title">Campaign</span>
                </a>
            </li>
            <li class="nav-item dropdown open">
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
                    <span class="title">Rewards And Incentives</span>
                    <span class="arrow">
                        <i class="arrow-icon"></i>
                    </span>
                </a>
                
                <ul class="dropdown-menu">
                    <li @if(request()->segment(2)=='my') class='active' @endif >
                        <a href="{{ route('user.my.reward') }}">My Available  Reward</a>
                    </li>
                    <li @if(request()->segment(2)=='progress') class='active' @endif >
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
                    
                    <li @if(request()->segment(2)=='profile') class='active' @endif >
                        <a href="{{ route('user.profile') }}">Profile</a>
                    </li>
                    <li @if(request()->segment(2)=='edit_profile') class='active' @endif >
                        <a href="{{ route('user.edit_profile') }}">Edit Profile</a>
                    </li>
                    <li @if(request()->segment(2)=='changePassword') class='active' @endif >
                        <a href="{{ route('user.changePassword') }}">Change Password</a>
                    </li>
                   
                    
                </ul>
                
            </li>
            <li class="nav-item dropdown open">
                <a class="dropdown-toggle" href="{{ route('user.notification') }}">
                    <span class="icon-holder">
                        <i class="anticon anticon-bell"></i>
                    </span>
                    <span class="title">Notification</span>
                </a>
            </li>
             <li class="nav-item dropdown open">
                <a class="dropdown-toggle"  href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
