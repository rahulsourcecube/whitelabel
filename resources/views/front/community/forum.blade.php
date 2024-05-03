@extends('front.layouts.master')
@section('title', 'community')
@section('main-content')


    <div id="search_results">
        <!-- Display search results here -->
    </div>

    <div class="d-flex flex-column justify-content-between w-100">

        <div class="container h-100 mt-4">
            <div class="row">
                <div class="col-lg-11 mx-auto">

                    <div class="row">
                        @if (!empty($discussions) && count($discussions) > 0)

                            @foreach ($discussions as $discussion)
                                <div class="card card-default mb-2">
                                    <div class="card-header">

                                        <span class="">{{ $discussion->users->FullName }},
                                            <b>{{ $discussion->created_at->diffForhumans() }}</b></span>

                                        <div class="text-right top">

                                            <a href="  {{ route('community.show', ['id' => $discussion->id]) }}" class="btn btn-info">View</a>
                                        </div>
                                    </div>


                                    <div class="card-body">
                                        <h5 class="">{{ $discussion->title }}</h5>
                                        <hr>
                                        <p class="">{{ Str::limit($discussion->content, 200) }}</p>
                                    </div>


                                    <div class="card-footer">
                                        {{-- @if (!empty($discussion) && $discussion->replies->count()  1) --}}
                                            <p> {{-- {{ $discussion->replies->count() }} --}} Repy</p>
                                        {{-- @else --}}
                                            {{-- <p>{{ $discussion->replies->count() }} Repies</p>
                                        @endif --}}



                                    </div>

                                </div>
                            @endforeach
                            <div class="col-sm-12 h-15 w-50 pagination justify-content-center">
                                {{-- {{ $task_data->appends(Request::all())->links() }} --}}
                            </div>
                        @else
                            @include('front.error.error')
                            {{-- <div class="col-md-12">
                                <h1 class="m-100 text-center">No Data Found!</h1>
                            </div> --}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
