<!DOCTYPE html>
<html lang="en">
@php
$siteSetting = App\Helpers\Helper::getSiteSetting();

@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title') || {{ !empty($siteSetting) && !empty($siteSetting->title) ? $siteSetting->title : env('APP_NAME') }}
    </title>
    <!-- Favicon -->
    <link rel="shortcut icon"
        href="@if (!empty($siteSetting) && isset($siteSetting->favicon) && file_exists(public_path('uploads/setting/' . $siteSetting->favicon))) {{ asset('uploads/setting/' . $siteSetting->favicon) }} @else{{ asset('assets/images/logo/logo.png') }} @endif">
    <!-- page css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    {{-- DatePicker Link --}}
    <link href="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <!-- Core css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/admin/common.css') }}">
</head>

<body>
    <div class="app">
        <div class="layout">
            <!-- Header START -->
            @include('user.includes.header')
            <!-- Header END -->
            <!-- Side Nav START -->
            @include('user.includes.sidebar')


            <!-- Side Nav END -->
            <!-- Page Container START -->
            <div class="page-container user-panel">
                <div class="container notification">
                                {{-- @include('user.includes.message') --}}
                </div>
                <!-- Content Wrapper START -->

                @yield('main-content')

                <!-- Content Wrapper END -->
                <!-- Footer START -->
                <footer class="footer">
                    <div class="footer-content">
                        <p class="m-b-0">Copyright © {{ date('Y') }}. All rights reserved.</p>
                    </div>
                </footer>
                <!-- Footer END -->
            </div>
            <!-- Page Container END -->
        </div>
    </div>
    <!--  Footer Scripts -->
    @include('user.includes.footer_scripts')
</body>

</html>
