
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <title>Login || {{ !empty($siteSetting) && !empty($siteSetting->title) ? $siteSetting->title :env('APP_NAME') }} </title>
    <!-- Favicon -->
    <link rel="shortcut icon"
    href="@if (!empty($siteSetting) && isset($siteSetting->favicon) && file_exists(base_path('uploads/setting/' . $siteSetting->favicon))) {{env('ASSET_URL').'/uploads/setting/' . $siteSetting->favicon }} @else{{ asset('assets/images/logo/logo.png') }} @endif">

    <!-- page css -->

    <!-- Core css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">
    <style>
        .error {
            color: red;
        }
    </style>

</head>

<body>

    <div class="app">
        <div class="container-fluid">
            <div class="d-flex full-height p-v-15 flex-column justify-content-between">
                <div class="d-none d-md-flex p-h-40">
                    <a href="{{!empty($siteSetting) && !empty($siteSetting->logo_link) ? $siteSetting->logo_link : "" }} "  {{!empty($siteSetting) && !empty($siteSetting->logo_link) ? 'target="_blank"' : "" }}>
                    <img  style="width: 130px ; hight:50px" src="@if (
                        !empty($siteSetting) &&
                            !empty($siteSetting->logo) &&
                            file_exists(base_path('uploads/setting/' . $siteSetting->logo))) {{env('ASSET_URL').'/uploads/setting/'. $siteSetting->logo }} @else {{asset('assets/images/logo/logo.png')}} @endif"
                        alt="Logo">
                    </a>
                </div>
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-body">
                                    @include('admin.includes.message')
                                    <h2 class="m-t-20">Sign In</h2>
                                    <p class="m-b-30">Enter your credential to get access</p>
                                    <form method="POST" action="{{ route('company.login') }}" id="companylogin">
                                        @csrf
                                        <div class="form-group">
                                            <label class="font-weight-semibold" for="userName">Username:</label>
                                            <div class="input-affix">
                                                <i class="prefix-icon anticon anticon-user"></i>
                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus placeholder="Email">

                                                @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <label id="email-error" class="error" for="email"></label>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-semibold" for="password">Password:</label>
                                            <div class="input-affix m-b-10">
                                                <i class="prefix-icon anticon anticon-lock"></i>
                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" autocomplete="current-password">
                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <label id="password-error" class="error" for="password"></label>
                                            <a class="float-right font-size-13 text-muted" href="{{route('company.forgetpassword')}}">Forget Password?</a>
                                        </div>
                                        </br>
                                        <div class="form-group">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="font-size-13 text-muted">
                                                    Don't have an account?
                                                    <a class="small" href="{{route('company.signup')}}"> Signup</a>
                                                </span>
                                                <button class="btn btn-primary">Sign In</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="offset-md-1 col-md-6 d-none d-md-block">
                            <img class="img-fluid" src="{{asset('assets/images/others/login-2.png')}}" alt="">
                        </div>
                    </div>
                </div>
                <div class="d-none d-md-flex  p-h-40 justify-content-between">
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script>
          $.validator.addMethod("email", function(value, element) {
            return this.optional(element) ||
                /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i
                .test(value);
        }, "Please enter a valid email id");
        $('#companylogin').validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                },
            },
            messages: {
                email: {
                    required: "Please enter email",
                    email: "Please enter valid email address."
                },
                password: {
                    required: "Please enter password",
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

<script>
    setTimeout(function() {
        $(".alert").remove();
    }, 5000); // 5 secs
</script>