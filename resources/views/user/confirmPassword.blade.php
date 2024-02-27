<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Confirm Password || {{ !empty($siteSetting) && !empty($siteSetting->title) ? Ucfirst($siteSetting->title) : env('APP_NAME') }} </title>
    <!-- Favicon -->
    <link rel="shortcut icon"
    href="@if (!empty($siteSetting) && isset($siteSetting->favicon) && file_exists(base_path('uploads/setting/' . $siteSetting->favicon))) {{env('ASSET_URL').'/uploads/setting/' . $siteSetting->favicon }} @else{{ asset('assets/images/logo/logo.png') }} @endif">
    <style>
        .error {
            color: red;
        }
    </style>

    <!-- page css -->

    <!-- Core css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">

</head>

<body>
    <div class="app">
        <div class="container-fluid p-h-0 p-v-20 bg full-height d-flex"
            style="background-image: url({{ asset('assets/images/others/login-3.png') }})">
            <div class="d-flex flex-column justify-content-between w-100">
                <div class="container d-flex h-100">
                    <div class="row align-items-center w-100">
                        <div class="col-md-7 col-lg-5 m-h-auto">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between m-b-30">
                                        <a href="{{!empty($siteSetting) && !empty($siteSetting->logo_link) ? $siteSetting->logo_link : "" }} "  {{!empty($siteSetting) && !empty($siteSetting->logo_link) ? 'target="_blank"' : "" }}>
                                        <img  style="width: 130px ; hight:50px" src="@if (
                                            !empty($siteSetting) &&
                                                !empty($siteSetting->logo) &&
                                                file_exists(base_path('uploads/setting/' . $siteSetting->logo))) {{env('ASSET_URL').'/uploads/setting/'. $siteSetting->logo }} @else {{asset('assets/images/logo/logo.png')}} @endif"
                                            alt="Logo">
                                        </a>
                                        <h2 class="m-b-0">Confirm Password</h2>
                                    </div>
                                    <form id="fromData" action="{{ route('user.reset-password') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="token" value="{{ $token }}">

                                        <div class="form-group">
                                            <div class="input-affix">
                                                <input id="email" type="hidden"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    name="email" value="{{ isset($user->email) ? $user->email : '' }}"
                                                    placeholder="Email" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-semibold" for="userName">Password:</label>
                                            <div class="input-affix">
                                                <i class="prefix-icon anticon anticon-user"></i>
                                                <input type="password" class="form-control" id="password"
                                                    placeholder="Password:" name="password">
                                            </div>
                                            <label id="password-error" class="error" for="password"></label>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-semibold" for="password">Confirm Password:</label>
                                            <div class="input-affix m-b-10">
                                                <i class="prefix-icon anticon anticon-lock"></i>
                                                <input type="password" class="form-control" id="password-confirmation"
                                                    placeholder="Confirm Password" name="password_confirmation">
                                            </div>
                                            <label id="password-confirmation-error" class="error"
                                                for="password-confirmation"></label>
                                        </div>
                                        <div class="form-group">
                                            <div class="d-flex align-items-center justify-content-between">


                                                <button type="submit" class="btn btn-primary">Change</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"
        integrity="sha512-WMEKGZ7L5LWgaPeJtw9MBM4i5w5OSBlSjTjCtSnvFJGSVD26gE5+Td12qN5pvWXhuWaWcVwF++F7aqu9cvqP0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        jQuery(document).ready(function($) {
            $("#fromData").validate({
                rules: {
                    email: {
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
                    email: {
                        required: "Please enter email",
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
        });
    </script>


    <!-- Core Vendors JS -->
    <script src="{{ asset('assets/js/vendors.min.js') }}"></script>

    <!-- page js -->

    <!-- Core JS -->
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

</body>

</html>
