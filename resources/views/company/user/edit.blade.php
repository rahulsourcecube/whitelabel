@extends('company.layouts.master')
@section('title', 'Edit User')
@section('main-content')
<div class="main-content">
    @include('company.includes.message')
    <div class="page-header">
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">
                    <i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                <a href="" class="breadcrumb-item">User</a>
                <span class="breadcrumb-item active">Update</span>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4>Update User</h4>
            <div class="m-t-50" style="">
                <form id="userUpdateform" method="POST"
                    action="{{ route('company.user.update', base64_encode($user->id)) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="fname">First Name <span class="error">*</span></label>
                            <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name"
                                maxlength="150" value="{{ isset($user) ? $user->first_name : '' }}">
                            @error('fname')
                            <label id="fname-error" class="error" for="fname">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lname">Last Name <span class="error">*</span></label>
                            <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name"
                                maxlength="150" value="{{ isset($user) ? $user->last_name : '' }}">
                            @error('lname')
                            <label id="lname-error" class="error" for="lname">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="number">Mobile Number <span class="error">*</span></label>
                            <input type="text" class="form-control" id="number" name="number"
                                placeholder="Mobile Number" maxlength="10" minlength="10"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                value="{{ isset($user) ? $user->contact_number : '' }}">
                            @error('number')
                            <label id="number-error" class="error" for="number">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">Email Address <span class="error">*</span></label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Email Address"
                                maxlength="150" value="{{ isset($user) ? $user->email : '' }}">
                            @error('email')
                            <label id="email-error" class="error" for="email">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password"> Password <span class="error">*</span></label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password" value="">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password_confirmation"> Comfirm Password <span class="error">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" placeholder="Comfirm Password" value="">
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
                                    <input type="checkbox" id="switch-1" name="status" value="true" @if (isset(
                                        $user->status) && $user->status == 1 ) checked="" @endif>
                                    <label for="switch-1"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-3" style="max-height: 200px;">
                            @if (isset($user) && !empty($user->profile_image) &&
                            file_exists('uploads/company/user-profile/' . $user->profile_image))
                            <img id="imagePreview"
                                src="{{ asset('uploads/company/user-profile/' . $user->profile_image) }}"
                                alt="Image Preview" style="max-width: 100%; max-height: 80%;">
                            @else
                            <img id="imagePreview" src="#" alt="Image Preview"
                                style="max-width: 100%; max-height: 80%; display: none;">
                            <button type="button" id="deleteImageButton" class="btn btn-danger btn-sm mt-2"
                                style="display: none;" onclick="deleteImage()"><i class="fa fa-trash"></i></button>
                            @endif
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group col-md-6">
                                <label for="f_link">Facebook Link</label>
                                <input type="url" class="form-control" name="facebook_link" id="f_link"
                                    placeholder="Facebook Link" value="{{ isset($user) ? $user->facebook_link : '' }}">
                                @error('facebook_link')
                                <label id="f_link-error" class="error" for="f_link">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="i_link">Instagram Link</label>
                                <input type="url" class="form-control" name="instagram_link" id="i_link"
                                    placeholder="Instagram Link"
                                    value="{{ isset($user) ? $user->instagram_link : '' }}">
                                @error('instagram_link')
                                <label id="i_link-error" class="error" for="i_link">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="t_link">Twitter Link</label>
                                <input type="url" class="form-control" name="twitter_link" id="t_link"
                                    placeholder="Twitter Link" value="{{ isset($user) ? $user->twitter_link : '' }}">
                                @error('twitter_link')
                                <label id="t_link-error" class="error" for="t_link">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="y_link">Youtube Link</label>
                                <input type="url" class="form-control" name="youtube_link" id="y_link"
                                    placeholder="Youtube Link" value="{{ isset($user) ? $user->youtube_link : '' }}">
                                @error('youtube_link')
                                <label id="y_link-error" class="error" for="y_link">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group col-md-6">
                                    <label class="font-weight-semibold" for="paypal_id">Paypal Id</label>
                                    <input type="text" class="form-control" id="paypal_id" placeholder="Paypal Id"
                                        name="paypal_id" value="{{ isset($user) ? $user->paypal_id : '' }}">
                                    @error('paypal_id')
                                        <label id="paypal_id-error" class="error"
                                            for="paypal_id">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="font-weight-semibold" for="stripe_id">Stripe Id</label>
                                    <input type="text" class="form-control" id="stripe_id" placeholder="Stripe Id"
                                        name="stripe_id" value="{{ isset($user) ? $user->stripe_id : '' }}">
                                    @error('stripe_id')
                                        <label id="stripe_id-error" class="error"
                                            for="stripe_id">{{ $message }}</label>
                                    @enderror
                                </div>
                            <div class="form-group col-md-6">
                                <label for="bank_name">Bank Name</label>
                                <input type="text" class="form-control" name="bank_name" id="bank_name"
                                    placeholder="Bank Name" value="{{ isset($user) ? $user->bank_name : '' }}">
                                @error('bank_name')
                                <label id="bank_name-error" class="error" for="bank_name">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="ac_holder">Account Holder</label>
                                <input type="text" class="form-control" name="ac_holder" id="ac_holder"
                                    placeholder="Account Holder" value="{{ isset($user) ? $user->ac_holder : '' }}">
                                @error('ac_holder')
                                <label id="ac_holder-error" class="error" for="ac_holder">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="ifsc_code">IFSC Code</label>
                                <input type="text" class="form-control" name="ifsc_code" id="ifsc_code"
                                    placeholder="IFSC Code" value="{{ isset($user) ? $user->ifsc_code : '' }}">
                                @error('ifsc_code')
                                <label id="ifsc_code-error" class="error" for="ifsc_code">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="ac_no">Account No</label>
                                <input type="text" class="form-control" name="ac_no" id="ac_no" maxlength="11"
                                    minlength="11" placeholder="Account No"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                    value="{{ isset($user) ? $user->ac_no : '' }}">
                                @error('ac_no')
                                <label id="ac_no-error" class="error" for="ac_no">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('assets/js/pages/company-user.js') }}"></script>
@endsection