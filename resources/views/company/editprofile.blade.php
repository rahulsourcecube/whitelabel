@extends('company.layouts.master')
@section('title', 'Edit Profile')
@section('main-content')
    <!-- Content Wrapper START -->
    <div class="main-content">
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">Edit Profile</span>
                </nav>
            </div>
        </div>
        <div class="container">
            <div class="tab-content m-t-15">
                @include('company.includes.message')
                <div class="tab-pane fade show active" id="tab-account">
                    <form action="{{ route('company.update_profile', $editprofiledetail->id) }}" method="post" enctype="multipart/form-data" id="profile-update">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Basic Infomation</h4>
                            </div>
                            <div class="card-body">
                                <div class="media align-items-center">
                                    <div class="avatar avatar-image  m-h-10 m-r-15" style="height: 80px; width: 80px">
                                        @if (isset($editprofiledetail) && $editprofiledetail->profile_image == '')
                                            <img src="{{ asset('assets/images/default-company.jpg') }}" class="imagePreviews">
                                        @else
                                            <img src="{{ asset('uploads/user-profile/' . $editprofiledetail->profile_image) }}" class="imagePreviews">
                                        @endif
                                    </div>
                                    <!-- <button class="btn btn-tone btn-primary" onclick="getimage()">Upload</button> -->
                                    <div class="m-l-20 m-r-20">
                                        <h5 class="m-b-5 font-size-18">
                                            {{ isset($editprofiledetail->first_name) ? $editprofiledetail->first_name : '' }}
                                            {{ isset($editprofiledetail->last_name) ? $editprofiledetail->last_name : '' }}</h5>
                                    </div>
                                    <!-- <button class="btn btn-primary">Upload</button> -->
                                    <div class="image">
                                        <input type="file" name="profile_image" id="profile_image">
                                    </div>
                                </div>
                                <hr class="m-v-25">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-semibold" for="firstname">First Name:</label>
                                        <input type="text" class="form-control" name="first_name" id="firstname" placeholder="First Name"
                                            value="{{ isset($editprofiledetail->first_name) ? $editprofiledetail->first_name : '' }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-semibold" for="last_name">Last Name:</label>
                                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name"
                                            value="{{ isset($editprofiledetail->last_name) ? $editprofiledetail->last_name : '' }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-semibold" for="email">Email:</label>
                                        <input type="email" class="form-control" name="email" id="email" placeholder="email"
                                            value="{{ isset($editprofiledetail->email) ? $editprofiledetail->email : '' }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-semibold" for="phoneNumber">Phone Number:</label>
                                        <input type="number" class="form-control" name="contact_number" id="phoneNumber" placeholder="Phone Number"
                                            value="{{ isset($editprofiledetail->contact_number) ? $editprofiledetail->contact_number : '' }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-semibold" for="country">Country:</label>
                                        <select name="country" id="country" class="form-control">

                                            @if ($country_data)
                                                @foreach ($country_data as $country)
                                                    <option value="{{ $country->id }}" {{ $editprofiledetail->country_id == $country->id ? 'selected' : '' }}>
                                                        {{ $country->name }}</option>
                                                @endforeach

                                            @endif
                                        </select>

                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-semibold" for="state">State:</label>
                                        <select name="state" id="state" class="form-control">
                                            @if ($state_data)
                                                @foreach ($state_data as $state)
                                                    <option value="{{ $state->id }}" {{ $editprofiledetail->state_id == $state->id ? 'selected' : '' }}>
                                                        {{ $state->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>

                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-semibold" for="city">City:</label>
                                        <select name="city" id="city" class="form-control">
                                            @if ($state_data)
                                                @foreach ($city_data as $city)
                                                    <option value="{{ $city->id }}" {{ $editprofiledetail->city_id == $city->id ? 'selected' : '' }}>
                                                        {{ $city->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>

                                    </div>
                                </div>

                                <button type="submit" class="btn btn-tone btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                    <form action="{{ route('company.update_password') }}" id="change_password" method="post">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Change Password</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label class="font-weight-semibold" for="newPassword">New Password:</label>
                                        <input type="password" class="form-control" name="newPassword" id="newPassword" placeholder="New Password">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="font-weight-semibold" for="confirmPassword">Confirm Password:</label>
                                        <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <button type="submit" class="btn btn-primary m-t-30">Change</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Content Wrapper END -->
@endsection
@section('js')
    <script>
        $(function() {
            $('#imagePreviews').on('click', function() {
                $('profile_image').trigger('click');
            });
        });
        $("#profile_image").change(function() {
            var input = this;
            var imagePreview = $(".imagePreviews")[0];
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(imagePreview).attr("src", e.target.result);
                    $(imagePreview).show();
                    $(deleteButton).show();
                };
                reader.readAsDataURL(input.files[0]);
            }
        });
        $(document).ready(function() {
            $('#profile-update').validate({
                rules: {
                    first_name: 'required',
                    last_name: 'required',
                    email: {
                        remote: {
                            url: "{{ route('user.verifyemail', $editprofiledetail->id) }}",
                            type: "GET"
                        },
                        required: true,
                        email: true
                    },
                    contact_number: {
                        number: true,
                        minlength: 10,
                        maxlength: 10,
                        required: true,
                        remote: {
                            url: "{{ route('user.verifycontact', $editprofiledetail->id) }}",
                            type: "GET"
                        }
                    },
                },
                messages: {
                    first_name: 'Please enter first name',
                    last_name: 'Please enter last name',
                    email: {
                        remote: "Email address already registred",
                        required: "Please enter email address.",
                        email: "Please enter valid email address.",
                    },
                    contact_number: {
                        remote: "Contact Number is already registered.",
                        required: "Please enter your contact number.",
                        number: "Only numbers are allowed.",
                        minlength: "Your phone number must be 10 digits.",
                        maxlength: "Your phone number must be 10 digits.",
                    }
                },
            });
            $('#change_password').validate({
                rules: {
                    oldpassword: {
                        required: true
                    },
                    newPassword: {
                        required: true,
                        minlength: 8,
                        maxlength: 50
                    },
                    confirmPassword: {
                        equalTo: '#newPassword'
                    },
                },
                messages: {
                    oldpassword: {
                        required: "Please enter old password"
                    },
                    newPassword: {
                        required: "Please enter password",
                        minlength: 'Please enter at least 6 cheracter.',
                        maxlength: 'Maximum password length 15 character.'
                    },
                    confirmPassword: {
                        equalTo: "The password you entered does not match.",
                    },
                },
            });


            $('#country').on('change', function() {
                var country_id = $(this).val();
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '/user/get_states',
                    type: 'POST',
                    data: {
                        country_id: country_id,
                        _token: CSRF_TOKEN // Include CSRF token in the request data
                    },
                    success: function(response) {
                        console.log(response);
                        var len = response.length;
                        $("#state").empty();
                        for (var i = 0; i < len; i++) {
                            var id = response[i]['id'];
                            var name = response[i]['name'];
                            $("#state").append("<option value='" + id + "'>" + name + "</option>");
                        }
                    }
                });
            });

            $('#state').on('change', function() {
                var state_id = $(this).val();
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '/user/get_city',
                    type: 'POST',
                    data: {
                        state_id: state_id,
                        _token: CSRF_TOKEN // Include CSRF token in the request data
                    },
                    success: function(response) {
                        console.log(response);
                        var len = response.length;
                        $("#city").empty();
                        for (var i = 0; i < len; i++) {
                            var id = response[i]['id'];
                            var name = response[i]['name'];
                            $("#city").append("<option value='" + id + "'>" + name + "</option>");
                        }
                    }
                });
            });
        });
    </script>
@endsection
