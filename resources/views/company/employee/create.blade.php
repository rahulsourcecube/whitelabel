@extends('company.layouts.master')
@section('title', 'Add Employee')
@section('main-content')
<div class="main-content">
    @include('company.includes.message')
    <div class="page-header">
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a href="{{ route('company.dashboard') }}" class="breadcrumb-item">
                    <i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                <a href="{{ route('company.employee.list') }}" class="breadcrumb-item">Employee</a>
                <span class="breadcrumb-item active">Add</span>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4>Add Employee</h4>
            <div class="m-t-50" style="">
                <form id="employeeform" method="POST" action="{{ route('company.employee.store') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="fname">First Name <span class="error">*</span></label>
                            <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name"
                                maxlength="150" value="{{ old('fname') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lname">Last Name <span class="error">*</span></label>
                            <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name"
                                maxlength="150" value="{{ old('lname') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="number">Email Address <span class="error">*</span></label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Email Address" value="{{ old('email') }}">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="Type">Role</label>
                            <select id="Type" class="form-control" name="role">
                                <option value="" >Select</option>
                                <option value="1" >Staff</option>
                                <option value="2" >Manager</option>
                                <option value="3" >Lead</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password"> Password <span class="error">*</span></label>
                            <input type="text" class="form-control" id="password" name="password"
                                placeholder="Password">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cpassword"> Comfirm Password <span class="error">*</span></label>
                            <input type="text" class="form-control" id="cpassword" name="cpassword"
                                placeholder="Comfirm Password">
                        </div>

                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>


                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
<script>
    $('#employeeform').validate({
            rules: {
                fname: {
                    required: true
                },
                lname: {
                    required: true
                },
                email: {
                    required: true
                },
                number: {
                    required: true
                },
                password: {
                minlength: 8,
                maxlength: 30,
                required: true,
                // pwcheck: true,
                // checklower: true,
                // checkupper: true,
                // checkdigit: true
            },
            cpassword: {
                    required: true,
                    equalTo: "#password"
                },
                // image: {
                //     required: true,
                //     maxfilesize: 1024 *
                //         1024, // Specify the maximum file size in bytes (1MB in this example)
                //     extension: "png|jpg|jpeg" // Specify the allowed file extensions
                // },

            },
            messages: {
                fname: {
                    required: "Please enter first name  "
                },
                lname: {
                    required: "Please enter last name "
                },
                email: {
                    required: "Please enter email address"
                },
                number: {
                    required: "Please mobile number address"
                },
                password: {
                required: "Please enter password",
                // pwcheck: "Password is not strong enough",
                // checklower: "Need atleast 1 lowercase alphabet",
                // checkupper: "Need atleast 1 uppercase alphabet",
                // checkdigit: "Need atleast 1 digit"
            },
            cpassword: {
                required: "Please enter confirm password",
                equalTo: "The password you entered does not match.",
            },
                // image: {
                //     required: "Please select an image",
                //     maxfilesize: "File size must be less than 1MB",
                //     extension: "Only PNG, JPG, and JPEG files are allowed"
                // },
            }
        });

        isFreePackage();

        $(document).on("change", '#inputype', function() {
            type = $(this).val();
            isFreePackage();

            if (type == '1') {
                $('.day_title').html('No Of Day');
                $(".day_place").attr("placeholder", "No Of Day").placeholder();
            } else if (type == '2') {
                $('.day_title').html('No Of Month');
                $(".day_place").attr("placeholder", "No Of Month").placeholder();

            } else {
                $('.day_title').html('No Of Year');
                $(".day_place").attr("placeholder", "No Of Year").placeholder();
            }
        })

        function isFreePackage() {

            if ($("#inputype option:selected").val() == '1') {
                $("#price-section").hide();
                $("#price").val("0");
            } else {
                $("#price-section").show();
                $("#price").val("");
            }
        }

        function previewImage() {
            var input = document.getElementById('file');
            var preview = document.getElementById('imagePreview');
            var deleteButton = document.getElementById('deleteImageButton');

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    deleteButton.style.display = 'block';
                };

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '#';
                preview.style.display = 'none';
                deleteButton.style.display = 'none';
            }
        }

        function deleteImage() {
            var input = document.getElementById('file');
            var preview = document.getElementById('imagePreview');
            var deleteButton = document.getElementById('deleteImageButton');

            input.value = ''; // Clear the file input
            preview.src = '#';
            preview.style.display = 'none';
            deleteButton.style.display = 'none';
        }
        $(document).ready(function() {
            window.onload = () => {
                CKEDITOR.replace("description");
            };
        });
</script>
@endsection

@section('js')
    <script src="{{ asset('assets/js/pages/company-employee.js') }}"></script>
@endsection
