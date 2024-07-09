@extends('front.layouts.master')
@section('title', 'Company Profiles')

@php
    $siteSetting = App\Helpers\Helper::getSiteSetting();
@endphp
@section('main-content')


    <div class="mail-wrapper  p-h-20 p-v-20 bg full-height">



        <div class="container">
            <form action="{{ route('front.company.profiles') }}" method="get">
                <div class="row mb-5">
                    <div class="col-md-3 mb-3">
                        <div class="input-group rounded">
                            <input type="search" class="form-control rounded" name="company_name"
                                placeholder="Search Company " value="{{ $_GET['company_name'] ?? '' }}" aria-label="Search"
                                aria-describedby="search-addon" />

                        </div>
                    </div>

                    <div class="col-md-2 mb-3">
                        <select name="country" id="country" class="form-control form-select">
                            <option value="0">Select Country</option>
                            @if (isset($countrys) && !empty($countrys))
                                @foreach ($countrys as $country)
                                    <option value="{{ $country->id }}" @if (isset($selectedCountry) && $selectedCountry == $country->id) selected @endif>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <select name="state" id="state" class="form-control form-select">
                            <option value="0">Select State</option>
                            @if (!empty($states))
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}" @if (isset($selectedState) && $state->id == $selectedState) selected @endif>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <select name="city" id="city" class="form-control form-select">
                            <option value="0">Select City</option>
                            @if (isset($citys) && !empty($citys))
                                @foreach ($citys as $city)
                                    <option value="{{ $city->id }}" @if (!empty($selectedCity) && $selectedCity == $city->id) selected @endif>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-1 mr-3">
                        <button id="search_dtt" type="submit" class="btn btn-primary">Search</button>
                    </div>

                    @if (isset($_GET) && !empty($_GET))
                        <div class="col-md-1">
                            <a href="{{ route('front.company.profiles') }}" class="btn btn-danger">Clear</a>
                        </div>
                    @endif
                </div>

                @csrf
            </form>


            {{-- @include('front.includes.message') --}}

            @if (!empty($companyProfiles) && count($companyProfiles) > 0)

                <div class="row" id="card-view">
                    @foreach ($companyProfiles as $companyProfile)
                        <div class="col-md-4">
                            <div class="card" style="height: 350px;">
                                <div class="card-body">
                                    <div class="m-t-20 text-center">
                                        <div class="avatar avatar-image" style="height: 100px; width: 100px;">
                                            <img style="width: 130px ; hight:50px"
                                                src="@if (
                                                    !empty($companyProfile->company->setting) &&
                                                        !empty($companyProfile->company->setting->logo) &&
                                                        file_exists(base_path('uploads/setting/' . $companyProfile->company->setting->logo))) {{ env('ASSET_URL') . '/uploads/setting/' . $companyProfile->company->setting->logo }} @else {{ asset('assets/images/logo/logo.png') }} @endif"
                                                alt="Logo">
                                        </div>

                                        <h4 class="m-t-30">{{ $companyProfile->company->company_name ?? '' }}</h4>
                                        {{-- <p>{{ $companyProfile->company->contact_email ?? '' }}</p> --}}
                                    </div>
                                    @php   $task =  $webUrl. $companyProfile->company->subdomain . '.'.config('app.domain').'/campaign'; @endphp
                                    @php   $community =   $webUrl.$companyProfile->company->subdomain . '.'.config('app.domain').'/community'; @endphp
                                    <div class="text-center m-t-30">
                                        <a href="{{ $task ?? '' }}" class="btn btn-primary btn-tone">
                                            <i class="anticon anticon-eye"></i>
                                            <span class="m-l-5">Task</span>
                                        </a>

                                        @php $ActivePackageData = App\Helpers\Helper::GetActivePackageDataCompany($companyProfile->id)  @endphp

                                        @if (
                                            !empty($ActivePackageData) &&
                                                $ActivePackageData->community_status == '1' &&
                                                !empty($ActivePackageData->community_status) && $companyProfile->company->setting->community_status=='1')
                                            <a href="{{ $community ?? '' }}" class="btn btn-primary btn-tone">
                                                <i class="anticon anticon-team"></i>
                                                <span class="m-l-5">Community</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-sm-12 h-15 w-50 pagination justify-content-center">
                    {{ $companyProfiles->appends(Request::all())->links() }}
                </div>
            @else
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="p-v-30 text-center">
                            <img src="{{ asset('assets/images/not-found.png') }}" class="w-25">
                            <h2 class="text-center">Not Found</h2>

                        </div>
                    </div>

                </div>
            @endif
        </div>
    </div>


@endsection
@section('js')

    <script>
        $(document).ready(function() {
            $('#searchInput').on('input', function() {
                var searchValue = $(this).val().trim();

                // Clear previous search results
                $('#searchResults').empty();

                // Perform search after a brief delay (for demonstration purposes)
                setTimeout(function() {
                    // Example: Simulate search results
                    var searchResultsDiv = $('#searchResults');
                    var newDiv = $('<div>').addClass('search-result').text('Search results for: ' +
                        searchValue);
                    searchResultsDiv.append(newDiv);
                }, 300); // Adjust delay as needed
            });
        });
    </script>

    <script>
        function sweetAlertAjax(deleteUrl) {
            // Use SweetAlert for confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If the user confirms, proceed with AJAX deletion
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: (response) => {

                            if (response.success == 'error') {
                                // Handle error case
                                Swal.fire({
                                    text: response.message,
                                    icon: "error",
                                    button: "Ok",
                                }).then(() => {
                                    // Reload the page or take appropriate action
                                    location.reload();
                                });
                            } else {

                                // Handle success case
                                Swal.fire({
                                    text: response.message,
                                    icon: "success",
                                    button: "Ok",
                                }).then(() => {
                                    // Reload the page or take appropriate action
                                    location.reload();
                                });
                            }
                        },
                        error: (xhr, status, error) => {
                            // Handle AJAX request error
                            console.error(xhr.responseText);
                            Swal.fire({
                                text: 'An error occurred while processing your request.',
                                icon: "error",
                                button: "Ok",
                            });
                        }
                    });
                }
            });
        }

        function handleClickActive(element, community_id) {
            var buttonText = $(element).text();
            var isChecked = buttonText === "Inactive";
            var message = isChecked ? 'make it Active' : 'make it Inactive';

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to ' + message + ', right?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, ' + message + '!'
            }).then((result) => {
                if (result.isConfirmed) {

                    if (buttonText === "Active") {
                        $(element).text("Inactive");
                        $(element).removeClass("btn-success").addClass(
                            "btn-danger");
                        var val = "1";
                    } else {
                        $(element).text("Active");
                        $(element).removeClass("btn-danger").addClass(
                            "btn-success");
                        var val = "0";
                    }
                    $.ajax({
                        url: "{{ route('community.status.change') }}",
                        type: 'POST',
                        data: {
                            id: community_id,
                            status: val,
                            _token: "{{ csrf_token() }}"
                        }

                    });
                }
            });
        }
    </script>

@endsection
