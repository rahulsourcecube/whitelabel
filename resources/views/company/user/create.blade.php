@extends('company.layouts.master')
@section('title', 'Add User')
@section('main-content')
<div class="main-content">
    @include('company.includes.message')
    <div class="page-header">
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">
                    <i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                <a href="" class="breadcrumb-item">User</a>
                <span class="breadcrumb-item active">Add</span>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4>Add User</h4>
            <div class="m-t-50" style="">
                <form id="userform" method="POST" action="{{ route('company.user.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="fname">First Name <span class="error">*</span></label>
                            <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name"
                                maxlength="150" value="{{ old('fname') }}">
                            @error('fname')
                            <label id="fname-error" class="error" for="fname">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lname">Last Name <span class="error">*</span></label>
                            <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name"
                                maxlength="150" value="{{ old('lname') }}">
                            @error('lname')
                            <label id="lname-error" class="error" for="lname">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="number">Mobile Number <span class="error">*</span></label>
                            <input type="text" class="form-control" id="number" name="number"
                                placeholder="Mobile Number" maxlength="10" minlength="10"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                value="{{ old('number') }}">
                            @error('number')
                            <label id="number-error" class="error" for="number">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">Email Address <span class="error">*</span></label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Email Address"
                                maxlength="150" value="{{ old('email') }}">
                            @error('email')
                            <label id="email-error" class="error" for="email">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password"> Password <span class="error">*</span></label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password" value="{{ old('password') }}">
                            @error('password')
                            <label id="password-error" class="error" for="password">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password_confirmation"> Comfirm Password <span class="error">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" placeholder="Comfirm Password"
                                value="{{ old('password_confirmation') }}">
                            @error('password_confirmation')
                            <label id="password_confirmation-error" class="error" for="password_confirmation">{{
                                $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="file">Image</label>
                            <input type="file" class="form-control" name="image" id="file" accept=".png, .jpg, .jpeg"
                                onchange="previewImage()">
                            @error('image')
                            <label id="image-error" class="error" for="image">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="col-md-6 pl-5">
                            <label for="expiry_date">Status</label>
                            <div class="form-group align-items-center">
                                <div class="switch m-r-10">
                                    <input type="checkbox" id="switch-1" name="status" value="true" checked>
                                    <label for="switch-1"></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-3" style="max-height: 200px;">
                            <img id="imagePreview" src="#" alt="Image Preview"
                                style="max-width: 100%; max-height: 80%;display: none;">
                            <button type="button" id="deleteImageButton" class="btn btn-danger btn-sm mt-2"
                                style="display: none;" onclick="deleteImage()"><i class="fa fa-trash"></i></button>
                        </div>

                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    var emailCheckUrl= "{{ route('company.user.checkEmail') }}";
    var numberCheckUrl= "{{ route('company.user.checkContactNumber') }}";
    var token= "{{ csrf_token() }}";
</script>
<script src="{{ asset('assets/js/pages/company-user.js') }}"></script>
@endsection