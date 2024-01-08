<!DOCTYPE html>
<html lang="en">



<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sign Up</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/logo.png') }}">

    <!-- page css -->

    <!-- Core css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">

    {{-- Custome css --}}

    <style>
        .error {
            color: red;
        }
    </style>

</head>

<body>
    <div class="app">
        <div class="container-fluid p-h-0 p-v-20 bg full-height d-flex"
            style="background-image: url('{{ asset('assets/images/others/login-3.png') }}">
            <div class="d-flex flex-column justify-content-between w-100">
                <div class="container d-flex h-100">
                    <div class="row align-items-center w-100">
                        <div class="col-md-7 col-lg-5 m-h-auto">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    @include('admin.includes.message')
                                    <div class="d-flex align-items-center justify-content-between m-b-30">
                                        <img class="img-fluid" alt="" src="{{ asset('assets/images/logo/logo.png') }}">
                                        <h2 class="m-b-0">Signup</h2>
                                    </div>
                                    <form id="fomData" action="{{ route('user.store') }}" method="POST">
                                        @csrf
                                        @if (isset(request()->referral_code))
                                        <input type="hidden" name="referral_code"
                                            value="{{ request()->referral_code }}">
                                        @endif
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="font-weight-semibold" for="userName">First Name:</label>
                                                <input type="text" class="form-control" id="first_name"
                                                    placeholder="First Name" name="first_name"
                                                    value="{{old('first_name')}}" minlength="50">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="font-weight-semibold" for="userName">Last name</label>
                                                <input type="text" class="form-control" id="last_name"
                                                    placeholder="Last name" name="last_name"
                                                    value="{{old('last_name')}}" minlength="50">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label class="font-weight-semibold" for="email">Email:</label>
                                                <input type="email" class="form-control" id="email" placeholder="Email"
                                                    name="email" value="{{old('email')}}" minlength="50">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label class="font-weight-semibold" for="contact">Contact
                                                    Number:</label>
                                                <input type="text" class="form-control" id="contact"
                                                    placeholder="Contact Number" minlength="10" maxlength="10"
                                                    name="contact_number" value="{{old('contact_number')}}"
                                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="font-weight-semibold" for="password">Password:</label>
                                                <input type="password" class="form-control" id="password"
                                                    placeholder="Password" name="password" value="{{old('password')}}" minlength="50">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="font-weight-semibold" for="confirmPassword">Confirm
                                                    Password:</label>
                                                <input type="password" class="form-control" id="confirmPassword"
                                                    placeholder="Confirm Password" name="password_confirmation"
                                                    value="{{old('password_confirmation')}}" minlength="50">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="d-flex align-items-center justify-content-between p-t-15">
                                                {{-- <div class="checkbox">
                                                    <input id="checkbox" type="checkbox">
                                                </div> --}}
                                                <label for="checkbox"><span>Already have an account? <a
                                                            href="{{ route('user.login') }}">Login</a></span></label>
                                                {{-- <a href="{{ route('user.dashboard') }}"
                                                    class="btn btn-primary">Signup</a> --}}
                                                <button type="submit" class="btn btn-primary">Sign Up</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-none d-md-flex p-h-40 justify-content-between">
                    {{-- <span class="">© 2019 ThemeNate</span> --}}
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            {{-- <a class="text-dark text-link" href="#">Legal</a> --}}
                        </li>
                        <li class="list-inline-item">
                            {{-- <a class="text-dark text-link" href="#">Privacy</a> --}}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>

    <script>
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

        jQuery(document).ready(function($) {
            $("#fomData").validate({
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
                    password: {
                        required: true,
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password",
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
                        required: "Please enter contact number",
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