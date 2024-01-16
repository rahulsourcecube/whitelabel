<div class="side-nav">
    <div class="side-nav-inner">
        <ul class="side-nav-menu scrollable">
            <li class="nav-item dropdown open @if(request()->segment(2) == 'dashboard') active @endif ">
                <a class="dropdown-toggle" href="{{ route('admin.dashboard') }}">
                    <span class="icon-holder">
                        <i class="anticon anticon-dashboard"></i>
                    </span>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item dropdown open @if(request()->segment(2) == 'package') active @endif ">
                <a class="dropdown-toggle" href="{{ route('admin.package.list') }}">
                    <span class="icon-holder">
                        <i class="anticon anticon-schedule"></i>
                    </span>
                    <span class="title">Package</span>
                </a>
            </li>
            <li class="nav-item dropdown open @if(request()->segment(2) == 'company') active @endif">
                <a class="dropdown-toggle" href="{{ route('admin.company.list') }}">
                    <span class="icon-holder">
                        <i class="anticon anticon-safety-certificate"></i>
                    </span>
                    <span class="title">Company</span>
                </a>
            </li>
            <li class="nav-item dropdown open @if(request()->segment(2) == 'setting') active @endif">
                <a class="dropdown-toggle" href="{{ route('admin.setting.index') }}">
                    <span class="icon-holder">
                        <i class="anticon anticon-setting"></i>
                    </span>
                    <span class="title">Setting</span>
                </a>
            </li>
            <li class="nav-item dropdown open">
                <a class="dropdown-toggle" href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="icon-holder">
                        <i class="anticon opacity-04 font-size-16 anticon-logout"></i>
                    </span>
                    <span class="title">Logout</span>
                </a>
            </li>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </ul>
    </div>
</div>
