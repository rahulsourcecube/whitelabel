<div class="header">
    <div class="logo logo-dark">
        <a href="{{ route('user.dashboard') }}">
            <img src="@if (!empty($siteSetting) && isset($siteSetting->logo) && file_exists(asset('uploads/setting/' . $siteSetting->logo))) {{ asset('uploads/setting/' . $siteSetting->logo) }} @else{{ asset('assets/images/logo/logo.png') }} @endif "
                alt="Logo">
            <img class="logo-fold" src="@if(!empty($siteSetting) &&  !empty($siteSetting->logo) && file_exists(base_path("uploads/setting/".$siteSetting->logo))) {{asset("uploads/setting/".$siteSetting->favicon)}} @else{{ asset('assets/images/logo/logo.png') }}  @endif" alt="Logo">
        </a>
    </div>
    <div class="logo logo-white">
        <a href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('assets/images/logo/logo-white.png') }}" alt="Logo">
            <img class="logo-fold" src="{{ asset('assets/images/logo/logo-fold-white.png') }}" alt="Logo">
        </a>
    </div>
    <div class="nav-wrap">
        <ul class="nav-left">
        </ul>
        <ul class="nav-right">
            <li class="dropdown dropdown-animated scale-left">
                <div class="pointer" data-toggle="dropdown">
                    <div class="avatar avatar-image  m-h-10 m-r-15">
                        @if (isset(Auth::user()->profile_image) &&
                                !empty(Auth::user()->profile_image) &&
                                file_exists('uploads/user/user-profile/' . Auth::user()->profile_image))
                            <img src="{{ asset('uploads/user/user-profile/' . Auth::user()->profile_image) }}">
                        @else
                        <img src="{{asset('assets/images/profile_image.jpg')}}">
                        @endif
                    </div>
                </div>
                <div class="p-b-15 p-t-20 dropdown-menu pop-profile">
                    <div class="p-h-20 p-b-15 m-b-10 border-bottom">
                        <div class="d-flex m-r-50">
                            <div class="avatar avatar-lg avatar-image">
                                @if (isset(Auth::user()->profile_image) &&
                                        !empty(Auth::user()->profile_image) &&
                                        file_exists('uploads/user/user-profile/' . Auth::user()->profile_image))
                                    <img src="{{ asset('uploads/user/user-profile/' . Auth::user()->profile_image) }}">
                                @else
                                <img src="{{asset('assets/images/profile_image.jpg')}}">
                                @endif
                            </div>
                            <div class="m-l-10">
                                <p class="m-b-0 text-dark font-weight-semibold">
                                    {{ isset(Auth::user()->first_name) ? Auth::user()->first_name : '' }}</p>
                                <p class="m-b-0 opacity-07">
                                    {{ isset(Auth::user()->last_name) ? Auth::user()->last_name : '' }}</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('user.profile') }}" class="dropdown-item d-block p-h-15 p-v-10">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <i class="anticon opacity-04 font-size-16 anticon-user"></i>
                                <span class="m-l-10">Profile</span>
                            </div>
                            <i class="anticon font-size-10 anticon-right"></i>
                        </div>
                    </a>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <a href="{{ route('user.edit_profile') }}" class="dropdown-item d-block p-h-15 p-v-10">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <i class="anticon opacity-04 font-size-16 anticon-edit"></i>
                                <span class="m-l-10">Edit Profile</span>
                            </div>
                            <i class="anticon font-size-10 anticon-right"></i>
                        </div>
                    </a>
                    <a href="{{ route('user.logout') }}" class="dropdown-item d-block p-h-15 p-v-10">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <i class="anticon opacity-04 font-size-16 anticon-logout"></i>
                                <span class="m-l-10">Logout</span>
                            </div>
                            <i class="anticon font-size-10 anticon-right"></i>
                        </div>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</div>
