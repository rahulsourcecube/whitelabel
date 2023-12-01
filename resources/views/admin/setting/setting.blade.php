@extends('admin.layouts.master')

@section('title', 'Setting')
@section('main-content')

<div class="main-content">
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

            <form action="" id="whoWeAreForm">

                <div class="form-row">
                <div class="form-group col-md-6">

                    <label for="title">Title</label>
                    <input type="text" class="form-control mb-2" name="title" id="title" placeholder="Title" value="{{ old('title') }}">

                </div>
                <div class="form-group col-md-6">

                    <label for="title">Email</label>
                    <input type="Email" class="form-control mb-2" name="title" id="title" placeholder="Email" value="{{ old('title') }}">

                </div>
                <div class="form-group col-md-6">

                    <label for="title">Contact Number</label>
                    <input type="Email" class="form-control mb-2" name="title" id="title" placeholder="Contact Number" value="{{ old('title') }}">

                </div>
                <div class="form-group col-md-4">

                    <label for="flink">Facebook Link</label>
                    <input type="text" class="form-control mb-2" name="flink" id="flink" placeholder="Facebook Link" value="{{ old('flink') }}">

                </div>
                <div class="form-group col-md-4">

                    <label for="t_link">Twitter Link</label>
                    <input type="text" class="form-control mb-2" name="t_link" id="t_link" placeholder="Twitter Link" value="{{ old('t_link') }}">

                </div>
                <div class="form-group col-md-4">

                    <label for="l_link">linkedin Link</label>
                    <input type="text" class="form-control mb-2" name="l_link" id="l_link" placeholder="Jane Doe" value="{{ old('l_link') }}">

                </div>

                    <div class="form-group col-md-6">
                        <label for="leader_image">Logo</label>
                        <input type="file" class="form-control leader_img files" name="leader_img" id="files" placeholder="File" value="old{'leader_img'}">
                        <!-- <input type="file" id="files" name="profile_image[]" multiple /> -->


                    </div>
                    <div class="form-group col-md-6">
                        <label for="leader_image">Favicon</label>
                        <input type="file" class="form-control favicon_img favicon" name="favicon_img" id="" placeholder="File" value="old{'leader_img'}">
                        <!-- <input type="file" id="files" name="profile_image[]" multiple /> -->


                    </div>

                </div>

                <button  class="btn btn-primary">Submit</button>
            </form>
        </div>

    </div>
</div>
</div>


<script>
    $(document).ready(function() {
        $.validator.addMethod("imageFile", function(value, element) {
            // Check if the file extension is one of these
            return this.optional(element) || /\.(jpg|jpeg|png|gif)$/i.test(value);
        }, "Please select a valid image file (jpg, jpeg, png, gif)");
        $("#whoWeAreForm").validate({
            ignore: [],
            rules: {
                leader_img: {
                    required: true,
                    imageFile: true,
                },
                first_name: {
                    required: true,
                },
                last_name: {
                    required: true,
                },
                desigtion: {
                    required: true,
                },
                description: {
                    required: true,
                },
                facebook: {
                    required: true,
                    // url:true,
                },
                instagram: {
                    required: true,
                    // url:true,
                },
                twitter: {
                    required: true,
                    // url:true,
                },
                linkedin: {
                    required: true,
                    // url:true,
                },
                'img[]': {
                    required: true,
                    imageFile: true,
                },

            },
            messages: {
                email: {
                    leader_img: "Please Select Leader Image",
                    accept: "Only image files are allowed."
                },
                first_name: {
                    required: "Please Enter First Name"
                },
                last_name: {
                    required: "Please Enter Last Name"
                },
                desigtion: {
                    required: "Please Enter Designation"
                },
                description: {
                    required: "Please Enter Description"
                },
                facebook: {
                    required: "Please Enter Facebook URL",
                    // url: "Please enter a valid Facebook URL"
                },
                instagram: {
                    required: "Please Enter Instagram URL",
                    // url: "Please enter a valid Instagram URL"
                },
                twitter: {
                    required: "Please Enter Twitter URL",
                    // url: "Please enter a valid Twitter URL"
                },
                linkedin: {
                    required: "Please Enter Linkedin URL",
                    // url: "Please enter a valid LinkedIn URL"
                },
                'img[]': {
                    required: "Please Select Images",
                    accept: "Only image files are allowed."
                },
            }
        });
    });
    $(document).ready(function() {
        if (window.File && window.FileList && window.FileReader) {
            $(".files").on("change", function(e) {
                var files = e.target.files,
                    filesLength = files.length;
                for (var i = 0; i < filesLength; i++) {
                    var f = files[i]
                    var fileReader = new FileReader();
                    fileReader.onload = (function(e) {
                        var file = e.target;
                        $('.pip').remove();
                        $("<span class=\"pip\">" +
                            "<img style='height: 150px; width: 190px;' class=\"imageThumb\" src=\"" +
                            e.target.result + "\" title=\"" + file.name + "\"/>" +
                            "<br/><span class=\"remove\" style='font-size:16px;color:red'>Remove image</span>" +
                            "</span>").insertAfter("#files");
                        $(".remove").click(function() {
                            $(this).parent(".pip").remove();
                            $('.leader_img').val('');
                        });

                    });
                    fileReader.readAsDataURL(f);
                }
                console.log(files);
            });
            $(".favicon").on("change", function(e) {

                var files = e.target.files,
                    filesLength = files.length;
                for (var i = 0; i < filesLength; i++) {
                    var f = files[i]
                    var fileReader = new FileReader();
                    fileReader.onload = (function(e) {
                        var file = e.target;
                        $('.pips').remove();
                        $("<span class=\"pips\">" +
                            "<img style='height: 150px; width: 190px;' class=\"imageThumb\" src=\"" +
                            e.target.result + "\" title=\"" + file.name + "\"/>" +
                            "<br/><span class=\"removes\" style='font-size:16px;color:red'>Remove image</span>" +
                            "</span>").insertAfter(".favicon");
                        $(".removes").click(function() {
                            $(this).parent(".pips").remove();
                            $('.favicon_img').val('');
                        });

                    });
                    fileReader.readAsDataURL(f);
                }
                console.log(files);
            });
        }
    });

    $(document).ready(function() {
        $('.file').on('change', function(e) {
            var files = e.target.files;

            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var reader = new FileReader();

                reader.onload = function(e) {
                    var previewHtml = `
				<div class="preview-item col-md-2">
					<img style='height: 150px; width: 190px;' class="preview-image" src="${e.target.result}" alt="Preview">
					<i class="fa fa-close delete-button" style="font-size:25px;color:red"></i>
				</div>
				`;

                    $('#who_we_are_preview_container').append(previewHtml);
                };

                reader.readAsDataURL(file);
            }
        });
        $('.favicon').on('change', function(e) {
            var files = e.target.files;

            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var reader = new FileReader();

                reader.onload = function(e) {
                    var previewHtml = `
				<div class="preview-item col-md-2">
					<img style='height: 150px; width: 190px;' class="preview-image" src="${e.target.result}" alt="Preview">
					<i class="fa fa-close delete-button" style="font-size:25px;color:red"></i>
				</div>
				`;

                    $('#who_we_are_preview_container').append(previewHtml);
                };

                reader.readAsDataURL(file);
            }
        });

        $('#who_we_are_preview_container').on('click', '.delete-button', function() {
            $(this).parent('.preview-item').remove();
            $('.imgs').val('');

        });
    });
</script>
@endsection
