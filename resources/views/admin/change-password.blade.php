@extends('admin.layouts.master')
@section('title', 'Dashboard')
@section('main-content')
    <style>
        .toggle-password {
            float: right;
            cursor: pointer;
            margin-right: 10px;
            margin-top: -25px;
        }
    </style>
    <!-- Page Container START -->
    <!-- Content Wrapper START -->
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">Change Password</h2>
        </div>
        @php
            $user = Auth::user();
        @endphp


        <div class="card">
            <div class="card-body">
                <div class="m-t-25">
                    <form id="form-validation" action="{{ route('admin.UpdatePassword') }}" method="post">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="control-label" for="current_password">Password</label>
                                <input type="password" id="current_password" class="form-control" placeholder="Password"
                                    name="current_password">
                                <i class="toggle-password fa fa-fw fa-eye-slash"></i>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label" for="new_password">New Password</label>
                                <input type="password" id="new_password" class="form-control" placeholder="New Password"
                                    name="new_password">
                                <i class="toggle-password fa fa-fw fa-eye-slash"></i>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label" for="confirm_password">Confirm Password</label>
                                <input type="password" id="confirm_password" class="form-control"
                                    placeholder="Confirm Password" name="confirm_password">
                                <i class="toggle-password fa fa-fw fa-eye-slash"></i>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="float: right;">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Wrapper END -->

@endsection
@section('js')
    <script>
        $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            input = $(this).parent().find("input");
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    </script>
    <script>
        $("#form-validation").validate({
            ignore: ':hidden:not(:radio)',
            errorElement: 'label',
            rules: {
                current_password: {
                    required: true,
                    minlength: 8,
                    maxlength: 30,
                },
                new_password: {
                    required: true,
                    minlength: 8,
                    maxlength: 30,
                },
                confirm_password: {
                    required: true,
                    equalTo: '#new_password',
                },
            },
            messages: {
                current_password: {
                    required: "Please Enter Password",

                },
                new_password: {
                    required: "Please Enter New Password",

                },
                confirm_password: {
                    required: "Please re-type new password",
                    equalTo: "Please Enter The Same Value Again"
                },
            }
        });
    </script>
@endsection
