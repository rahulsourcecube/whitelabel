@extends('admin.layouts.master')
@section('title', 'Add City')
@section('main-content')
    <div class="main-content">
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">
                        <i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <a href="{{ route('admin.location.city.list') }}" class="breadcrumb-item">City</a>
                    <span class="breadcrumb-item active">Add</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Add City</h4>
                <div class="m-t-50" style="">
                    <form id="city_form" method="POST" action="{{ route('admin.location.city.store') }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputype">State <span class="error">*</span></label>
                                <select id="state" name="state" class="form-control inputype">
                                    <option value="">Select State</option>
                                    @if ($state)
                                        @foreach ($state as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="name">City<span class="error">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="City" maxlength="150">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="name">Zipcode<span class="error">*</span></label>
                                <input type="number" class="form-control" id="zipcode" name="zipcode" placeholder="394107" maxlength="150">
                            </div>

                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3" style="max-height: 200px;">
                                <img id="imagePreview" src="#" alt="Image Preview" style="max-width: 100%; max-height: 80%;display: none;">
                                <button type="button" id="deleteImageButton" class="btn btn-danger btn-sm mt-2" style="display: none;" onclick="deleteImage()"><i
                                        class="fa fa-trash"></i></button>
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

            $('#city_form').validate({
                // alert('etger');
                rules: {
                    state: {
                        required: true
                    },
                    name: {
                        required: true
                    },
                    zipcode: {
                        required: true
                    },

                },
                messages: {
                    state: {
                        required: "Please enter state"
                    },
                    name: {
                        required: "Please enter city"
                    },
                    zipcode: {
                        required: "Please enter zipcode"
                    },

                }
            });

        });
    </script>
@endsection
