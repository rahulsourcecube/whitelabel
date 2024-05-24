@extends('admin.layouts.master')
@section('title', 'Edit State')
@section('main-content')
    <div class="main-content">
        @include('admin.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">
                        <i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <a href="{{ route('admin.location.state.list') }}" class="breadcrumb-item">State</a>
                    <span class="breadcrumb-item active">Edit</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Edit State</h4>
                <div class="m-t-50" style="">

                    <form action="{{ route('admin.location.state.update', ['state' => $state->id]) }}" method="post"
                        enctype="multipart/form-data" id="state_update">
                        @csrf
                        @method('PUT')

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputype">Country <span class="error">*</span></label>
                                <select id="country" name="country" class="form-control inputype">
                                    <option value="">Select Country</option>

                                    @if ($country)
                                        @foreach ($country as $data)
                                            <option value="{{ $data->id }}"
                                                {{ $state->country_id == $data->id ? 'selected' : '' }}>
                                                {{ $data->name }}
                                            </option>
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="name">State <span class="error">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="State"
                                    maxlength="150" value="{{ !empty($state->name) ? $state->name : '' }}">
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
    -->

    <script>
        $(document).ready(function() {


            $('#state_update').validate({
                // alert('etger');
                rules: {
                    country: {
                        required: true
                    },
                    name: {
                        required: true
                    }

                },
                messages: {
                    country: {
                        required: "Please enter country"
                    },
                    name: {
                        required: "Please enter state name"
                    }
                }
            });

        });
    </script>
@endsection
