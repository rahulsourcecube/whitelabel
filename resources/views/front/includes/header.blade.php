<style>
    /* .front-header li a.nav-link:hover {
        color: blue !important;
    } */

    /* .front-header ul li a {
        display: block;
        color: #fff;
        width: 100px;
        line-height: 35px;
        background: #15612e;
        padding-left: 16px;
    } */

    /* .front-header ul li a:hover {
        background-color: #ea8206 !important;
    }

    .front-header ul li a.active {
        display: block;
        background-color: #ea8206 !important;
    } */
    /* .front-header {
        border-bottom: 1px solid #BBBBBB;
    } */

    .nav-link {
        font-weight: bold;
        font-size: 14px;
        text-transform: uppercase;
        text-decoration: none;
        color: #031D44;
        padding: 20px 0px;
        margin: 0px 20px;
        display: inline-block;
        position: relative;
        opacity: 0.75;
    }

    .nav-link:hover {
        opacity: 1;
    }

    .front-header ul li a.active {
        opacity: 1;

    }


    .nav-link-ltr::before {
        transition: 300ms;
        height: 5px;
        content: "";
        position: absolute;
        background-color: #e6e9ee;
    }

    .nav-link-ltr::before {
        width: 0%;
        bottom: 8px;
    }

    .nav-link-ltr:hover::before {
        width: 80%;
    }
</style>
<?php $ActivePackageData = App\Helpers\Helper::GetActivePackageData(); ?>
<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <div class="container-fluid">
        <div class="collapse navbar-collapse  front-header" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto order-0  ">
                @if (request()->getHttpHost() != config('app.domain'))
                    <li class="nav-item">
                        <a class="nav-link nav-link-ltr  @if (request()->segment(1) == 'campaign' || request()->segment(1) == '') active @endif"
                            href="{{ route('front.campaign.list') }}">Task</a>
                    </li>
                    @if (
                        !empty($ActivePackageData) &&
                            $ActivePackageData->community_status == '1' &&
                            !empty($ActivePackageData->community_status))
                        <li class="nav-item">
                            <a class="nav-link nav-link-ltr   @if (request()->segment(1) == 'community') active @endif "
                                href="{{ route('community') }}">Community</a>
                        </li>
                    @endif
                @endif
                @if (request()->getHttpHost() == config('app.domain'))
                    <li class="nav-item">
                        <a class="nav-link nav-link-ltr  @if (request()->segment(1) == 'company-profiles') active @endif  "
                            href="{{ route('front.company.profiles') }}">Company</a>
                    </li>
                @endif
            </ul>

            @if (request()->getHttpHost() != config('app.domain'))
                @if (!Auth::user())
                    <ul class="navbar-nav d-flex  order-5">
                        <li class="nav-item">
                            <a class="nav-link nav-link-ltr" href="{{ route('user.login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-ltr" href="{{ route('user.signup') }}">Sign Up</a>
                        </li>
                    </ul>
                @else
                    <ul class="navbar-nav d-flex  order-5">
                        @if (!empty(Auth::user()) && Auth::user()->user_type == '4')
                            <a class="nav-link nav-link-ltr" href="{{ route('user.dashboard') }}">Dashboard</a>
                        @else
                            <li class="nav-item">
                                <a class="nav-link nav-link-ltr" href="{{ route('company.dashboard') }}">Dashboard</a>
                            </li>
                        @endif


                    </ul>
                @endif
            @else
                <ul class="navbar-nav d-flex  order-5">
                    <li class="nav-item">
                        <a class="nav-link nav-link-ltr" href="{{ route('company.signup') }}">Sign Up</a>
                    </li>
                </ul>
            @endif
            <div class="mx-auto">
            </div>
            {{-- <form class="d-flex ms-auto order-5">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form> --}}
        </div>
    </div>
</nav>
