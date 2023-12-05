@extends('admin.layouts.master')

@section('title', 'Setting')
@section('main-content')

<div class="main-content">
    @include('admin.includes.message')
<style>
    .remove {
        cursor: pointer;
        color: blue;
        /* Optional: Change the color to indicate it's clickable */
    }
</style>
<!-- Page Container START -->
<div class="card">
    <div class="card-body">


        <h4>Setting</h4>

        <div class="m-t-50" style="">

            <form id="" method="POST" action="{{ route('admin.setting.store',$setting->id) }}" 
                enctype="multipart/form-data">
                @csrf

                <div class="form-row">
                <div class="form-group col-md-6">

                    <label for="title">Title</label>
                    <input type="text" class="form-control mb-2" name="title" id="title" placeholder="Title" value="{{$setting->title }}">

                </div>
                <div class="form-group col-md-6">

                    <label for="email">Email</label>
                    <input type="Email" class="form-control mb-2" name="email" id="email" placeholder="Email" value="{{$setting->email }}">

                </div>
                <div class="form-group col-md-6">

                    <label for="contact">Contact Number</label>
                    <input type="text" class="form-control mb-2" name="contact_no" id="contact" placeholder="Contact Number" value="{{$setting->contact_number }}">

                </div>
                <div class="form-group col-md-4">

                    <label for="flink">Facebook Link</label>
                    <input type="text" class="form-control mb-2" name="flink" id="flink" placeholder="Facebook Link" value="{{$setting->facebook_link }}">

                </div>
                <div class="form-group col-md-4">

                    <label for="t_link">Twitter Link</label>
                    <input type="text" class="form-control mb-2" name="t_link" id="t_link" placeholder="Twitter Link" value="{{$setting->twitter_link }}">

                </div>
                <div class="form-group col-md-4">

                    <label for="l_link">linkedin Link</label>
                    <input type="text" class="form-control mb-2" name="l_link" id="l_link" placeholder="linkedin Link" value="{{$setting->linkedin_link }}">

                </div>

                    {{-- <div class="form-group col-md-6">
                        <label for="leader_image">Logo</label>                        
                        <input type="file" class="form-control" name="logo" id="file" accept=".png, .jpg, .jpeg"
                            onchange="previewImage()">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <img id="imagePreview" src="{{ asset("uploads/setting/".$setting->logo) }}" alt="Image Preview"
                                        style="width: 50%; height: 100px; display: block; ">
                                    <button type="button" id="deleteImageButton"  class="btn btn-danger mt-2"
                                        style="display: block;" onclick="deleteImage()">Delete Image</button>
                                </div>
                            </div>
                    </div> --}}
                    <div class="form-group col-md-6">
                        <label for="leader_image">Logo</label>
                        <input type="file" class="form-control" name="logo" id="logofiles" accept=".png, .jpg, .jpeg">
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <img id="logoimagePreviews" src="{{ asset("uploads/setting/".$setting->logo) }}" alt="Image Preview" style="width: 50%; height: 100px; display: block;">
                                <button type="button" id="logodeleteImageButtons" class="btn btn-danger mt-2" style="display: block;">Delete Image</button>
                            </div>
                        </div>
                    </div>
                   
                  
                            <div class="form-group col-md-6">
                                <label for="leader_image">Favicon</label>
                                <input type="file" class="form-control" name="favicon_img" id="files" accept=".png, .jpg, .jpeg">
                                
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <img id="imagePreviews" src="{{ asset("uploads/setting/".$setting->favicon) }}" alt="Image Preview" style="width: 50%; height: 100px; display: block;">
                                        <button type="button" id="deleteImageButtons" class="btn btn-danger mt-2" style="display: block;">Delete Image</button>
                                    </div>
                                </div>
                            </div>

                    {{-- <div class="form-row">
                        <div class="form-group col-md-6">
                            <img id="imagePreview" src="{{ asset("uploads/setting/".$setting->favicon) }}" alt="Image Preview"
                                style="width: 50%; height: 100px; display: block; ">
                            <button type="button" id="faviconImage"  class="btn btn-danger mt-2"
                                style="display: block;" onclick="faviconImage()">Delete Image</button>
                        </div>
                    </div> --}}
                    

                </div>

                <button  class="btn btn-primary">Submit</button>
            </form>
        </div>

    </div>
</div>
</div>



   
<script>
//     function previewImage() {
//         var input = document.getElementById('file');
//         var preview = document.getElementById('imagePreview');
//         var deleteButton = document.getElementById('deleteImageButton');

//         if (input.files && input.files[0]) {
//             var reader = new FileReader();

//             reader.onload = function (e) {
//                 preview.src = e.target.result;
//                 preview.style.display = 'block';
//                 deleteButton.style.display = 'block';
//             };

//             reader.readAsDataURL(input.files[0]);
//         } else {
//             preview.src = '#';
//             preview.style.display = 'none';
//             deleteButton.style.display = 'none';
//         }
//     }

//     function deleteImage() {
//         var input = document.getElementById('file');
//         var preview = document.getElementById('imagePreview');
//         var deleteButton = document.getElementById('deleteImageButton');

//         input.value = ''; // Clear the file input
//         preview.src = '#';
//         preview.style.display = 'none';
//         deleteButton.style.display = 'none';
//     }
// </script>
<script>
    // Function to preview image
    $("#files").change(function () {
        var input = this;
        var imagePreview = $("#imagePreviews")[0];
        var deleteButton = $("#deleteImageButtons");

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(imagePreview).attr("src", e.target.result);
                $(imagePreview).show();
                $(deleteButton).show();
            };

            reader.readAsDataURL(input.files[0]);
        }
    });

    // Function to delete image
    $("#deleteImageButtons").click(function () {
        var confirmation = confirm("Are you sure you want to delete the image?");
        
        if (confirmation) {
            $("#files").val(""); // Clear the file input
            $("#imagePreviews").attr("src", "").hide(); // Clear the image preview and hide it
            $(this).hide(); // Hide the delete button
        }
    });
</script>
<script>
    // Function to preview image
    $("#logofiles").change(function () {
        var input = this;
        var imagePreview = $("#logoimagePreviews")[0];
        var deleteButton = $("#logodeleteImageButtons");

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(imagePreview).attr("src", e.target.result);
                $(imagePreview).show();
                $(deleteButton).show();
            };

            reader.readAsDataURL(input.files[0]);
        }
    });

    // Function to delete image
    $("#logodeleteImageButtons").click(function () {
        var confirmation = confirm("Are you sure you want to delete the image?");
        
        if (confirmation) {
            $("#logofiles").val(""); // Clear the file input
            $("#logoimagePreviews").attr("src", "").hide(); // Clear the image preview and hide it
            $(this).hide(); // Hide the delete button
        }
    });
</script>


@endsection
