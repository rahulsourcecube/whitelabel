@extends('front.layouts.master')
@section('title', 'community')
@section('main-content')


    <!-- Content Wrapper START -->


    <div class="mail-wrapper  p-h-20 p-v-20 bg full-height">
        @include('front.community.sidebar')


        <div class="container">

            @if (!empty($questions) && count($questions) > 0)

                @foreach ($questions as $question)
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                {{-- <div class="col-md-4">
                                    <img class="img-fluid" src="assets/images/others/img-2.jpg" alt="">
                                </div> --}}
                                <div class="col-md-12">
                                    <div class="text-right top">

                                    </div>
                                    <h4 class="m-b-10">{{ $question->title }}</h4>

                                    <div class="d-flex align-items-center m-t-5 m-b-15">
                                        <div class="avatar avatar-image avatar-sm ">
                                            @if (isset($question) &&
                                                    !empty($question->users->profile_image) &&
                                                    file_exists(base_path() . '/uploads/user/user-profile/' . $question->users->profile_image))
                                                <img
                                                    src="{{ asset('uploads/user/user-profile/' . $question->users->profile_image) }}">
                                            @else
                                                <img src="{{ asset('assets/images/profile_image.jpg') }}">
                                            @endif
                                        </div>
                                        <div class="m-l-10">
                                            <span
                                                class="text-gray font-weight-semibold">{{ !empty($question) && !empty($question->user_id) && !empty($question->users->FullName) ? $question->users->FullName : '' }}</span>
                                            <span class="m-h-5 text-gray">|</span>
                                            <span class="text-gray">{{ $question->created_at->diffForhumans() }}</span>
                                        </div>
                                    </div>
                                    <p class="m-b-20">
                                        <?php ?>
                                        {!! htmlspecialchars_decode(strip_tags(Str::limit($question->content, 200))) !!}</p>
                                    <div class="text-left">
                                        <a href="  {{ route('community.show', ['id' => base64_encode($question->id)]) }}"
                                            class="btn btn-hover font-weight-semibold">
                                            <span>Read More</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-sm-12 h-15 w-50 pagination justify-content-center">
                    {{ $questions->appends(Request::all())->links() }}
                </div>
            @else
                @include('front.error.error')

            @endif
        </div>
    </div>

@endsection
