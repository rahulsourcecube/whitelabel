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
                    <form id="employeeform" method="POST" action="{{ route('company.employee.store') }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="fname">First Name <span class="error">*</span></label>
                                <input type="text" class="form-control" id="fname" name="fname"
                                    placeholder="First Name" maxlength="150" value="{{ old('fname') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="lname">Last Name <span class="error">*</span></label>
                                <input type="text" class="form-control" id="lname" name="lname"
                                    placeholder="Last Name" maxlength="150" value="{{ old('lname') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="number">Email Address <span class="error">*</span></label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Email Address" value="{{ old('email') }}">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="Type">Role</label>
                                <select id="Type" class="form-control" name="role">
                                    <option value="">Select</option>
                                    @foreach ($roles as $role)
                                        @if ($role->name != 'Company')
                                            <option value="{{ $role->name }}">{{ $role->role_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password"> Password <span class="error">*</span></label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Password">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="cpassword"> Confirm Password <span class="error">*</span></label>
                                <input type="password" class="form-control" id="cpassword" name="cpassword"
                                    placeholder="Confirm Password">
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
                fname: {
                    required: true
                },
                lname: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                role: {
                    required: true
                },
                password: {
                    minlength: 8,
                    maxlength: 50,
                    required: true,
                },
                cpassword: {
                    required: true,
                    equalTo: "#password"
                },
            },
            messages: {
                fname: {
                    required: "Please enter first name"
                },
                lname: {
                    required: "Please enter last name"
                },
                role: {
                    required: "Please select role"
                },
                email: {
                    required: "Please enter email",
                    email: "Please enter valid email address.",

                },
                password: {
                    required: "Please enter password",
                },
                cpassword: {
                    required: "Please enter confirm password",
                    equalTo: "The password you entered does not match.",
                },
            }
        });
    </script>
@endsection
