<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   
    <title>Confirm Password || {{ !empty($siteSetting) && !empty($siteSetting->title) ? Ucfirst($siteSetting->title) : env('APP_NAME') }} </title>
    <!-- Favicon -->
    <link rel="shortcut icon"
        href="@if (!empty($siteSetting) && isset($siteSetting->favicon) && file_exists(('uploads/setting/' . $siteSetting->favicon))) {{ url('uploads/setting/' . $siteSetting->favicon) }} @else{{ asset('assets/images/logo/logo.png') }} @endif">


 

    <!-- page css -->

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Core css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/admin/common.css') }}">

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
                                    @include('admin.includes.message')
                                    <div class="d-flex align-items-center justify-content-between m-b-30">
                                        <img  style="width: 130px ; hight:50px" src="@if (
                                        !empty($siteSetting) &&
                                            !empty($siteSetting->logo) &&
                                            file_exists('uploads/setting/' . $siteSetting->logo)) {{url('uploads/setting/' . $siteSetting->logo)}} @else{{asset('assets/images/logo/logo.png')}}@endif"
                                        alt="Logo">
                                        <h2 class="m-b-0">Change Password</h2>
                                    </div>
                                    
                                    <form  id="changePass" method="POST" action="{{ route('company.reset-password') }}">
                                        @csrf
                                        <input id="email" type="hidden"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    name="email" value="{{ isset($user->email) ? $user->email : '' }}"
                                                    placeholder="Email" readonly>
                                                    <input type="hidden" name="token" value="{{ $token }}">
                                        <div class="form-group">
                                            <label class="font-weight-semibold" for="userName">Password</label>
                                            <div class="input-affix">
                                                <i class="prefix-icon anticon anticon-lock"></i>
                                                <input type="password" class="form-control" id="password"
                                                    placeholder="Password" name="password">
                                            </div>
                                            <label id="password-error" class="error" for="password"></label>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-semibold" for="cpassword">Confirm Password</label>                                           
                                            <div class="input-affix m-b-10">
                                                <i class="prefix-icon anticon anticon-lock"></i>
                                                <input type="password" class="form-control"  name="cpassword" id="cpassword" placeholder="Confirm Password">
                                            </div>
                                            <label id="cpassword-error" class="error" for="cpassword"></label>
                                        </div>
                                        <div class="form-group">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="font-size-13 text-muted">
                                                    Don't have an account? 
                                                    <a class="small" href="{{route('company.signup')}}"> Signup</a>
                                                </span>
                                                <button  type="submit"  class="btn btn-primary">Change</button>
                                            </div>
                                        </div>
                                    </form>
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
       
        $('#changePass').validate({
            rules: {
               
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
                password: {
                required: "Please enter password",                
                },
                cpassword: {
                    required: "Please enter confirm password",
                    equalTo: "The password you entered does not match.",
                },
            }
        });
   
</script>
   <!-- Core Vendors JS -->
   <script src="{{ asset('assets/js/vendors.min.js') }}"></script>

   <!-- page js -->

   <!-- Core JS -->
   <script src="{{ asset('assets/js/app.min.js') }}"></script>

</body>

</html>