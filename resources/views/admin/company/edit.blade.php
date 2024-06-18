@extends('admin.layouts.master')

@section('title', 'Setting')
@section('main-content')

    <div class="main-content">
        @include('admin.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <a href="{{ route('admin.company.list') }}" class="breadcrumb-item">Company</a>
                    <span class="breadcrumb-item active">Edit</span>
                </nav>
            </div>
        </div>

        <!-- Page Container START -->
        <div class="card">
            <div class="card-body">
                <div class="m-t-25">
                    <div class="d-flex">
                        <ul class="nav nav-tabs flex-column" id="myTabVertical" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab-vertical" data-toggle="tab" href="#home-vertical"
                                    role="tab" aria-controls="home-vertical" aria-selected="true">Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab-vertical" data-toggle="tab" href="#contact-vertical"
                                    role="tab" aria-controls="contact-vertical" aria-selected="false">Setting</a>
                            </li>
                        </ul>

                        <div class="tab-content m-l-15" id="myTabContentVertical">
                            <div class="tab-pane fade show active" id="home-vertical" role="tabpanel"
                                aria-labelledby="home-tab-vertical">
                                <div class="tab-pane fade show active" id="tab-account">
                                    <form action="{{ route('admin.company.update_profile', $editprofiledetail->id) }}"
                                        method="post" enctype="multipart/form-data" id="profile-update">
                                        @csrf
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Profile</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="media align-items-center">
                                                    <div class="avatar avatar-image  m-h-10 m-r-15"
                                                        style="height: 80px; width: 80px">
                                                        @if (isset($editprofiledetail) && $editprofiledetail->profile_image == '')
                                                            <img src="{{ asset('assets/images/default-company.jpg') }}"
                                                                class="imagePreviews">
                                                        @else
                                                            <img src="{{ base_path() . ('/uploads/user-profile/' . $editprofiledetail->profile_image) }}"
                                                                class="imagePreviews">
                                                        @endif
                                                    </div>
                                                    <!-- <button class="btn btn-tone btn-primary" onclick="getimage()">Upload</button> -->
                                                    <div class="m-l-20 m-r-20">
                                                        <h5 class="m-b-5 font-size-18">
                                                            {{ isset($editprofiledetail->first_name) ? $editprofiledetail->first_name : '' }}
                                                            {{ isset($editprofiledetail->last_name) ? $editprofiledetail->last_name : '' }}
                                                        </h5>
                                                    </div>
                                                    <!-- <button class="btn btn-primary">Upload</button> -->
                                                    <div class="image">
                                                        <input type="file" name="profile_image" id="profile_image">
                                                    </div>
                                                </div>
                                                <hr class="m-v-25">
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label class="font-weight-semibold" for="firstname">First
                                                            Name:</label>
                                                        <input type="text" class="form-control" name="first_name"
                                                            id="firstname" placeholder="First Name"
                                                            value="{{ isset($editprofiledetail->first_name) ? $editprofiledetail->first_name : '' }}">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="font-weight-semibold" for="last_name">Last
                                                            Name:</label>
                                                        <input type="text" class="form-control" name="last_name"
                                                            id="last_name" placeholder="Last Name"
                                                            value="{{ isset($editprofiledetail->last_name) ? $editprofiledetail->last_name : '' }}">
                                                    </div>
                                                    <div class="form-group col-md-5">
                                                        <label class="font-weight-semibold" for="email">Email:</label>
                                                        <input type="email" class="form-control" name=""
                                                            id="email" placeholder="email"
                                                            value="{{ isset($editprofiledetail->email) ? $editprofiledetail->email : '' }}"
                                                            readonly>
                                                    </div>
                                                    <div class="form-group col-md-5">
                                                        <label class="font-weight-semibold" for="phoneNumber">Phone
                                                            Number:</label>
                                                        <input type="number" class="form-control" name="contact_number"
                                                            id="phoneNumber" placeholder="Phone Number"
                                                            value="{{ isset($editprofiledetail->contact_number) ? $editprofiledetail->contact_number : '' }}">
                                                    </div>
                                                    <div class="col-md-2 pl-4">
                                                        <label for="expiry_date">Status</label>
                                                        <div class="form-group align-items-center">
                                                            <div class="switch m-r-10">
                                                                <input type="checkbox" id="switch-1" name="status"
                                                                    value="true"
                                                                    @if (isset($editprofiledetail->status) && $editprofiledetail->status == 1) checked="" @endif>
                                                                <label for="switch-1"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-tone btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                    <form action="{{ route('admin.company.update_password', $editprofiledetail->id) }}"
                                        id="change_password" method="post">
                                        @csrf
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Change Password</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="form-group col-md-3">
                                                        <label class="font-weight-semibold" for="newPassword">New
                                                            Password:</label>
                                                        <input type="password" class="form-control" name="newPassword"
                                                            id="newPassword" placeholder="New Password">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label class="font-weight-semibold" for="confirmPassword">Confirm
                                                            Password:</label>
                                                        <input type="password" class="form-control"
                                                            name="confirmPassword" id="confirmPassword"
                                                            placeholder="Confirm Password">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <button type="submit"
                                                            class="btn btn-primary m-t-30">Change</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="contact-vertical" role="tabpanel">
                                <div class="card">
                                    <div class="card-body">
                                        <h4>Setting</h4>
                                        <div class="">
                                            <form id="settings" method="POST"
                                                action="{{ route('admin.company.store', $editprofiledetail->id) }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-row">
                                                    <div class="form-group col-md-4">
                                                        <label for="cname">Company Name<span
                                                                class="error">*</span></label>
                                                        <input type="text" class="form-control mb-2" name="title"
                                                            id="cname" placeholder="Company Name"
                                                            value="{{ !empty($setting) && $setting->title ? $setting->title : $user_company->company_name }}"
                                                            required>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="cdomainname">Company Domain</label>
                                                        <input type="text" class="form-control mb-2" name=""
                                                            id=""
                                                            value="{{ !empty($user_company->subdomain) ? $user_company->subdomain : '' }}"
                                                            readonly>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="cemail">Contact Email</label>
                                                        <input type="email" class="form-control mb-2" name="email"
                                                            id="cemail" placeholder="Company Email"
                                                            value="{{ !empty($setting) ? $setting->email : '' }}">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="contact">Contact Number</label>
                                                        <input type="text" min="0" maxlength="10"
                                                            minlength="10" class="form-control mb-2"
                                                            name="contact_number" id="contact"
                                                            placeholder="Contact Number"
                                                            value="{{ !empty($setting) && $setting->contact_number ? $setting->contact_number : $user_company->contact_number }}">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="flink">Facebook Link</label>
                                                        <input type="url" class="form-control mb-2"
                                                            name="facebook_link" id="flink"
                                                            placeholder="Facebook Link"
                                                            value="{{ !empty($setting) ? $setting->facebook_link : '' }}">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="t_link">Twitter Link</label>
                                                        <input type="url" class="form-control mb-2"
                                                            name="twitter_link" id="t_link" placeholder="Twitter Link"
                                                            value="{{ !empty($setting) ? $setting->twitter_link : '' }}">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="l_link">Linkedin Link</label>
                                                        <input type="url" class="form-control mb-2"
                                                            name="linkedin_link" id="l_link"
                                                            placeholder="Linkedin Link"
                                                            value="{{ !empty($setting) ? $setting->linkedin_link : '' }}">
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="descriptions">Description <span
                                                                class="error">*</span></label>
                                                        <textarea type="text" class="form-control" id="descriptions" name="description" placeholder="description"> {{ !empty($setting->description) ? $setting->description : '' }} </textarea>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="leader_image">Logo</label>
                                                        <input type="file" class="form-control" name="logo"
                                                            id="logofiles" accept=".png, .jpg, .jpeg">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-3  mt-2">
                                                                <img id="logoimagePreviews"
                                                                    src="{{ !empty($setting) && $setting->logo ? asset('uploads/setting/' . $setting->logo) : '' }}"
                                                                    alt="Logo Preview" class="img-reposive w-100">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="leader_image">Favicon</label>
                                                        <input type="file" class="form-control" name="favicon"
                                                            id="files" accept=".png, .jpg, .jpeg">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-1 mt-2">
                                                                <img id="imagePreviews"
                                                                    src="{{ !empty($setting) && $setting->favicon ? asset('uploads/setting/' . $setting->favicon) : '' }}"
                                                                    alt="Favicon Icon Preview" class="img-reposive w-100">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary" type="submit"
                                                    id="btnSubmit">Submit</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
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
                        minlength: 'Please enter at least 8 cheracter.',
                        maxlength: 'Maximum password length 15 character.'
                    },
                    confirmPassword: {
                        equalTo: "The password you entered does not match.",
                    },
                },
            });
            window.onload = () => {
                CKEDITOR.replace("description");
            };
        });
    </script>
    <script>
        $('#settings').validate({
            rules: {
                title: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: "Please enter company name"
                }
            }
        });
        $(document).ready(function() {
            if (!$("#imagePreviews").attr("src")) {
                $("#imagePreviews, #logodeleteImageButtons").hide();
            }
            if (!$("#logoimagePreviews").attr("src")) {
                $("#logoimagePreviews, #deleteImageButtons").hide();
            }
            // Function to preview image
            $("#files").change(function() {
                var input = this;
                var imagePreview = $("#imagePreviews")[0];
                var deleteButton = $("#deleteImageButtons");
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
            // Function to delete image
            $("#deleteImageButtons").click(function() {
                var confirmation = confirm("Are you sure you want to delete the image?");
                if (confirmation) {
                    $("#files").val(""); // Clear the file input
                    $("#imagePreviews").attr("src", "").hide(); // Clear the image preview and hide it
                    $(this).hide(); // Hide the delete button
                }
            });
            // Function to preview image
            $("#logofiles").change(function() {
                var input = this;
                var imagePreview = $("#logoimagePreviews")[0];
                var deleteButton = $("#logodeleteImageButtons");
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
            // Function to delete image
            $("#logodeleteImageButtons").click(function() {
                var confirmation = confirm("Are you sure you want to delete the image?");
                if (confirmation) {
                    $("#logofiles").val(""); // Clear the file input
                    $("#logoimagePreviews").attr("src", "").hide(); // Clear the image preview and hide it
                    $(this).hide(); // Hide the delete button
                }
            });
        });
    </script>
@endsection
