@extends('admin.layouts.master')
@section('title', 'Country')
@section('main-content')
    <div class="main-content">
        @include('admin.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">
                        <i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <a href="{{ route('admin.location.country.list') }}" class="breadcrumb-item">Country</a>
                    <span class="breadcrumb-item active">Add</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Add Country</h4>
                <div class="m-t-50" style="">
                    <form id="country_form" method="POST" action="{{ route('admin.location.country.store') }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Name <span class="error">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}" placeholder="Country" maxlength="150">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="name">Short Name<span class="error"></span></label>
                                <input type="text" class="form-control" id="short_name" name="short_name"
                                    value="{{ old('short_name') }}" placeholder="IND" maxlength="150">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="name">Phone Code <span class="error"></span></label>
                                <input type="text" class="form-control" id="code" name="code" placeholder="+91"
                                    value="{{ !empty(old('code')) ? old('code') : '' }}" maxlength="150">
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

    <script>
        $(document).ready(function() {
            $('#country_form').validate({
                rules: {
                    name: {
                        required: true
                    },
                    code: {
                        required: true
                    }

                },
                messages: {
                    name: {
                        required: "Please enter country name"
                    },
                    code: {
                        required: "Please enter Phone code"
                    }

                }
            });

            // Custom validation method for country code
            $.validator.addMethod("validateCountryCode", function(value, element) {
                return this.optional(element) || /^\+\d+$/.test(value);
            }, "Please enter valid country code (e.g., +91)");


        });
    </script>
@endsection