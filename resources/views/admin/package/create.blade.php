@extends('admin.layouts.master')
@section('title', 'Add Package')
@section('main-content')
    <div class="main-content">
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">
                        <i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <a href="{{ route('admin.package.list') }}" class="breadcrumb-item">Package</a>
                    <span class="breadcrumb-item active">Add</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Add Package</h4>
                <div class="m-t-50" style="">
                    <form id="package" method="POST" action="{{ route('admin.package.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Title <span class="error">*</span></label>
                                <input type="text" class="form-control" id="name" name="title" placeholder="Title"
                                    maxlength="150">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="campaign"> No Of Campaign <span class="error">*</span></label>
                                <input type="text" class="form-control" id="campaign" name="campaign"
                                    placeholder="No Of Campaign"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="user"> No Of User <span class="error">*</span></label>
                                <input type="text" class="form-control" id="user" name="user"
                                    placeholder="No Of User"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="employee"> No Of Employee <span class="error">*</span></label>
                                <input type="text" class="form-control" id="employee" name="employee"
                                    placeholder="No Of Employee"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputype">Type <span class="error">*</span></label>
                                <select id="inputype" name="type" class="form-control inputype">
                                    <option value="1">Free Trial</option>
                                    <option value="2">Monthly</option>
                                    <option value="3">Yearly</option>
                                </select>
                            </div>


                            <div class="form-group col-md-3">
                                <label for="day" class="day_title">No Of Day <span class="error">*</span></label>
                                <input type="text" class="form-control day_place" id="day" name="day"
                                    placeholder="No Of Day"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            </div>
                            <div class="col-md-3 pl-5">
                                <label for="expiry_date">Status</label>
                                <div class="form-group align-items-center">
                                    <div class="switch m-r-10">
                                        <input type="checkbox" id="switch-1" data-toggle="switch" name="status"
                                            value="true" checked>
                                        <label for="switch-1"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6" id="price-section">
                                <label for="price"> Price <span class="error">*</span></label>
                                <input type="text" class="form-control" id="price" name="price"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="10"
                                    placeholder="Price">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="descriptions">Description</label>
                                <textarea type="text" class="form-control" id="descriptions" name="description" placeholder="description"> </textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="file">Image <span class="error">*</span></label>
                                <input type="file" class="form-control" name="image" id="file"
                                    accept=".png, .jpg, .jpeg" onchange="previewImage()">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3" style="max-height: 200px;">
                                <img id="imagePreview" src="#" alt="Image Preview"
                                    style="max-width: 100%; max-height: 80%;display: none;">
                                <button type="button" id="deleteImageButton" class="btn btn-danger btn-sm mt-2"
                                    style="display: none;" onclick="deleteImage()"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script>
        $('#package').validate({
            rules: {
                title: {
                    required: true
                },
                campaign: {
                    required: true
                },
                employee: {
                    required: true
                },
                user: {
                    required: true
                },
                description: {
                    required: true
                },
                type: {
                    required: true
                },
                day: {
                    required: true
                },
                price: {
                    required: true
                },
                image: {
                    required: true,
                    maxfilesize: 1024 *
                        1024, // Specify the maximum file size in bytes (1MB in this example)
                    extension: "png|jpg|jpeg" // Specify the allowed file extensions
                },
            },
            messages: {
                title: {
                    required: "Please enter package title"
                },
                campaign: {
                    required: "Please enter no of campaign"
                },
                employee: {
                    required: "Please enter no of employee"
                },
                user: {
                    required: "Please enter no of user"
                },
                description: {
                    required: "Please enter description"
                },
                day: {
                    required: "This field is required"
                },
                price: {
                    required: "Please enter price"
                },
                image: {
                    required: "Please select an image",
                    maxfilesize: "File size must be less than 1MB",
                    extension: "Only PNG, JPG, and JPEG files are allowed"
                },
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
