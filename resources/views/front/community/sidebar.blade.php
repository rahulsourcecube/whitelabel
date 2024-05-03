@php
    $getChannel = App\Helpers\Helper::getChannels();

@endphp

<div class="mail-nav" id="mail-nav">
    <div class="p-h-50 m-t-25">
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
        {{-- <h6 class="center">Question</h6> --}}
        <ul class="menu nav flex-column">
            <li class="nav-item">
                <h6 class="nav-link d-inline-block">Question</h6>
            </li>
            <li class="nav-item">
                <a href="{{ route('community') }}" class="nav-link @if (request()->segment(1) == 'community' && request()->segment(2) == '') active @endif">
                    <i class=""></i>
                    <span>All</span>
                </a>
            </li>
            @if (!empty(Auth::check()))
                <li class="nav-item">
                    <a href="{{ route('community', 'my') }}"
                        class="nav-link @if (request()->segment(2) == 'my') active @endif">
                        <i class=""></i>
                        <span>My Question</span>
                    </a>
                </li>
            @endif

        </ul>
        <ul class="menu nav flex-column m-t-25">

            <li class="nav-item">
                <h6 class="nav-link d-inline-block">Categories</h6>
            </li>
            @if (!empty($getChannel))
                @foreach ($getChannel as $channel)
                    <li class="nav-item">
                        <a href="{{ route('community', base64_encode($channel->id)) }}"
                            class="nav-link @if (request()->segment(2) == base64_encode($channel->id)) active @endif">
                            <div class="d-flex align-items-center m-r-10">
                                <span class="badge badge-success  m-r-10"></span>
                                <span
                                    data-id="{{ $channel ? $channel->id : '' }}">{{ $channel && $channel->title ? $channel->title : '' }}</span>
                            </div>
                        </a>
                    </li>
                @endforeach

            @endif

        </ul>
    </div>
</div>
