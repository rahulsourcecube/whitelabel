@extends('admin.layouts.master')
@section('title', 'Update Package')
@section('main-content')
    <div class="main-content">
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">
                        <i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <a href="{{ route('admin.package.list') }}" class="breadcrumb-item">Package</a>
                    <span class="breadcrumb-item active">Update</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Update Package</h4>
                <div class="m-t-50" style="">
                    <form id="package" method="POST" action="{{ route('admin.package.update', $package->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Title <span class="error">*</span></label>
                                <input type="text" class="form-control" id="name" name="title" placeholder="Title"
                                    value="{{ !empty($package->title) ? $package->title : '' }}" maxlength="150">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="campaign"> No Of Campaign <span class="error">*</span></label>
                                <input type="text" class="form-control" id="campaign" name="campaign"
                                    value="{{ !empty($package->no_of_campaign) ? $package->no_of_campaign : '' }}"
                                    placeholder="No Of Campaign"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="user"> No Of User <span class="error">*</span></label>
                                <input type="text" class="form-control" id="user" name="user"
                                    placeholder="No Of User" value="{{ !empty($package->no_of_user) ? $package->no_of_user : '' }}"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="employee"> No Of Employee <span class="error">*</span></label>
                                <input type="text" class="form-control" id="employee" name="employee"
                                    placeholder="No Of Employee" value="{{ !empty($package->no_of_employee) ? $package->no_of_employee : '' }}"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputype">Type <span class="error">*</span></label>
                                <select id="inputype" name="type" class="form-control type">

                                    <option value="1"
                                        {{ !empty($package->type) && $package->type == '1' ? 'selected' : '' }}>Free Trial
                                    </option>
                                    <option value="2"
                                        {{ !empty($package->type) && $package->type == '2' ? 'selected' : '' }}>Monthly
                                    </option>
                                    <option value="3"
                                        {{ !empty($package->type) && $package->type == '3' ? 'selected' : '' }}>Yearly
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="day" class="day_title">No Of Day <span class="error">*</span></label>
                                <input type="text" class="form-control day_place" id="day" name="day"
                                    placeholder="No Of Day"
                                    value="{{ !empty($package->duration) ? $package->duration : '' }}"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            </div>

                            <div class="col-md-3 pl-5">
                                <label for="expiry_date">Status</label>
                                <div class="form-group align-items-center">
                                    <div class="switch m-r-10">
                                        <input type="checkbox" id="switch-1" name="status" value="1" @if (isset( $package->status) && $package->status == 1 ) checked="" @endif>
                                        <label for="switch-1"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3" id="price-section">
                                <label for="price"> Price <span class="error">*</span></label>
                                <input type="text" class="form-control" id="price" name="price"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="10"
                                    value="{{ !empty($package->price) ? $package->price : '' }}" placeholder="Price">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="descriptions">Description <span class="error">*</span></label>
                                <textarea type="text" class="form-control" id="descriptions" name="description" placeholder="description"> {{ !empty($package->description) ? $package->description : '' }} </textarea>
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
                                <img id="imagePreview" src="{{ asset('uploads/package/' . $package->image) }}"
                                    alt="Image Preview" style="max-width: 100%; max-height: 80%;">
                                {{-- <button type="button" id="deleteImageButton" class="btn btn-danger btn-sm mt-2"
                                    style="display: block;" onclick="deleteImage()"><i class="fa fa-trash"></i></button> --}}
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

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
            },
            messages: {
                title: {
                    required: "Please Enter Package Title"
                },
                campaign: {
                    required: "Please Enter No Of Campaign"
                },
                employee: {
                    required: "Please Enter No Of Employee"
                },
                user: {
                    required: "Please Enter No Of User"
                },
                description: {
                    required: "Please Enter description"
                },
                day: {
                    required: "This field is required"
                },
                price: {
                    required: "Please Enter Price"
                },
                // image: {
                //     required: "Please Select Image"
                // },
            }
        });

        isFreePackage();
        $(document).on("change", "#inputype", function() {

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

        });

        function isFreePackage() {

            if ($("#inputype option:selected").val() == '1') {
                $("#price-section").hide();
                $("#price").val("0");
            } else {
                $("#price-section").show();
                // $("#price").val("");
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
