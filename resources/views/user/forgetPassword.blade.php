<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.png') }}">

    <!-- page css -->

    <!-- Core css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">

</head>

<body>
    <div class="app">
        <div class="container-fluid p-h-0 p-v-20 bg full-height d-flex" style="background-image: url({{asset('assets/images/others/login-3.png')}})">
            <div class="d-flex flex-column justify-content-between w-100">
                <div class="container d-flex h-100">
                    <div class="row align-items-center w-100">
                        <div class="col-md-7 col-lg-5 m-h-auto">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between m-b-30">
                                        <img class="img-fluid" alt="" src="{{asset('assets/images/logo/logo.png')}}">
                                        <h2 class="m-b-0">Forget Password</h2>
                                    </div>
                                    <form>
                                        <div class="form-group">
                                            <label class="font-weight-semibold" for="userName">Email:</label>
                                            <div class="input-affix">
                                                <i class="prefix-icon anticon anticon-user"></i>
                                                <input type="text" class="form-control" id="userName" placeholder="Email">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="font-size-13 text-muted">
                                                    Don't have an account?
                                                    <a class="small" href="{{route('user.signup')}}"> Signup</a>
                                                </span>
                                                <a  href="{{route('user.confirmPassword')}}" class="btn btn-primary">Submit</a>
                                            </div>
                                        </div>
                                    </form>
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


   <!-- Core Vendors JS -->
   <script src="{{ asset('assets/js/vendors.min.js') }}"></script>

   <!-- page js -->

   <!-- Core JS -->
   <script src="{{ asset('assets/js/app.min.js') }}"></script>

</body>

</html>
