<!DOCTYPE html>
<html lang="en">



<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sign Up
    </title>
    <!-- Favicon -->
    <link rel="shortcut icon"
        href="@if (!empty($siteSetting) && isset($siteSetting->favicon) && file_exists(public_path('uploads/setting/' . $siteSetting->favicon))) {{ asset('uploads/setting/' . $siteSetting->favicon) }} @else{{ asset('assets/images/logo/favicon.png') }} @endif">
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
                        <div class="col-md-7 col-lg-5 m-h-auto">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    @include('admin.includes.message')
                                    <div class="d-flex align-items-center justify-content-between m-b-30">
                                        <img class="img-fluid" alt="" src="{{asset('assets/images/logo/logo.png')}}">
                                        <h2 class="m-b-0">Sign Up</h2>
                                    </div>
                                    <form id="signup" method="POST" action="{{ route('company.signup.store') }}">
                                        @csrf
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="font-weight-semibold" for="fname">First Name:</label>
                                                <input type="text" class="form-control" name="fname" id="fname"
                                                    placeholder="First Name">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="font-weight-semibold" for="lname">Last name</label>
                                                <input type="text" class="form-control" name="lname" id="lname"
                                                    placeholder="Last name">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label class="font-weight-semibold" for="email">Email:</label>
                                                <input type="email" class="form-control" name="email" id="email"
                                                    placeholder="Email">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label class="font-weight-semibold" for="cname">Company Name:</label>
                                                <input type="text" class="form-control" name="cname" id="cname"
                                                    placeholder="Company Name">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label class="font-weight-semibold" for="userName">Domain Name:</label>
                                                <input type="text" class="form-control" name="dname" id="userName"
                                                    placeholder="Domain Name">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="font-weight-semibold" for="password">Password:</label>
                                                <input type="password" class="form-control" name="password"
                                                    id="password" placeholder="Password">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="font-weight-semibold" for="confirmPassword">Confirm
                                                    Password:</label>
                                                <input type="password" class="form-control" name="cpassword"
                                                    id="confirmPassword" placeholder="Confirm Password">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="d-flex align-items-center justify-content-between p-t-15">
                                                {{-- <div class="checkbox">
                                                    <input id="checkbox" type="checkbox">

                                                </div> --}}
                                                <button type="submit" class="btn btn-primary">Sign Up</button>
                                            </div>
                                        </div>
                                    </form>
                                    <label for="checkbox"><span>Already have an account? <a
                                                href="{{route('company.signin')}}">Login</a></span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-none d-md-flex p-h-40 justify-content-between">
                    <span class="">© 2019 ThemeNate</span>
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <a class="text-dark text-link" href="#">Legal</a>
                        </li>
                        <li class="list-inline-item">
                            <a class="text-dark text-link" href="#">Privacy</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <script>
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
                dname: {
                    required: true
                },
                email: {
                    required: true
                },
                password: {
                minlength: 8,
                maxlength: 30,
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
                dname: {
                    required: "Please enter domain name"
                },
                email: {
                    required: "Please enter Email"
                },
                password: {
                required: "Please enter password",                
                },
                cpassword: {
                    required: "Please enter confirm password",
                    equalTo: "The password you entered does not match.",
                },
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