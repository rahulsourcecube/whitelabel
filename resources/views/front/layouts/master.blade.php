<!DOCTYPE html>
<html lang="en">
@php
    $siteSetting = App\Helpers\Helper::getSiteSetting();
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title') ||
        {{ !empty($siteSetting) && !empty($siteSetting->title) ? $siteSetting->title : env('APP_NAME') }}</title>
    <!-- Favicon -->
    <link rel="shortcut icon"
        href=" @if (
            !empty($siteSetting) &&
                !empty($siteSetting->favicon) &&
                base_path(public_path('uploads/setting/' . $siteSetting->favicon))) {{ asset('uploads/setting/' . $siteSetting->favicon) }} @else{{ asset('assets/images/logo/logo.png') }} @endif">
    <!-- page css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/dataTables.bootstrap.min.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Core css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/admin/common.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>


<body>
    <div class="app survey-app">
        <div class="layout">
            <!-- Header START -->
            @include('front.includes.header')
            <!-- Header END -->

            <!-- Page Container START -->
            <div class=" p-h-0 p-v-20 bg full-height">
                <div class="page-containers">
                    <!-- Content Wrapper START -->
                    @yield('main-content')
                    <!-- Content Wrapper END -->

                </div>
            </div>
            <!-- Page Container END -->
        </div>
        <!-- Footer START -->
        <footer class="footer">
            <div class="footer-content">
                <p class="m-b-0">Copyright © {{ date('Y') }}. All rights reserved.</p>
            </div>
        </footer>
        <!-- Footer END -->
    </div>

    <!--  Footer Scripts -->
    @include('front.includes.footer_scripts')
</body>

</html>
