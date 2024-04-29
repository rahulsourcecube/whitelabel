@extends('front.layouts.master')
@section('title', 'Survey')
@section('main-content')
    <div class="d-flex flex-column justify-content-between w-100">
        <div class="container h-100">
            <div class="row">
                <div class="col-lg-11 mx-auto">
                    <div class="row">
                        @if (!empty($task_data))
                            @foreach ($task_data as $data)
                                {{-- {{ $data }} --}}
                                <div class="col-md-4">
                                    <div class="card">
                                        @if (isset($data) && $data->image != '' && file_exists(base_path() . '/uploads/company/campaign/' . $data->image))
                                            <img class="card-img-top"
                                                src="{{ asset('uploads/company/campaign/' . $data->image) }}"
                                                class="w-200   img-responsive" height='200'>
                                        @else
                                            <img src="{{ asset('assets/images/others/No_image_available.png') }}"
                                                class="w-200  img-responsive" height='200'>
                                        @endif


                                        <div class="card-body">
                                            <h4 class="m-t-10">{{ $data->title }}</h4>
                                            <p class="m-b-20">
                                                {{ strip_tags(html_entity_decode($data->description)) }}</p>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <p class="m-b-0 text-dark font-weight-semibold font-size-15">
                                                    {{ $data->created_at }}</p>
                                                <a class="btn-primary btn btn-sm btn-hover" href="">
                                                    Read More
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                            <div class="col-sm-12 h-15 w-50 pagination justify-content-center ">
                                {{ $task_data->appends(Request::all())->links() }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
