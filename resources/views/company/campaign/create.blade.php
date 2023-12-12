@extends('company.layouts.master')
@section('title', 'Add Task')
@section('main-content')
    <div class="main-content">
        @include('company.includes.message')
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
                    <form id="taskadd" method="POST"
                        action="{{ route('company.campaign.store') }}"enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="title">Task Titles <span class="error">*</span></label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Task Title" maxlength="150" value="{{ old('title') }}">
                                @error('title')
                                    <label id="title-error" class="error" for="title">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="reward"> Reward <span class="error">*</span></label>
                                <input type="text" class="form-control" id="reward" name="reward"
                                    placeholder="Reward" onkeypress="return event.charCode >= 48 && event.charCode <= 57" value="{{ old('reward') }}">
                                @error('reward')
                                    <label id="reward-error" class="error" for="reward">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="description">Description</label>
                                <textarea type="text" class="form-control" id="description" name="description" placeholder="description">{{ old('description') }}</textarea>
                                @error('description')
                                    <label id="description-error" class="error" for="description">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="expiry_date">End date <span class="error">*</span></label>
                                <input type="date" class="form-control" id="expiry_date" name="expiry_date"
                                    placeholder="No Of Task"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57" value="{{ old('expiry_date') }}">
                                @error('expiry_date')
                                    <label id="expiry_date-error" class="error" for="expiry_date">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="type">Task Type</label>
                                <select id="type" class="form-control" name="type">
                                    @if (isset($type))
                                        <option value="{{ $type }}" selected>
                                            {{ \App\Helpers\Helper::taskType($type) }}</option>
                                    @else
                                        <option value="">Select</option>
                                        <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Referral</option>
                                        <option value="2" {{ old('type') == '2' ? 'selected' : '' }}>Custom</option>
                                        <option value="3" {{ old('type') == '3' ? 'selected' : '' }}>Social</option>
                                    @endif
                                </select>
                                @error('type')
                                    <label id="type-error" class="error" for="type">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="file">Image <span class="error">*</span></label>
                                <input type="file" class="form-control" name="image" id="file"
                                    accept=".png, .jpg, .jpeg" onchange="previewImage()">
                                @error('image')
                                    <label id="image-error" class="error" for="image">{{ $message }}</label>
                                @enderror
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
@endsection
@section('js')
    <script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script>
        $(document).ready(function() {
            window.onload = () => {
                CKEDITOR.replace("description");
            };
        });
        $('#taskadd').validate({
            rules: {
                title: {
                    required: true
                },
                reward: {
                    required: true
                },
                description: {
                    required: true
                },
                type: {
                    required: true
                },
                expiry_date: {
                    required: true
                },
                image: {
                    fileExtension: true,
                    fileSize: true,
                },
            },
            messages: {
                title: {
                    required: "Please enter title"
                },
                reward: {
                    required: "Please enter reward"
                },
                description: {
                    required: "Please enter description"
                },
                type: {
                    required: "Please select task type"
                },
                expiry_date: {
                    required: "Please select end date"
                },
            }
        });
    </script>
@endsection
