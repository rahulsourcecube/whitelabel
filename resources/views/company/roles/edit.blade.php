@extends('company.layouts.master')
@section('title', 'Edit Role')
@section('main-content')

    <div class="main-content">

        @include('company.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">Edit Role </span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Edit Role<span class="error">*</span></h4>
                <form id="package" method="POST" action="{{ route('company.role.update', $role->id) }}"enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <input type="text" id="myTextbox" class="form-control @error('name') is-invalid @enderror"
                                placeholder="Role Name" name="name" value="{{ $role->name }}" maxlength="150">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Edit</button>


                    <div class="m-t-25">
                        <table id="data-table" class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>View</th>
                                    <th>Add</th>
                                    <th>Edit</th>
                                    <th>Delete</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ModelPermission as $value)
                                    <tr>
                                        <th scope="row"><label> {{ $value->module_name }}</label></th>
                                        @if (isset($value->modules))
                                            @foreach ($value->modules as $modelPermission)
                                                <td style="text-align: center;">
                                                    {{ Form::checkbox('permission[]', $modelPermission->id, in_array($modelPermission->id, $rolePermissions) ? true : false, ['class' => 'name']) }}
                                                </td>
                                            @endforeach
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <label id="permission[]-error" class="error" for="permission[]"
                                    style="display: none !important">Please select at least one Permission.</label>
                    </div>
                </form>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
         <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
       <script>
            jQuery('#frm').validate({
                rules: {
                    name: "required",
                    'permission[]': {
                        required: true,
                        minlength: 1
                    }
                },
                messages: {
                    name: "Please Enter Role Name",
                    'permission[]': "Please select at least one Permission."
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        </script>

    @endsection
