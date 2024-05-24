<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <div class="container-fluid">
        <div class="collapse navbar-collapse " id="navbarSupportedContent">
            <ul class="navbar-nav me-auto order-0  ">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('front.campaign.list') }}">Task</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('community') }}">Community</a>
                </li>
            </ul>
            @if (!Auth::user())
                <ul class="navbar-nav d-flex  order-5">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.login') }}">login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.signup') }}">Sign Up</a>
                    </li>
                </ul>
            @else
                <ul class="navbar-nav d-flex  order-5">
                    @if (!empty(Auth::user()) && Auth::user()->user_type == '4')
                        <a class="nav-link" href="{{ route('user.dashboard') }}">Dashboard</a>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('company.dashboard') }}">Dashboard</a>
                        </li>
                    @endif


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
