@extends('company.layouts.master')
@section('title')
    @if (!empty($progression))
        Edit
    @else
        Add
    @endif
    Progression
@endsection
@section('main-content')
    <div class="main-content">
        @include('company.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <a href="{{ route('company.employee.list') }}" class="breadcrumb-item">Progression</a>
                    <span class="breadcrumb-item active">{{ !empty($progression) ? 'Edit' : 'Add' }}</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Add progression</h4>
                <div class="m-t-50" style="">
                    <form id="employeeform" method="POST" action="{{ route('company.progression.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="title">Title <span class="error">*</span></label>
                                <input type="text" class="form-control" id="title" name="title"
                                    value="{{ !empty($progression) && !empty($progression->title) ? $progression->title : '' }}"
                                    placeholder="Title" maxlength="150" value="{{ old('title') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="no_of_task">Number of task<span class="error">*</span></label>
                                <input type="text" class="form-control" id="no_of_task" name="no_of_task"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                    value="{{ !empty($progression) && !empty($progression->no_of_task) ? $progression->no_of_task : '' }}"
                                    placeholder="Number of task" maxlength="150" value="{{ old('no_of_task') }}">
                            </div>

                            <div class="form-group col-md-8">
                                <label for="file">Image<span class="error">*</span></label>
                                <input type="file" class="form-control" name="image" id="file"
                                    accept=".png, .jpg, .jpeg" onchange="previewImage()"
                                    @if (empty($progression) && empty($progression->image)) required @endif>
                                @error('image')
                                    <label id="image-error" class="error" for="image">{{ $message }}</label>
                                @enderror
                            </div>
                            @if (isset($progression))
                                <input type="hidden" name="id"
                                    value="{{ !empty($progression->id) ? base64_encode($progression->id) : '' }}">
                            @endif

                            <div class="form-group col-md-8 ckecked" style="max-height: 200px;">
                                @if (isset($progression) &&
                                        !empty($progression->image) &&
                                        file_exists(base_path('uploads/company/progression/' . $progression->image)))
                                    <img id="imagePreview"
                                        src="{{ asset('uploads/company/progression/' . $progression->image) }}"
                                        alt="Image Preview" style="max-width:80px; max-height: 80px;">
                                @else
                                    <img id="imagePreview" src="#" alt="Image Preview"
                                        style="max-width:80px; max-height: 80px; display: none;">
                                    <button type="button" id="deleteImageButton" class="btn btn-danger btn-sm mt-2"
                                        style="display: none;" onclick="deleteImage()"><i class="fa fa-trash"></i></button>
                                @endif
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
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#file').on('change', function() {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var img = new Image();
                        img.onload = function() {
                            var width = this.width;
                            var height = this.height;
                            // Define your expected dimensions
                            var expectedWidth = 400;
                            var expectedHeight = 400;
                            if (width === expectedWidth && height === expectedHeight) {
                                $('.ckecked').show();
                            } else {
                                $('.ckecked').hide();
                                $('#file').val("");
                                alert("Dimensions are not as expected. Expected: " + expectedWidth +
                                    "x" + expectedHeight + ", Actual: " + width + "x" + height);
                            }
                        };
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert("Please select a file.");
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#files').change(function() {
                var fileInput = $('#file')[0];
                var file = fileInput.files[0];
                var reader = new FileReader();

                reader.onload = function(e) {
                    var img = new Image();
                    img.src = e.target.result;

                    img.onload = function() {
                        var imageWidth = this.width;
                        var imageHeight = this.height;
                        var imageSize = file.size; // in bytes
                        var maxSize = 1024; // 5MB

                        if (imageHeight != 400 && imageSize <= maxSize) {

                            $('#imagePreview').empty();
                            $('#validationResult').html(
                                'Image dimensions or size are not valid. Please upload an image with height 400 pixels and size less than 5MB.'
                            );
                        }
                    };
                };

                reader.readAsDataURL(file);
            });
        });
        $.validator.addMethod("email", function(value, element) {
            return this.optional(element) ||
                /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i
                .test(value);
        }, "Please enter a valid email id");

        $('#employeeform').validate({
            rules: {
                title: {
                    required: true
                },
                no_of_task: {
                    required: true
                },

            },
            messages: {
                fname: {
                    required: "Please enter title"
                },
                no_of_task: {
                    required: "Please enter Number of task"
                },

            }
        });
    </script>
@endsection
