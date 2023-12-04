@extends('admin.layouts.master')
@section('title', 'Update Package')
@section('main-content')
<div class="main-content">
    <div class="card">
        <div class="card-body">
            <h4>Add Package</h4>
            <div class="m-t-50" style="">
                <form id="package" method="POST" action="{{ route('admin.package.update',$package->id) }}" 
                    enctype="multipart/form-data">
                    @csrf
                   @method('PUT')

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">Title</label> 
                            <input type="text" class="form-control" id="name" name="title" placeholder="Title" value="{{ !empty($package->title) ? $package->title :''  }}"
                                onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)"
                                maxlength="150">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="campaign"> No Of Campaign</label>
                            <input type="text" class="form-control" id="campaign" name="campaign" value="{{ !empty($package->no_of_campaign) ? $package->no_of_campaign :''  }}"
                                placeholder="No Of Campaign"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="descriptions">description</label>
                            <textarea type="text" class="form-control" id="descriptions" name="description" 
                                placeholder="description"> {{ !empty($package->description) ? $package->description :''  }} </textarea>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="inputype">Type</label>
                            <select id="inputype" name="type" class="form-control type">

                                <option value="1" {{ !empty($package->type) && $package->type == '1' ? 'selected' : '' }}>Free Trial</option>
                                <option value="2" {{ !empty($package->type) && $package->type == '2' ? 'selected' : '' }}>Monthly</option>
                                <option value="3" {{ !empty($package->type) && $package->type == '3' ? 'selected' : '' }}>Yearly</option>   
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="day" class="day_title">No Of Day</label>
                            <input type="text" class="form-control day_place" id="day" name="day" placeholder="No Of Day" value="{{ !empty($package->duration) ? $package->duration :''  }}"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="price"> Price</label>
                            <input type="text" class="form-control" id="price" name="price"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="10" value="{{ !empty($package->price) ? $package->price :''  }}"
                                placeholder="Price">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="file">Image</label>
                            <input type="file" class="form-control" name="image" id="file" accept=".png, .jpg, .jpeg"
                                onchange="previewImage()">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <img id="imagePreview" src="{{ asset("uploads/package/".$package->image) }}" alt="Image Preview"
                                style="width: 50%; height: 100px; display: block; ">
                            <button type="button" id="deleteImageButton"  class="btn btn-danger mt-2"
                                style="display: block;" onclick="deleteImage()">Delete Image</button>
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
     window.onload = () => {
    CKEDITOR.replace("description");
    };
    tinymce.init({
       selector: '#descriptions'
    });
</script>
    <script>
  
        jQuery(document).ready(function($) {
            
      
                $('#package').validate({
                    rules: {
                        title: {
                            required: true
                        },
                        campaign: {
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
                        // image: {
                        //     required: true
                        // },
                    },
                    messages: {
                        title: {
                            required: "Please Enter Package Title"
                        },
                        campaign: {
                            required: "Please Enter No Of Campaign"
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
            });
            $(document).ready(function() {
            $('.type').change(function() {

                type = $(this).val();

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
        })
        
</script>
<script>
    function previewImage() {
        var input = document.getElementById('file');
        var preview = document.getElementById('imagePreview');
        var deleteButton = document.getElementById('deleteImageButton');

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
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
</script>


@endsection
