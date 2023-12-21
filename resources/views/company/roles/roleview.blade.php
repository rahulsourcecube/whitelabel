@extends('company.layouts.master')
@section('title', 'Role')
@section('main-content')


    <div class="main-content">

        @include('company.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">Role </span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Role<span class="error">*</span></h4>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <input type="text" id="myTextbox" class="form-control @error('name') is-invalid @enderror"
                            placeholder="Role Name" name="name" value="{{ $role->name }}" maxlength="150" readonly>
                    </div>
                </div>

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
                                                {{ Form::checkbox('permission[]', $modelPermission->id, in_array($modelPermission->id, $rolePermission) ? true : false, ['class' => 'permission', 'disabled' => '']) }}
                                                {{-- {{ $modelPermission->name }} --}}
                                            </td>
                                        @endforeach
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        

    @endsection
