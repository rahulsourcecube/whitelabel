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
                                <label for="title">Title <span class="error">*</span> </label>
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
                                    value="{{ !empty($setting) ? $setting->contact_number : '' }}" maxlength="10">

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
                        <h4>Mail Credentials</h4>

                        <div class="m-t-50" style="">


                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="mail_mailer">Driver <span class="error">*</span> </label>
                                    <input type="text" class="form-control mb-2" name="mail_mailer" id="mail_mailer"
                                        placeholder="Mail MaiLer" value="{{ !empty($setting) ? $setting->mail_mailer : '' }}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="mail_host">Mail Host <span class="error">*</span> </label>
                                    <input type="text" class="form-control mb-2" name="mail_host" id="mail_host"
                                        placeholder="Mail Host" value="{{ !empty($setting) ? $setting->mail_host : '' }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="mail_port">Mail Port <span class="error">*</span> </label>
                                    <input type="text" class="form-control mb-2" name="mail_port" id="mail_port"
                                        placeholder="Mail Port" value="{{ !empty($setting) ? $setting->mail_port : '' }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="mail_username">Mail Username <span class="error">*</span> </label>
                                    <input type="text" class="form-control mb-2" name="mail_username"
                                        id="mail_username" placeholder="Mail Username" value="{{ !empty($setting) ? $setting->mail_username : '' }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="mail_password">Mail Password <span class="error">*</span> </label>
                                    <input type="text" class="form-control mb-2" name="mail_password"
                                        id="mail_password" placeholder="Mail Password" value="{{ !empty($setting) ? $setting->mail_password : '' }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="mail_encryption">Mail Encryption <span class="error">*</span> </label>
                                    <input type="text" class="form-control mb-2" name="mail_encryption"
                                        id="mail_encryption" placeholder="Mail Encryption" value="{{ !empty($setting) ? $setting->mail_encryption : '' }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="mail_address">Mail From Address <span class="error">*</span> </label>
                                    <input type="text" class="form-control mb-2" name="mail_address"
                                        id="mail_address" placeholder="Mail From Address" value="{{ !empty($setting) ? $setting->mail_address : '' }}">
                                </div>
                            </div>

                            <h4>Stripe Credentials</h4>

                            <div class="m-t-50" style="">                             
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="stripe_key">Stripe Key <span class="error">*</span></label>
                                            <input type="text" class="form-control mb-2" name="stripe_key"
                                                id="stripe_key" placeholder="Stripe Key" value="{{ !empty($setting) ? $setting->stripe_key : '' }}" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="stripe_secret">Stripe Secret <span class="error">*</span> </label>
                                            <input type="text" class="form-control mb-2" name="stripe_secret"
                                                id="stripe_secret" placeholder="Stripe Secret" value="{{ !empty($setting) ? $setting->stripe_secret : '' }}">
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
        $.validator.addMethod("email", function(value, element) {
            return this.optional(element) ||
                /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i
                .test(value);
        }, "Please enter a valid email id");
        $('#settings').validate({
            rules: {
                title: {
                    required: true
                },
                mail_mailer: {
                    required: true
                },
                mail_host: {
                    required: true
                },
                mail_port: {
                    required: true
                },
                mail_username: {
                    required: true
                },
                mail_password: {
                    required: true
                },
                mail_encryption: {
                    required: true
                },
                mail_address: {
                    required: true
                },
                stripe_key: {
                    required: true
                },
                stripe_secret: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: "Please enter site title"
                },
                mail_mailer: {
                    required: "Please enter site mail mailer"
                },
                mail_host: {
                    required: "Please enter site mail host"
                },
                mail_port: {
                    required: "Please enter site mail port"
                },
                mail_username: {
                    required: "Please enter site mail username"
                },
                mail_password: {
                    required: "Please enter site mail password"
                },
                mail_encryption: {
                    required: "Please enter site mail encryption"
                },
                mail_address: {
                    required: "Please enter site mail address"
                },
                stripe_key: {
                    required: "Please enter site stripe key"
                },
                stripe_secret: {
                    required: "Please enter site stripe secret"
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
