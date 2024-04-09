@extends('admin.layouts.master')
@section('title', 'Add State')
@section('main-content')
    <div class="main-content">
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">
                        <i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <a href="{{ route('admin.location.state.list') }}" class="breadcrumb-item">State</a>
                    <span class="breadcrumb-item active">Add</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Add State</h4>
                <div class="m-t-50" style="">
                    <form id="state_form" method="POST" action="{{ route('admin.location.state.store') }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputype">Country <span class="error">*</span></label>
                                <select id="country" name="country" class="form-control inputype">
                                    <option value="">Select Country</option>
                                    @if ($country)
                                        @foreach ($country as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="name">State <span class="error">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="State" maxlength="150">
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

            // alert('rthh');
            $('#state_form').validate({
                // alert('etger');
                rules: {
                    country: {
                        required: true
                    },
                    name: {
                        required: true
                    },

                },
                messages: {
                    counrtry: {
                        required: "Please enter country"
                    },
                    name: {
                        required: "Please enter state"
                    },

                }
            });

        });
    </script>
@endsection
