@extends('admin.layouts.master')
@section('title', 'Edit City')
@section('main-content')
    <div class="main-content">
        @include('admin.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">
                        <i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <a href="{{ route('admin.location.city.list') }}" class="breadcrumb-item">City</a>
                    <span class="breadcrumb-item active">Edit</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Edit City</h4>
                <div class="m-t-50" style="">

                    <form action="{{ route('admin.location.city.update', ['city' => $city->id]) }}" method="post"
                        enctype="multipart/form-data" id="city_update">
                        @csrf
                        @method('PUT')

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="inputype">Country <span class="error">*</span></label>
                                <select id="country" name="country" class="form-control inputype">
                                    <option value="">Select Country</option>
                                    @if ($country)
                                        @foreach ($country as $data)
                                            <option value="{{ $data->id }}"
                                                {{ (!empty(old('country')) && old('country') == $data->id) || $data->id == $city->country_id ? 'selected' : '' }}>
                                                {{ $data->name }}
                                            </option>
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputype">State <span class="error">*</span></label>
                                <select id="state" name="state" class="form-control inputype">
                                    <option value="">Select State</option>
                                    @if ($state)
                                        @foreach ($state as $data)
                                            <option value="{{ $data->id }}"
                                                {{ $city->state_id == $data->id ? 'selected' : '' }}>
                                                {{ $data->name }}
                                            </option>
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="name">City <span class="error">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="City"
                                    maxlength="150" value="{{ !empty($city->name) ? $city->name : '' }}">
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

    <script>
        $(document).ready(function() {
            $('#city_update').validate({
                rules: {
                    state: {
                        required: true
                    },
                    name: {
                        required: true
                    }
                },
                messages: {
                    state: {
                        required: "Please select state"
                    },
                    name: {
                        required: "Please enter city"
                    },
                }
            });

        });
    </script>
@endsection
