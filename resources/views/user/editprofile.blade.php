@extends('user.layouts.master')
@section('title', 'Edit Profile')
@section('main-content')

    <style>
        .center-button {
            text-align: center;
        }
    </style>
    <!-- Content Wrapper START -->
    <div class="main-content">
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">Edit Profile</span>
                </nav>
            </div>
        </div>
        <div class="container">
            @if (\Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show success" style="margin-top: 17px;}"
                    role="alert">
                    <i class="uil uil-times me-2"></i>
                    {!! \Session::get('success') !!}
                </div>
            @endif
            @if (\Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show error" style="margin-top: 17px;}" role="alert">
                    <i class="uil uil-times me-2"></i>
                    {!! \Session::get('error') !!}
                </div>
            @endif
            <div class="tab-content m-t-15">
                <div class="tab-pane fade show active" id="tab-account">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Basic Infomation</h4>
                        </div>
                        <div class="card-body">
                            <div class="media align-items-center">
                                <div class="avatar avatar-image  m-h-10 m-r-15" style="height: 80px; width: 80px">
                                    @if (isset($userData) &&
                                            !empty($userData->profile_image) &&
                                            file_exists('uploads/user/user-profile/' . $userData->profile_image))
                                        <img src="{{ asset('uploads/user/user-profile/' . $userData->profile_image) }}">
                                    @else
                                        <img src="{{ asset('assets/images/profile_image.jpg') }}">
                                    @endif
                                </div>
                                <div class="m-l-20 m-r-20">
                                    <h5 class="m-b-5 font-size-18">
                                        {{ isset($userData->first_name) ? $userData->first_name : '' }}
                                        {{ isset($userData->last_name) ? $userData->last_name : '' }}
                                    </h5>
                                </div>
                                <div>
                                    {{-- <button class="btn btn-tone btn-primary">Upload</button> --}}
                                </div>
                            </div>
                            <hr class="m-v-25">
                            <form id="fromData" action="{{ route('user.editProfileStore', Auth::user()->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-semibold" for="userName">First Name:</label>
                                        <input type="text" class="form-control" id="first_name" placeholder="First Name"
                                            name="first_name"
                                            value="{{ isset($userData->first_name) ? $userData->first_name : '' }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-semibold" for="userName">Last Name:</label>
                                        <input type="text" class="form-control" id="last_name" placeholder="Last Name"
                                            name="last_name"
                                            value="{{ isset($userData->last_name) ? $userData->last_name : '' }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-semibold" for="email">Email:</label>
                                        <input type="email" class="form-control" id="email" placeholder="email"
                                            name="email" value="{{ isset($userData->email) ? $userData->email : '' }}">
                                        <input type="hidden" name="hidden_email"
                                            value="{{ isset($userData->email) ? $userData->email : '' }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="font-weight-semibold" for="phoneNumber">Phone Number:</label>
                                        <input type="tel" min="0" class="form-control" id="contact_number"
                                            placeholder="Phone Number" name="contact_number"
                                            onkeypress="return /[0-9]/i.test(event.key)" maxlength="10"
                                            value="{{ isset($userData->contact_number) ? $userData->contact_number : '' }}">
                                        <input type="hidden" name="hidden_contact_number"
                                            value="{{ isset($userData->contact_number) ? $userData->contact_number : '' }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="font-weight-semibold" for="profileImage">Profile Image:</label>
                                        <input type="file" min="0" class="form-control" id="profile_image"
                                            name="profile_image">
                                        @if (isset($userData) &&
                                                !empty($userData->profile_image) &&
                                                file_exists('uploads/user/user-profile/' . $userData->profile_image))
                                            <img src="{{ asset('uploads/user/user-profile/' . $userData->profile_image) }}"
                                                style="width: 50px; height: auto;" class="mt-2">
                                        @else
                                        @endif
                                        <input type="hidden" class="hidden_profile_image"
                                            value="{{ isset($userData->profile_image) ? $userData->profile_image : '' }}">
                                        <label class="profile_image_err" style="display: none;color: red;">Please Select
                                            Only
                                            jpg,jpeg and png file.</label>
                                    </div>
                                </div>

                                <div class="center-button">
                                    <button type="submit" class="btn btn-primary">
                                        <span>Update</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Social Accounts Link</h4>
                        </div>
                        <div class="card-body">
                            <form id="socialAccountsLink" action="{{ route('user.socialAccount') }}" method="POST">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-semibold" for="Facebook">Facebook:</label>
                                        <input type="text" class="form-control" id="facebook_link"
                                            placeholder="Facebook"
                                            value="{{ isset(Auth::user()->facebook_link) ? Auth::user()->facebook_link : '' }}"
                                            name="facebook_link">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-semibold" for="Instagram">Instagram:</label>
                                        <input type="text" class="form-control" id="instagram_link"
                                            placeholder="Instagram"
                                            value="{{ isset(Auth::user()->instagram_link) ? Auth::user()->instagram_link : '' }}"
                                            name="instagram_link">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-semibold" for="Tweeter">Tweeter:</label>
                                        <input type="text" class="form-control" id="twitter_link"
                                            placeholder="Tweeter"
                                            value="{{ isset(Auth::user()->twitter_link) ? Auth::user()->twitter_link : '' }}"
                                            name="twitter_link">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-semibold" for="YouTube">YouTube:</label>
                                        <input type="text" class="form-control" id="youtube_link"
                                            placeholder="YouTube"
                                            value="{{ isset(Auth::user()->youtube_link) ? Auth::user()->youtube_link : '' }}"
                                            name="youtube_link">
                                    </div>
                                </div>
                                <div class="center-button">
                                    <button type="submit" class="btn btn-primary">
                                        <span>Submit</span>
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Bank Details</h4>
                        </div>
                        <div class="card-body">
                            <form id="bankDetails" action="{{ route('user.bankDetail') }}" method="POST">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-semibold" for="bank_name">Bank Name:</label>
                                        <input type="text" class="form-control" id="bank_name"
                                            placeholder="Bank Name"
                                            value="{{ isset(Auth::user()->bank_name) ? Auth::user()->bank_name : '' }}"
                                            name="bank_name">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-semibold" for="ac_holder">Account Holder:</label>
                                        <input type="text" class="form-control" id="ac_holder"
                                            placeholder="Account Holder"
                                            value="{{ isset(Auth::user()->ac_holder) ? Auth::user()->ac_holder : '' }}"
                                            name="ac_holder">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-semibold" for="ifsc_code">IFSC Code:</label>
                                        <input type="text" class="form-control" id="ifsc_code"
                                            placeholder="IFSC Code"
                                            value="{{ isset(Auth::user()->ifsc_code) ? Auth::user()->ifsc_code : '' }}"
                                            name="ifsc_code" onkeypress="return /[0-9,a-z,A-Z]/i.test(event.key)">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-semibold" for="ac_no">Account Number:</label>
                                        <input type="text" class="form-control" id="ac_no"
                                            placeholder="Acount Number"
                                            value="{{ isset(Auth::user()->ac_no) ? Auth::user()->ac_no : '' }}"
                                            name="ac_no" onkeypress="return /[0-9]/i.test(event.key)" maxlength="11">
                                    </div>
                                </div>
                                <div class="center-button">
                                    <button type="submit" class="btn btn-primary">
                                        <span>Submit</span>
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Change Password</h4>
                        </div>
                        <div class="card-body">
                            <form id="changePassword" action="{{ route('user.changePasswordStore') }}" method="POST">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label class="font-weight-semibold" for="oldPassword">Old Password:</label>
                                        <input type="password" class="form-control" id="oldPassword"
                                            placeholder="Old Password" name="current_password">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="font-weight-semibold" for="newPassword">New Password:</label>
                                        <input type="password" class="form-control" id="password"
                                            placeholder="New Password" name="password">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="font-weight-semibold" for="confirmPassword">Confirm
                                            Password:</label>
                                        <input type="password" class="form-control" id="confirmPassword"
                                            placeholder="Confirm Password" name="password_confirmation">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <button type="submit" class="btn btn-primary m-t-30">Change</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content Wrapper END -->
@endsection

@section('js')
    <script>
        $(document).ready(function() {

            jQuery.validator.addMethod("email", function(value, element) {
                return this.optional(element) ||
                    /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i
                    .test(value);
            }, "Please enter a valid email id");
            $(document).ready(function() {
                jQuery.validator.addMethod("emailValidation", function(value, element) {
                    return this.optional(element) ||
                        /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i
                        .test(value);
                }), "Please enter a valid email id"
            });

            $(document).on("change", "#profile_image", function() {
                myfile = $(this).val();
                var ext = myfile.split('.').pop();
                if (ext == "png" || ext == "jpg" || ext ==
                    "jpeg") {} else {
                    $('#profile_image').val('');
                    $(".profile_image_err").css("display", "block");
                    setTimeout(function() {
                        $(".profile_image_err").css("display", "none");
                    }, 3000)
                }
            })

            $("#fromData").validate({
                rules: {
                    first_name: {
                        required: true,
                    },
                    last_name: {
                        required: true,
                    },
                    email: {
                        required: true,
                    },
                    contact_number: {
                        required: true,
                    },
                    profile_image: {
                        required: function(element) {
                            if ($(".hidden_profile_image").val() != '') {
                                return false;
                            } else {
                                return true;
                            }
                        }
                    },
                },
                messages: {
                    first_name: {
                        required: "Please enter first name",
                    },
                    last_name: {
                        required: "Please enter last name",
                    },
                    email: {
                        required: "Please enter email",
                    },
                    contact_number: {
                        required: "Please enter phone number",
                    },
                    profile_image: {
                        required: function(element) {
                            if ($(".hidden_profile_image").val() != '') {
                                return false;
                            } else {
                                return "please upload profile image";
                            }
                        }
                    },
                },
            });

            $("#changePassword").validate({
                rules: {
                    current_password: {
                        required: true,
                    },
                    password: {
                        required: true,
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password",
                    },
                },
                messages: {
                    current_password: {
                        required: "Please enter old password",
                    },
                    password: {
                        required: "Please enter password",
                    },
                    password_confirmation: {
                        required: "Please enter confirmation password",
                        equalTo: "Password and confirm password dose not match",
                    },
                },
            });

            $("#socialAccountsLink").validate({
                rules: {
                    facebook_link: {
                        required: true,
                        url: true,
                    },
                    instagram_link: {
                        required: true,
                        url: true,
                    },
                    twitter_link: {
                        required: true,
                        url: true,
                    },
                    youtube_link: {
                        required: true,
                        url: true,
                    },
                },
                messages: {
                    facebook_link: {
                        required: "Please enter facebook link",
                        url: "Please enter a valid URL",
                    },
                    instagram_link: {
                        required: "Please enter instagram link",
                        url: "Please enter a valid URL",
                    },
                    twitter_link: {
                        required: "Please enter twitter link",
                        url: "Please enter a valid URL",
                    },
                    youtube_link: {
                        required: "Please enter youtube link",
                        url: "Please enter a valid URL",
                    },
                },
            });

            $("#bankDetails").validate({
                rules: {
                    bank_name: {
                        required: true,
                    },
                    ac_holder: {
                        required: true,
                    },
                    ifsc_code: {
                        required: true,
                    },
                    ac_no: {
                        required: true,
                        minlength: 8,
                    },
                },
                messages: {
                    bank_name: {
                        required: "Please enter bank name",
                    },
                    ac_holder: {
                        required: "Please enter account holder",
                    },
                    ifsc_code: {
                        required: "Please enter IFSC code",
                    },
                    ac_no: {
                        required: "Please enter account number",
                    },
                },
            });

            setTimeout(function() {
                $(".alert").remove();
            }, 5000); // 5 secs

        });
    </script>
@endsection
