@php
    $getChannel = App\Helpers\Helper::getChannels();

@endphp
<style>
    .nav-item {
        height: auto !important;
        line-height: 30px !important
    }

    a.nav-link {
        padding: 0 15px !important;
        margin: 5px;
    }
</style>
<div class="mail-nav" id="mail-nav">
    <div class="p-h-50 m-t-25 m-b-25">
        <div class="p-b-15 d-md-none d-inline-block">
            <a class="text-dark font-size-18 mail-close-nav" href="javascript:void(0);">
                <i class="anticon anticon-menu-fold"></i>
            </a>
        </div>
        <a class="btn btn-primary w-100 mail-open-compose" href="{{ route('community.questions.create') }}">
            Add Question
        </a>
    </div>
    <div class="p-v-15">

        <ul class="menu nav flex-column">
            <li class="nav-item  border-bottom">
                <a href="javascript:void(0);" class="nav-link ">
                    <div class="d-flex align-items-center m-r-10">
                        <h4 class="mb-0" data-id="">Question</h4>
                    </div>
                </a>
            </li>

            <li class="nav-item border-bottom">
                <a href="{{ route('community') }}" class="nav-link @if (request()->segment(1) == 'community' && request()->segment(2) == '') active @endif">
                    <i class=""></i>
                    <span>All</span>
                </a>
            </li>
            @if (!empty(Auth::check()))
                <li class="nav-item border-bottom">
                    <a href="{{ route('community', 'my') }}"
                        class="nav-link @if (request()->segment(2) == 'my') active @endif">
                        <i class=""></i>
                        <span>My Question</span>
                    </a>
                </li>
            @endif

        </ul>
        <ul class="menu nav flex-column m-t-15">

            <li class="nav-item  border-bottom">
                <a href="javascript:void(0);" class="nav-link ">
                    <div class="d-flex align-items-center m-r-10">
                        <h4 class="mb-0" data-id="">Categories</h4>
                    </div>
                </a>
            </li>
            @if (!empty($getChannel))
                @foreach ($getChannel as $channel)
                    <li class="nav-item border-bottom">
                        <a href="{{ route('community', base64_encode($channel->id)) }}"
                            class="nav-link @if (request()->segment(2) == base64_encode($channel->id)) active @endif">
                            <div class="d-flex align-items-center m-r-10">
                                <span class="mb-0"
                                    data-id="{{ $channel ? $channel->id : '' }}">{{ $channel && $channel->title ? $channel->title : '' }}</span>
                            </div>
                        </a>
                    </li>
                @endforeach
            @else
                <li class="nav-item">
                    <h6 class="nav-link text-danger">Categories not found!!</h6>
                </li>
            @endif

        </ul>
    </div>
</div>
