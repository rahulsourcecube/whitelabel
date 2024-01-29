
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Error</title>

    <!-- Favicon -->
    <link rel="shortcut icon"
    href="@if (!empty($siteSetting) && isset($siteSetting->favicon) && file_exists(('uploads/setting/' . $siteSetting->favicon))) {{ url('uploads/setting/' . $siteSetting->favicon) }} @else{{ asset('assets/images/logo/logo.png') }} @endif">
    <!-- page css -->

    <!-- Core css -->
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
        <div class="container-fluid">
            <div class="d-flex p-v-20 flex-column justify-content-between">
                <div class="d-none d-md-flex p-h-40">
                    <img  style="width: 130px ; hight:50px" src="@if (
                        !empty($siteSetting) &&
                            !empty($siteSetting->logo) &&
                            file_exists('uploads/setting/' . $siteSetting->logo)) {{url('uploads/setting/' . $siteSetting->logo)}} @else{{asset('assets/images/logo/logo.png')}}@endif"
                        alt="Logo">
                </div>
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-6 m-auto">                            
                                <h1 class="font-weight-semibold display-1 text-primary lh-1-2"></h1>
                                <h2 class="font-weight-light font-size-30">Woops! company not available please inform you administer </h2>
                        </div>
                        <div class="col-md-6 m-l-auto">
                            {{-- <img class="img-fluid" src="assets/images/others/error-1.png" alt=""> --}}
                        </div>
                    </div>
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