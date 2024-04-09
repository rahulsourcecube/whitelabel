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
                <h4>Add progression</h4>
                <div class="m-t-50" style="">
                    <form id="employeeform" method="POST" action="{{ route('company.progression.store') }}"  enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="title">Title <span class="error">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" value="{{!empty($progression) && !empty($progression->title)?$progression->title:"";}}"
                                    placeholder="Title" maxlength="150" value="{{ old('title') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="no_of_task"><span class="error">*</span>No of task</label>
                                <input type="text" class="form-control" id="no_of_task" name="no_of_task"   onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                   value="{{!empty($progression) && !empty($progression->no_of_task)?$progression->no_of_task:"";}}"  placeholder="No of task" maxlength="150" value="{{ old('no_of_task') }}">
                            </div>
                           
                                <div class="form-group col-md-8">
                                    <label for="file">Image<span class="error">*</span></label>
                                    <input type="file" class="form-control" name="image" id="file" 
                                        accept=".png, .jpg, .jpeg" onchange="previewImage()" @if(empty($progression) && empty($progression->image) ) required @endif>
                                    @error('image')
                                        <label id="image-error" class="error" for="image">{{ $message }}</label>
                                    @enderror
                                </div>
                                @if (isset($progression))
                                <input type="hidden" name="id" value="{{!empty($progression->id)?base64_encode($progression->id):""}}">
                                  @endif  
                                
                                    <div class="form-group col-md-8" style="max-height: 200px;">
                                        @if (isset($progression) && !empty($progression->image) && file_exists(base_path('uploads/company/progression/' . $progression->image)))
                                                <img id="imagePreview"
                                                    src="{{ asset('uploads/company/progression/' . $progression->image) }}"
                                                    alt="Image Preview" style="max-width: 100%; max-height: 80%;">
                                            @else
                                                <img id="imagePreview" src="#" alt="Image Preview"
                                                    style="max-width: 100%; max-height: 80%; display: none;">
                                                <button type="button" id="deleteImageButton" class="btn btn-danger btn-sm mt-2"
                                                    style="display: none;" onclick="deleteImage()"><i
                                                        class="fa fa-trash"></i></button>
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
                    required: "Please enter no of task"
                },
                
            }
        });
    </script>
@endsection
