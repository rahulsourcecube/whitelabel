@extends('admin.layouts.master')
@section('title', 'Edit Country')
@section('main-content')
    <div class="main-content">
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">
                        <i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <a href="{{ route('admin.location.country.list') }}" class="breadcrumb-item">Country</a>
                    <span class="breadcrumb-item active">Edit</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Edit Country</h4>
                <div class="m-t-50" style="">

                    <form action="{{ route('admin.location.state.update', ['state' => $state->id]) }}" method="post" enctype="multipart/form-data"
                        id="state_update">
                        @csrf
                        @method('PUT')

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputype">Country <span class="error">*</span></label>
                                <select id="country" name="country" class="form-control inputype">
                                    <option value="">Select Country</option>

                                    @if ($country)
                                        @foreach ($country as $data)
                                            <option value="{{ $data->id }}" {{ $state->country_id == $data->id ? 'selected' : '' }}>
                                                {{ $data->name }}
                                            </option>
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="name">State <span class="error">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="State" maxlength="150"
                                    value="{{ !empty($state->name) ? $state->name : '' }}">
                            </div>

                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3" style="max-height: 200px;">
                                <img id="imagePreview" src="#" alt="Image Preview" style="max-width: 100%; max-height: 80%;display: none;">
                                <button type="button" id="deleteImageButton" class="btn btn-danger btn-sm mt-2" style="display: none;" onclick="deleteImage()"><i
                                        class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
                                                                                                                                                                                                                                -->

    <script>
        $(document).ready(function() {


            $('#state_update').validate({
                // alert('etger');
                rules: {
                    country: {
                        required: true
                    },
                    // campaign: {
                    //     required: true
                    // },
                    // employee: {
                    //     required: true
                    // },
                    // user: {
                    //     required: true
                    // },
                    // description: {
                    //     required: true
                    // },
                    // type: {
                    //     required: true
                    // },
                    // day: {
                    //     required: true
                    // },
                    // price: {
                    //     required: true
                    // },
                    // image: {
                    //     required: true,
                    //     maxfilesize: 1024 *
                    //         1024, // Specify the maximum file size in bytes (1MB in this example)
                    //     extension: "png|jpg|jpeg" // Specify the allowed file extensions
                    // },
                },
                messages: {
                    counrtry: {
                        required: "Please enter country"
                    },
                    // campaign: {
                    //     required: "Please enter no of campaign"
                    // },
                    // employee: {
                    //     required: "Please enter no of employee"
                    // },
                    // user: {
                    //     required: "Please enter no of user"
                    // },
                    // description: {
                    //     required: "Please enter description"
                    // },
                    // day: {
                    //     required: "This field is required"
                    // },
                    // price: {
                    //     required: "Please enter price"
                    // },
                    // image: {
                    //     required: "Please select an image",
                    //     maxfilesize: "File size must be less than 1MB",
                    //     extension: "Only PNG, JPG, and JPEG files are allowed"
                    // },
                }
            });

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

        // function previewImage() {
        //     var input = document.getElementById('file');
        //     var preview = document.getElementById('imagePreview');
        //     var deleteButton = document.getElementById('deleteImageButton');

        //     if (input.files && input.files[0]) {
        //         var reader = new FileReader();

        //         reader.onload = function(e) {
        //             preview.src = e.target.result;
        //             preview.style.display = 'block';
        //             deleteButton.style.display = 'block';
        //         };

        //         reader.readAsDataURL(input.files[0]);
        //     } else {
        //         preview.src = '#';
        //         preview.style.display = 'none';
        //         deleteButton.style.display = 'none';
        //     }
        // }

        // function deleteImage() {
        //     var input = document.getElementById('file');
        //     var preview = document.getElementById('imagePreview');
        //     var deleteButton = document.getElementById('deleteImageButton');

        //     input.value = ''; // Clear the file input
        //     preview.src = '#';
        //     preview.style.display = 'none';
        //     deleteButton.style.display = 'none';
        // }
        // $(document).ready(function() {
        //     window.onload = () => {
        //         CKEDITOR.replace("description");
        //     };
        // });
    </script>
@endsection
