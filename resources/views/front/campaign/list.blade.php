@extends('front.layouts.master')
@section('title', 'Task')
@section('main-content')
    <form action="{{ route('front.campaign.list') }}" method="get">

        <div class="row">
            <div class="col-md-3">
                <select name="country" id="country" class="form-control form-select">
                    <option value="0">Select Country</option>
                    @if (isset($country) && !empty($country))
                        @foreach ($country as $data)
                            <option value="{{ $data->id }}" @if (isset($selectedCountry) && $selectedCountry == $data->id) selected @endif> {{ $data->name }} </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-3">
                <select name="state" id="state" class="form-control form-select">
                    <option value="0">Select State</option>
                    @if (!empty($state) && isset($_GET['state']) && !empty($_GET['state']))

                        @foreach ($state as $data)
                            <option value="{{ $data->id }}" @if (isset($_GET['state']) && $data->id == $_GET['state']) selected @endif> {{ $data->name }} </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-3">
                <select name="city" id="city" class="form-control form-select">
                    <option value="0">Select City</option>
                    {{-- @if (isset($city) && !empty($city))
                        @foreach ($city as $data)
                            <option value="{{ $data->id }}" @if (isset($selectedCity) && $selectedCity == $data->id) selected @endif> {{ $data->name }} </option>
                        @endforeach
                    @endif --}}
                </select>
            </div>
            <div class="col-md-3">
                <button id="search_dtt" type="submit" value="" class="btn btn-primary">Search</button>
            </div>
        </div>

        @csrf
    </form>

    <div id="search_results">
        <!-- Display search results here -->
    </div>

    <div class="d-flex flex-column justify-content-between w-100">
        <div class="container h-100 mt-4">
            <div class="row">
                <div class="col-lg-11 mx-auto">
                    <div class="row">
                        @if ($task_data->isNotEmpty())
                            @foreach ($task_data as $data)
                                <div class="col-md-4">
                                    <div class="card">
                                        @if (isset($data) && $data->image != '' && file_exists(base_path() . '/uploads/company/campaign/' . $data->image))
                                            <img class="card-img-top" src="{{ asset('uploads/company/campaign/' . $data->image) }}" class="w-200 img-responsive"
                                                height='200' width='200'>
                                        @else
                                            <img src="{{ asset('assets/images/others/No_image_available.png') }}" class="w-200 img-responsive" height='200'>
                                        @endif

                                        <div class="card-body">
                                            <h4 class="m-t-10">{{ $data->title }}</h4>
                                            <p class="m-b-20">{{ strip_tags(html_entity_decode($data->description)) }}</p>

                                            <div class="d-flex align-items-center justify-content-between">
                                                <p class="m-b-0 text-dark font-weight-semibold font-size-15"></p>
                                                <a class="btn btn-hover btn-primary" href="{{ url('user/login') }}">JOIN</a>
                                                <a class="btn-primary btn  btn-hover" href="{{ url('front/campaign/detail', ['id' => $data->id]) }}">Read
                                                    More</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                            <div class="col-sm-12 h-15 w-50 pagination justify-content-center">
                                {{ $task_data->appends(Request::all())->links() }}
                            </div>
                        @else
                            <div class="col-md-12">
                                <h1 class="m-100 text-center">No Data Found!</h1>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
