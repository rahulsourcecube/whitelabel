<!DOCTYPE html>
<html lang="en">



<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <title>Sign Up || {{ !empty($siteSetting) && !empty($siteSetting->title) ? $siteSetting->title : env('APP_NAME') }} </title>
    <!-- Favicon -->
    <link rel="shortcut icon"
    href="@if (!empty($siteSetting) && isset($siteSetting->favicon) && file_exists(('uploads/setting/' . $siteSetting->favicon))) {{ url('uploads/setting/' . $siteSetting->favicon) }} @else{{ asset('assets/images/logo/logo.png') }} @endif">
    <!-- page css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Core css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/admin/common.css') }}">

</head>

<body>
    <div class="app">
        <div class="container-fluid p-h-0 p-v-20 bg full-height d-flex"
            style="background-image: url('{{asset('assets/images/others/login-3.png')}}">
            <div class="d-flex flex-column justify-content-between w-100">
                <div class="container d-flex h-100">
                    <div class="row align-items-center w-100">
                        <div class="col-md-7 col-lg-6 m-h-auto">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    @include('admin.includes.message')
                                    <div class="d-flex align-items-center justify-content-between m-b-30">
                                        <img  style="width: 130px ; hight:50px" src="@if (
                                            !empty($siteSetting) &&
                                                !empty($siteSetting->logo) &&
                                                file_exists('uploads/setting/' . $siteSetting->logo)) {{url('uploads/setting/' . $siteSetting->logo)}} @else {{asset('assets/images/logo/logo.png')}} @endif "
                                            alt="Logo">
                                        <h2 class="m-b-0"> Company Sign Up</h2>
                                    </div>
                                    <form id="signup" method="POST" action="{{ route('company.signup.store') }}">
                                        @csrf
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="font-weight-semibold" for="fname">First Name:</label>
                                                <input type="text" class="form-control" name="fname" id="fname"
                                                    placeholder="First Name" value="{{old('fname')}}" maxlength="50">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="font-weight-semibold" for="lname">Last name</label>
                                                <input type="text" class="form-control" name="lname" id="lname"
                                                    placeholder="Last name" value="{{old('lname')}}" maxlength="50">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label class="font-weight-semibold" for="email">Email:</label>
                                                <input type="email" class="form-control" name="email" id="email"
                                                    placeholder="Email" value="{{old('email')}}" maxlength="50">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label class="font-weight-semibold" for="cname">Company Name:</label>
                                                <input type="text" class="form-control" name="cname" id="cname"
                                                    placeholder="Company Name" value="{{old('cname')}}" maxlength="50">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label class="font-weight-semibold" for="ccontact">Contact
                                                    Number:</label>
                                                <input type="text"   class="form-control" name="ccontact" id="ccontact"
                                                    placeholder="Contact Number" maxlength="10"  
                                                    value="{{old('ccontact')}}"   onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                            </div>
                                            <div class="col-md-12">
                                                <label class="font-weight-semibold" for="dname">Domain Name:</label>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" name="dname"
                                                        placeholder="Domain Name" id="dname" value="{{old('dname')}}" maxlength="50">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon2">{{
                                                            Request::getHost() }}</span>
                                                    </div>
                                                </div>
                                                <label id="dname-error" class="error" for="dname"></label>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="font-weight-semibold" for="password">Password:</label>
                                                <input type="password" class="form-control" name="password"
                                                    id="password" placeholder="Password" value="{{old('password')}}" maxlength="50">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="font-weight-semibold" for="confirmPassword">Confirm
                                                    Password:</label>
                                                <input type="password" class="form-control" name="cpassword"
                                                    id="confirmPassword" placeholder="Confirm Password"
                                                    value="{{old('cpassword')}}" maxlength="50">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="d-flex align-items-center justify-content-between p-t-15">
                                                <button type="submit" class="btn btn-primary submitform">Sign Up</button>
                                            </div>
                                        </div>
                                    </form>
                                    {{-- <label for="checkbox"><span>Already have an account? <a
                                                href="{{route('company.signin')}}">Login</a></span></label> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-none d-md-flex p-h-40 justify-content-between">
                </div>
            </div>
        </div>
    </div>


    <script>
        $.validator.addMethod("subdomainV", function(value, element) {
        var regex = new RegExp("^[a-zA-Z]+[a-zA-Z0-9\\-]*$");
        return regex.test(value);
        }, "Please provide proper subdomain name");
        $('#signup').validate({
            rules: {
                fname: {
                    required: true
                },
                lname: {
                    required: true
                },
                cname: {
                    required: true
                },
                ccontact: {
                    required: true,
                    minlength: 10,
                    digits: true
                },
                dname: {
                    required: true,
                    subdomainV: true /*** New Rule Applied */
                },
                email: {
                    required: true
                },
                password: {
                    minlength: 8,
                    maxlength: 50,
                    required: true,
                },
                cpassword: {
                    required: true,
                    equalTo: "#password"
                },
            },
            messages: {
                fname: {
                    required: "Please enter first name"
                },
                lname: {
                    required: "Please enter last name"
                },
                cname: {
                    required: "Please enter company name"
                },
                ccontact: {
                    required: "Please enter contact number",
                    digits: "Please enter valid contact number",
                    minlength: "Your phone number must be 10 digits."
                },
                dname: {
                    required: "Please enter domain name"
                },
                email: {
                    required: "Please enter email"
                },
                password: {
                required: "Please enter password",
                },
                cpassword: {
                    required: "Please enter confirm password",
                    equalTo: "The password you entered does not match.",
                },
            },
            submitHandler: function(form) {                    
                 // Show the spinner                 
                $('.submitform').html('Sign Up <div id="button-spinner" style="margin-left: 10px; width: 15px; height: 15px; display: none" class="spinner-border"></div>').attr('disabled', true);
                $('#button-spinner').show();
                form.submit();
                }
        });
        $(document).ready(function() {
    // Show the alert
            $("alert").fadeIn();

            // Hide the alert after 3 seconds
            setTimeout(function() {
            $("#alert").fadeOut();
            }, 2000);
    });
    </script>
    <!-- Core Vendors JS -->
    <script src="{{asset('assets/js/vendors.min.js')}}"></script>

    <!-- page js -->

    <!-- Core JS -->
    <script src="{{asset('assets/js/app.min.js')}}"></script>

</body>



</html>
