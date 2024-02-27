<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Forget Password ||
        {{ !empty($siteSetting) && !empty($siteSetting->title) ? Ucfirst($siteSetting->title) : env('APP_NAME') }}
    </title>


    <!-- Favicon -->
    <link rel="shortcut icon"
        href="@if (
            !empty($siteSetting) &&
                isset($siteSetting->favicon) &&
                file_exists(base_path('uploads/setting/' . $siteSetting->favicon))) {{ env('ASSET_URL') . '/uploads/setting/' . $siteSetting->favicon }} @else{{ asset('assets/images/logo/logo.png') }} @endif">


    <!-- Favicon -->

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
            style="background-image: url({{ asset('assets/images/others/login-3.png') }})">
            <div class="d-flex flex-column justify-content-between w-100">
                <div class="container d-flex h-100">
                    <div class="row align-items-center w-100">
                        <div class="col-md-7 col-lg-5 m-h-auto">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    @include('admin.includes.message')
                                    <div class="d-flex align-items-center justify-content-between m-b-30">
                                        <a href="{{!empty($siteSetting) && !empty($siteSetting->logo_link) ? $siteSetting->logo_link : "" }} "  {{!empty($siteSetting) && !empty($siteSetting->logo_link) ? 'target="_blank"' : "" }}>
                                            <img style="width: 130px ; hight:50px"
                                                src="@if (
                                                    !empty($siteSetting) &&
                                                        !empty($siteSetting->logo) &&
                                                        file_exists(base_path('uploads/setting/' . $siteSetting->logo))) {{ asset('/uploads/setting/' . $siteSetting->logo) }} @else {{ asset('assets/images/logo/logo.png') }} @endif"
                                                alt="Logo">
                                        </a>
                                        <h2 class="m-b-0">Forget Password</h2>
                                    </div>
                                    <form id="forgetPassSendmail" method="POST"
                                        action="{{ route('company.forget-password') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label class="font-weight-semibold" for="email">Email:</label>
                                            <div class="input-affix">
                                                <i class="prefix-icon anticon anticon-user"></i>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    placeholder="Email">
                                            </div>
                                            <label id="email-error" class="error" for="email"></label>
                                        </div>

                                        <div class="form-group">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="font-size-13 text-muted">
                                                    Don't have an account?
                                                    <a class="small" href="{{ route('company.signup') }}"> Signup</a>
                                                </span>
                                                <button type="submit"
                                                    class="btn btn-primary submitform">Submit</button>
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
        $('#forgetPassSendmail').validate({
            rules: {
                email: {
                    required: true,                   
                }
            },
            messages: {
                email: {
                    required: "Please enter Email"
                },
            },
            submitHandler: function(form) {
                // Show the spinner                 
                $('.submitform').html(
                    'Submit <div id="button-spinner" style="margin-left: 10px; width: 15px; height: 15px; display: none" class="spinner-border"></div>'
                    ).attr('disabled', true);
                $('#button-spinner').show();
                form.submit();
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
