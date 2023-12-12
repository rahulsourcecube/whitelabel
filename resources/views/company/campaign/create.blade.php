@extends('company.layouts.master')
@section('title', 'Add Task')
@section('main-content')
    <div class="main-content">
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <a href="{{ route('admin.package.list') }}" class="breadcrumb-item">Task</a>
                    <span class="breadcrumb-item active">Add</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Add Task</h4>
                <div class="m-t-50" style="">
                    <form id="taskadd" method="POST" action="{{ route('company.campaign.referral.store') }}"enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Task Titles <span class="error">*</span></label>
                                <input type="text" class="form-control" id="name" name="title" placeholder="Task Title"
                                    maxlength="150">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="reaward"> Reaward <span class="error">*</span></label>
                                <input type="text" class="form-control" id="reaward" name="reaward"
                                    placeholder="Reaward"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="descriptions">Description</label>
                                <textarea type="text" class="form-control" id="descriptions" name="description" placeholder="description"> </textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="date">End date <span class="error">*</span></label>
                                <input type="date" class="form-control" id="" name="edate"
                                    placeholder="No Of Task"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="Type">Task Type</label>
                                <select id="Type" class="form-control" name="tasktype">
                                    <option >Select</option>
                                    <option >Referral</option>
                                    <option >Custom</option>
                                    <option >Social</option>
                                   
                                </select>
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
        $('#taskadd').validate({
            rules: {
                title: {
                    required: true
                },
                reaward: {
                    required: true
                },
                description: {
                    required: true
                },
                tasktype: {
                    required: true
                },
                edate: {
                    required: true
                },
               
                // image: {
                //     required: true,
                //     maxfilesize: 1024 *
                //         1024, // Specify the maximum file size in bytes (1MB in this example)
                //     extension: "png|jpg|jpeg" // Specify the allowed file extensions
                // },
            },
            messages: {
                title: {
                    required: "Please enter title"
                },
                reaward: {
                    required: "Please enter Reaward"
                },
                description: {
                    required: "Please enter description"
                },
                edate: {
                    required: "Please select End date"
                },
                tasktype: {
                    required: "Please enter price"
                },
                // image: {
                //     required: "Please select an image",
                //     maxfilesize: "File size must be less than 1MB",
                //     extension: "Only PNG, JPG, and JPEG files are allowed"
                // },
            }
        });


        // isFreePackage();

        // $(document).on("change", '#inputype', function() {
        //     type = $(this).val();
        //     isFreePackage();

        //     if (type == '1') {
        //         $('.day_title').html('No Of Day');
        //         $(".day_place").attr("placeholder", "No Of Day").placeholder();
        //     } else if (type == '2') {
        //         $('.day_title').html('No Of Month');
        //         $(".day_place").attr("placeholder", "No Of Month").placeholder();

        //     } else {
        //         $('.day_title').html('No Of Year');
        //         $(".day_place").attr("placeholder", "No Of Year").placeholder();
        //     }
        // })

        // function isFreePackage() {

        //     if ($("#inputype option:selected").val() == '1') {
        //         $("#price-section").hide();
        //         $("#price").val("0");
        //     } else {
        //         $("#price-section").show();
        //         $("#price").val("");
        //     }
        // }

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
