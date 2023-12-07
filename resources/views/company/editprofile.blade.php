@extends('company.layouts.master')
@section('title', 'Edit Profile')
@section('main-content')

 <!-- Content Wrapper START -->
 <div class="main-content">
    <div class="page-header no-gutters has-tab">
        <h2 class="font-weight-normal">Edit Profile</h2>
    </div>
    <div class="container">
        <div class="tab-content m-t-15">
            <div class="tab-pane fade show active" id="tab-account" >
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Basic Infomation</h4>
                    </div>
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="avatar avatar-image  m-h-10 m-r-15" style="height: 80px; width: 80px">
                                <img src="{{ asset('assets/images/avatars/thumb-3.jpg') }}" alt="">
                            </div>
                            <div class="m-l-20 m-r-20">
                                <h5 class="m-b-5 font-size-18">Marshall Nichols</h5>
                            </div>
                            <div>
                                <button class="btn btn-tone btn-primary">Upload</button>
                            </div>
                        </div>
                        <hr class="m-v-25">
                        <form>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="font-weight-semibold" for="userName">First Name:</label>
                                    <input type="text" class="form-control" id="userName" placeholder="User Name" value="Marshall Nichols">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="font-weight-semibold" for="userName">Last Name:</label>
                                    <input type="text" class="form-control" id="userName" placeholder="User Name" value="Marshall Nichols">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="font-weight-semibold" for="email">Email:</label>
                                    <input type="email" class="form-control" id="email" placeholder="email" value="">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="font-weight-semibold" for="phoneNumber">Phone Number:</label>
                                    <input type="number" min="0" class="form-control" id="phoneNumber" placeholder="Phone Number">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Change Password</h4>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label class="font-weight-semibold" for="oldPassword">Old Password:</label>
                                    <input type="password" class="form-control" id="oldPassword" placeholder="Old Password">
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="font-weight-semibold" for="newPassword">New Password:</label>
                                    <input type="password" class="form-control" id="newPassword" placeholder="New Password">
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="font-weight-semibold" for="confirmPassword">Confirm Password:</label>
                                    <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm Password">
                                </div>
                                <div class="form-group col-md-3">
                                    <button class="btn btn-primary m-t-30">Change</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Content Wrapper END -->
@endsection
