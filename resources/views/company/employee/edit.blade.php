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
            <h4>Add Employee</h4>
            <div class="m-t-50" style="">

                <form id="employeeform" method="POST" action="{{ route('company.employee.update', base64_encode($user->id)) }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="fname">First Name <span class="error">*</span></label>
                            <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name"
                                maxlength="150" value="{{isset($user->first_name) ? $user->first_name : '' }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lname">Last Name <span class="error">*</span></label>
                            <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name"
                                maxlength="150" value="{{isset($user->last_name) ? $user->last_name : '' }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="number">Email Address <span class="error">*</span></label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Email Address" value="{{isset($user->email) ? $user->email : '' }}">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="Type">Role</label>
                            <select id="Type" class="form-control" name="role">
                                <option value="" >Select</option>
                                <option value="1" >Staff</option>
                                <option value="2" >Manager</option>
                                <option value="3" >Lead</option>
                            </select>
                        </div>
                        {{-- <div class="form-group col-md-6">
                            <label for="password"> Password <span class="error">*</span></label>
                            <input type="text" class="form-control" id="password" name="password"
                                placeholder="Password">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cpassword"> Comfirm Password <span class="error">*</span></label>
                            <input type="text" class="form-control" id="cpassword" name="cpassword"
                                placeholder="Comfirm Password">
                        </div> --}}

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
    <script src="{{ asset('assets/js/pages/company-employee.js') }}"></script>
@endsection
