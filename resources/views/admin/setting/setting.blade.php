@extends('admin.layouts.master')

@section('title', 'Setting')
@section('main-content')

    <div class="main-content">
        @include('admin.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">Setting</span>
                </nav>
            </div>
        </div>
        <!-- Page Container START -->
        <div class="card">
            <div class="card-body">

                <h4>Setting</h4>

                <div class="m-t-50" style="">

                    <form id="settings" method="POST" action="{{ route('admin.setting.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="title">Title</label>
                                <input type="text" class="form-control mb-2" name="title" id="title"
                                    placeholder="Title" value="{{ !empty($setting) ? $setting->title : env('APP_NAME') }}"
                                    required>
                            </div>
                            <div class="form-group col-md-4">

                                <label for="email">Email</label>
                                <input type="email" class="form-control mb-2" name="email" id="email"
                                    placeholder="Email" value="{{ !empty($setting) ? $setting->email : '' }}">

                            </div>
                            <div class="form-group col-md-4">

                                <label for="contact">Contact Number</label>
                                <input type="number" min="0" maxlength="10" minlength="10" class="form-control mb-2"
                                    name="contact_no" id="contact" placeholder="Contact Number"
                                    value="{{ !empty($setting) ? $setting->contact_number : '' }}">

                            </div>
                            <div class="form-group col-md-4">

                                <label for="flink">Facebook Link</label>
                                <input type="url" class="form-control mb-2" name="flink" id="flink"
                                    placeholder="Facebook Link"
                                    value="{{ !empty($setting) ? $setting->facebook_link : '' }}">

                            </div>
                            <div class="form-group col-md-4">

                                <label for="t_link">Twitter Link</label>
                                <input type="url" class="form-control mb-2" name="t_link" id="t_link"
                                    placeholder="Twitter Link" value="{{ !empty($setting) ? $setting->twitter_link : '' }}">

                            </div>
                            <div class="form-group col-md-4">

                                <label for="l_link">Linkedin Link</label>
                                <input type="url" class="form-control mb-2" name="l_link" id="l_link"
                                    placeholder="Linkedin Link"
                                    value="{{ !empty($setting) ? $setting->linkedin_link : '' }}">

                            </div>
                            <div class="form-group col-md-4">
                                <label for="leader_image">Logo</label>
                                <input type="file" class="form-control" name="logo" id="logofiles"
                                    accept=".png, .jpg, .jpeg">

                                <div class="form-row">
                                    <div class="form-group col-md-3  mt-2">
                                        <img id="logoimagePreviews"
                                            src="{{ !empty($setting) && $setting->logo ? asset('uploads/setting/' . $setting->logo) : '' }}"
                                            alt="Logo Preview" class="img-reposive w-100">
                                    </div>
                                </div>
                            </div>


                            <div class="form-group col-md-4">
                                <label for="leader_image">Favicon</label>
                                <input type="file" class="form-control" name="favicon_img" id="files"
                                    accept=".png, .jpg, .jpeg">

                                <div class="form-row">
                                    <div class="form-group col-md-1 mt-2">
                                        <img id="imagePreviews"
                                            src="{{ !empty($setting) && $setting->favicon ? asset('uploads/setting/' . $setting->favicon) : '' }}"
                                            alt="Favicon Icon Preview" class="img-reposive w-100">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit" id="btnSubmit">Submit</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script>
        $('#settings').validate({
            rules: {
                title: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: "Please enter site title"
                }
            }
        });
        $(document).ready(function() {
            if (!$("#imagePreviews").attr("src")) {
                $("#imagePreviews, #logodeleteImageButtons").hide();
            }
            if (!$("#logoimagePreviews").attr("src")) {
                $("#logoimagePreviews, #deleteImageButtons").hide();
            }

            // Function to preview image
            $("#files").change(function() {
                var input = this;
                var imagePreview = $("#imagePreviews")[0];
                var deleteButton = $("#deleteImageButtons");

                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $(imagePreview).attr("src", e.target.result);
                        $(imagePreview).show();
                        $(deleteButton).show();
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            });

            // Function to delete image
            $("#deleteImageButtons").click(function() {
                var confirmation = confirm("Are you sure you want to delete the image?");
                if (confirmation) {
                    $("#files").val(""); // Clear the file input
                    $("#imagePreviews").attr("src", "").hide(); // Clear the image preview and hide it
                    $(this).hide(); // Hide the delete button
                }
            });

            // Function to preview image
            $("#logofiles").change(function() {
                var input = this;
                var imagePreview = $("#logoimagePreviews")[0];
                var deleteButton = $("#logodeleteImageButtons");

                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $(imagePreview).attr("src", e.target.result);
                        $(imagePreview).show();
                        $(deleteButton).show();
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            });

            // Function to delete image
            $("#logodeleteImageButtons").click(function() {
                var confirmation = confirm("Are you sure you want to delete the image?");
                if (confirmation) {
                    $("#logofiles").val(""); // Clear the file input
                    $("#logoimagePreviews").attr("src", "").hide(); // Clear the image preview and hide it
                    $(this).hide(); // Hide the delete button
                }
            });
        });
    </script>
@endsection
