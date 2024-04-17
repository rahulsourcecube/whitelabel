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
                    <form id="userform" method="POST" action="{{ route('company.user.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="fname">First Name <span class="error">*</span></label>
                                <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name" maxlength="150"
                                    value="{{ old('fname') }}">
                                @error('fname')
                                    <label id="fname-error" class="error" for="fname">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="lname">Last Name <span class="error">*</span></label>
                                <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name" maxlength="150"
                                    value="{{ old('lname') }}">
                                @error('lname')
                                    <label id="lname-error" class="error" for="lname">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="number">Mobile Number <span class="error">*</span></label>
                                <input type="number" min="0" class="form-control" id="number" name="number" placeholder="Mobile Number" maxlength="10"
                                    minlength="10" onkeypress="return event.charCode >= 48 && event.charCode <= 57" value="{{ old('number') }}">
                                @error('number')
                                    <label id="number-error" class="error" for="number">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">Email Address <span class="error">*</span></label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Email Address" maxlength="150"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <label id="email-error" class="error" for="email">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password"> Password <span class="error">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="{{ old('password') }}">
                                @error('password')
                                    <label id="password-error" class="error" for="password">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password_confirmation"> Confirm Password <span class="error">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password"
                                    value="{{ old('password_confirmation') }}">
                                @error('password_confirmation')
                                    <label id="password_confirmation-error" class="error" for="password_confirmation">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-weight-semibold" for="country">Country:</label>
                                <select name="country" id="country" class="form-control">
                                    <option value="">Select Country</option>
                                    @if ($country_data)
                                        @foreach ($country_data as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach

                                    @endif
                                </select>

                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-weight-semibold" for="state">State:</label>
                                <select name="state" id="state" class="form-control">
                                    <option value="">Select State</option>
                                </select>

                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-weight-semibold" for="city">City:</label>
                                <select name="city" id="city" class="form-control">
                                    <option value="">Select City</option>
                                </select>

                            </div>

                            <div class="form-group col-md-6">
                                <label for="file">Image</label>
                                <input type="file" class="form-control" name="image" id="file" accept=".png, .jpg, .jpeg" onchange="previewImage()">
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
                                <img id="imagePreview" src="#" alt="Image Preview" style="max-width: 100%; max-height: 80%;display: none;">
                                <button type="button" id="deleteImageButton" class="btn btn-danger btn-sm mt-2" style="display: none;"
                                    onclick="deleteImage()"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-group col-md-6">
                                    <label for="flink">Facebook Link</label>
                                    <input type="url" class="form-control" name="facebook_link" id="flink" placeholder="Facebook Link"
                                        value="{{ old('facebook_link') }}">
                                    @error('facebook_link')
                                        <label id="flink-error" class="error" for="flink">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="ilink">Instagram Link</label>
                                    <input type="url" class="form-control" name="instagram_link" id="ilink" placeholder="Instagram Link"
                                        value="{{ old('instagram_link') }}">
                                    @error('instagram_link')
                                        <label id="ilink-error" class="error" for="ilink">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="tlink">Twitter Link</label>
                                    <input type="url" class="form-control" value="{{ old('twitter_link') }}" name="twitter_link" id="tlink"
                                        placeholder="Twitter Link">
                                    @error('twitter_link')
                                        <label id="tlink-error" class="error" for="tlink">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="ylink">Youtube Link</label>
                                    <input type="url" class="form-control" value="{{ old('youtube_link') }}" name="youtube_link" id="ylink"
                                        placeholder="Youtube Link">
                                    @error('youtube_link')
                                        <label id="ylink-error" class="error" for="ylink">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group col-md-6">
                                    <label class="font-weight-semibold" for="paypal_id">Paypal Id</label>
                                    <input type="text" class="form-control" value="{{ old('paypal_id') }}" id="paypal_id" placeholder="Paypal Id"
                                        name="paypal_id">
                                    @error('paypal_id')
                                        <label id="paypal_id-error" class="error" for="paypal_id">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="font-weight-semibold" for="stripe_id">Stripe Id</label>
                                    <input type="text" class="form-control" value="{{ old('stripe_id') }}" id="stripe_id" placeholder="Stripe Id"
                                        name="stripe_id">
                                    @error('stripe_id')
                                        <label id="stripe_id-error" class="error" for="stripe_id">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="bank_name">Bank Name</label>
                                    <input type="text" class="form-control" value="{{ old('bank_name') }}" name="bank_name" id="bank_name"
                                        placeholder="Bank Name">
                                    @error('bank_name')
                                        <label id="bank_name-error" class="error" for="bank_name">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="ac_holder">Account Holder</label>
                                    <input type="text" class="form-control" value="{{ old('ac_holder') }}" name="ac_holder" id="ac_holder"
                                        placeholder="Account Holder">
                                    @error('ac_holder')
                                        <label id="ac_holder-error" class="error" for="ac_holder">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="ifsc_code">IFSC Code</label>
                                    <input type="text" class="form-control" value="{{ old('ifsc_code') }}" name="ifsc_code" id="ifsc_code"
                                        placeholder="IFSC Code">
                                    @error('ifsc_code')
                                        <label id="ifsc_code-error" class="error" for="ifsc_code">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="ac_no">Account No</label>
                                    <input type="text" class="form-control" name="ac_no" id="ac_no" maxlength="11" minlength="11"
                                        placeholder="Account No" value="{{ old('ac_no') }}" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                    @error('ac_no')
                                        <label id="ac_no-error" class="error" for="ac_no">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary" id="addUser">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        var emailCheckUrl = "{{ route('company.user.checkEmail') }}";
        var numberCheckUrl = "{{ route('company.user.checkContactNumber') }}";
        var token = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('assets/js/pages/company-user.js?v=' . time()) }}"></script>
   
@endsection

