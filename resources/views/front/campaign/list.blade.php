@extends('front.layouts.master')
@section('title', 'Task')
@section('main-content')


    <div id="search_results">
        <!-- Display search results here -->
    </div>

    <div class="d-flex flex-column justify-content-between w-100">

        <div class="container h-100 mt-4">
            <div class="row">


                <div class="col-lg-11 mx-auto">
                    <form action="{{ route('front.campaign.list') }}" method="get">
                        <div class="row from-group mb-5">
                            <div class="col-md-3 from-group">
                                <select name="country" id="country" class="form-control form-select">
                                    <option value="0">Select Country</option>
                                    @if (isset($countrys) && !empty($countrys))
                                        @foreach ($countrys as $country)
                                            <option value="{{ $country->id }}"
                                                @if (isset($selectedCountry) && $selectedCountry == $country->id) selected @endif>
                                                {{ $country->name }} </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-3">
                                <select name="state" id="state" class="form-control form-select">
                                    <option value="0">Select State</option>
                                    @if (!empty($states))
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}"
                                                @if (isset($selectedState) && $state->id == $selectedState) selected @endif>
                                                {{ $state->name }} </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="city" id="city" class="form-control form-select">
                                    <option value="0">Select City</option>
                                    @if (isset($citys) && !empty($citys))
                                        @foreach ($citys as $city)
                                            <option value="{{ $city->id }}"
                                                @if (!empty($selectedCity) && $selectedCity == $city->id) selected @endif>
                                                {{ $city->name }} </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-1 mr-3">
                                <button id="search_dtt" type="submit" value=""
                                    class="btn btn-primary">Search</button>
                            </div>
                            <div class="col-md-1">
                                <a href="{{ route('front.campaign.list') }}" value="" class="btn btn-danger">Clear</a>
                            </div>
                        </div>

                        @csrf
                    </form>
                    <div class="row">
                        @if (!empty($task_data) && count($task_data) > 0)

                            @foreach ($task_data as $data)
                                <div class="col-md-4 mb-5">
                                    <div class="card h-100 ">

                                        @if (isset($data) && $data->image != '' && file_exists(base_path() . '/uploads/company/campaign/' . $data->image))
                                            <img class="card-img-top"
                                                src="{{ asset('uploads/company/campaign/' . $data->image) }}"
                                                class="w-200 img-responsive" style="min-height:200px" width='200'>
                                        @else
                                            <img src="{{ asset('assets/images/others/No_image_available.png') }}"
                                                class="w-200 img-responsive" height='200'>
                                        @endif

                                        <div class="card-body">
                                            <p class="text-gray font-weight-semibold">
                                                @if ($data->priority == 1)
                                                    <span class="badge badge-pill badge-danger"> High </span>
                                                @elseif($data->priority == 2)
                                                    <span class="badge badge-pill badge-info"> Medium </span>
                                                @elseif($data->priority == 3)
                                                    <span class="badge badge-pill badge-success"> Low </span>
                                                @else
                                                    {{-- Handle other cases if needed --}}
                                                @endif
                                                Reward:
                                                <b>{{ $data->text_reward ? $data->text_reward : (isset($data->reward) ? \App\Helpers\Helper::getcurrency() . $data->reward : '0') }}</b>


                                            </p>
                                            <h4 class="m-t-10">{{ $data->title }}</h4>
                                            <p class="m-b-20">{{ strip_tags(html_entity_decode($data->description)) }}</p>

                                        </div>
                                        <div class=" mb-5 d-flex justify-content-center ">

                                            <p class="m-b-0 text-dark font-weight-semibold font-size-15"></p>
                                            <a class="btn btn-hover btn-primary mr-2 "
                                                href="{{ route('front.campaign.Join', base64_encode($data->id)) }}">Join
                                                Now</a>
                                            <a class="btn-primary btn  btn-hover gap-2"
                                                href="{{ url('front/campaign/detail', ['id' => $data->id]) }}">Read
                                                More</a>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                            <div class="col-sm-12 h-15 w-50 pagination justify-content-center">
                                {{ $task_data->appends(Request::all())->links() }}
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
