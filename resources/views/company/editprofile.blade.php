@extends('company.layouts.master')
@section('title', 'Edit Profile')
@section('main-content')
<style>
    .image {
        /* display: none; */
    }
</style>
<!-- Content Wrapper START -->
<div class="main-content">
    {{-- <div class="page-header no-gutters has-tab">
        <h2 class="font-weight-normal">Edit Profile</h2>
    </div> --}}
    <div class="page-header">
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a href="{{ route('company.dashboard') }}" class="breadcrumb-item"><i
                        class="anticon anticon-home m-r-5"></i>Dashboard</a>
                {{-- <a class="breadcrumb-item" href="#">Edit Profile</a> --}}
                <span class="breadcrumb-item active">Edit Profile</span>
            </nav>
        </div>
    </div>
    <div class="container">
        <div class="tab-content m-t-15">
            <div class="tab-pane fade show active" id="tab-account">
                <form action="{{route('company.update_profile', $editprofiledetail->id)}}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Basic Infomation</h4>
                        </div>
                        <div class="card-body">
                            <div class="media align-items-center">
                                <div class="avatar avatar-image  m-h-10 m-r-15" style="height: 80px; width: 80px">
                                    @if (isset($editprofiledetail) && $editprofiledetail->profile_image == '')
                                    <img src="{{ asset('assets/images/avatars/thumb-3.jpg') }}" alt="">
                                    @else
                                    <img src="{{ asset('uploads/user-profile/'.$editprofiledetail->profile_image) }}"
                                        alt="" id="imagePreviews">
                                    @endif
                                </div>
                                <!-- <button class="btn btn-tone btn-primary" onclick="getimage()">Upload</button> -->
                                <div class="m-l-20 m-r-20">
                                    <h5 class="m-b-5 font-size-18">
                                        {{isset($editprofiledetail->first_name)?$editprofiledetail->first_name:''}}
                                        {{isset($editprofiledetail->last_name)?$editprofiledetail->last_name:''}}</h5>
                                </div>
                                <!-- <button class="btn btn-primary">Upload</button> -->
                                <div class="image">
                                    <input type="file" name="profile_image" id="profile_image">
                                </div>
                            </div>
                            <hr class="m-v-25">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="font-weight-semibold" for="userName">First Name:</label>
                                    <input type="text" class="form-control" name="first_name" id="userName"
                                        placeholder="User Name"
                                        value="{{isset($editprofiledetail->first_name)?$editprofiledetail->first_name:''}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="font-weight-semibold" for="userName">Last Name:</label>
                                    <input type="text" class="form-control" name="last_name" id="userName"
                                        placeholder="User Name"
                                        value="{{isset($editprofiledetail->last_name)?$editprofiledetail->last_name:''}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="font-weight-semibold" for="email">Email:</label>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="email"
                                        value="{{isset($editprofiledetail->email)?$editprofiledetail->email:''}}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="font-weight-semibold" for="phoneNumber">Phone Number:</label>
                                    <input type="number" class="form-control" name="contact_number" id="phoneNumber"
                                        placeholder="Phone Number"
                                        value="{{isset($editprofiledetail->contact_number)?$editprofiledetail->contact_number:''}}">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-tone btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
                <form action="{{route('company.update_password')}}" id="change_password" method="post">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Change Password</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                {{-- <div class="form-group col-md-3">
                                    <label class="font-weight-semibold" for="oldPassword">Old Password:</label>
                                    <input type="password" class="form-control" name="oldpassword" id="oldPassword"
                                        placeholder="Old Password">
                                </div> --}}
                                <div class="form-group col-md-3">
                                    <label class="font-weight-semibold" for="newPassword">New Password:</label>
                                    <input type="password" class="form-control" name="newPassword" id="newPassword"
                                        placeholder="New Password">
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="font-weight-semibold" for="confirmPassword">Confirm Password:</label>
                                    <input type="password" class="form-control" name="confirmPassword"
                                        id="confirmPassword" placeholder="Confirm Password">
                                </div>
                                <div class="form-group col-md-3">
                                    <button type="submit" class="btn btn-primary m-t-30">Change</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Content Wrapper END -->
@endsection
@section('js')
<script>
    $(function() {
        $('#imagePreviews').on('click', function() {
            $('profile_image').trigger('click');
        });
    });
    $("#profile_image").change(function() {
        var input = this;
        var imagePreview = $("#imagePreviews")[0];
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(imagePreview).attr("src", e.target.result);
                $(imagePreview).show();
                $(deleteButton).show();
            };
            reader.readAsDataURL(input.files[0]);
        }
    });
    $(document).ready(function() {
        $('#change_password').validate({
            rules: {
                oldpassword:{
                    required:true
                },
                newPassword: {
                    minlength: 6,
                    maxlength: 15
                },
                confirmPassword: {
                    equalTo: '#newPassword'
                },
            },
            messages: {
                oldpassword:{
                    required:"Please enter old password"
                },
                newPassword: {
                    minlength: 'Please enter at least 6 cheracter.',
                    maxlength: 'Maximum password length 15 character.'
                },
                confirmPassword: {
                    equalTo: "The password you entered does not match.",
                },
            },
        });
    });
</script>
@endsection