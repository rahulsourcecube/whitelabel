@extends('front.layouts.master')
@section('title', 'Add Community')
@section('main-content')


    <div class="mail-wrapper  p-h-20 p-v-20 bg full-height">
        @include('front.community.sidebar')


        <div class="container">

            <form id="community" action ="{{ route('community.store') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="title">Title <span class="error">*</span></label>
                    <input type="text" value="{{ old('title') }}" name="title" placeholder="Enter Title"
                        class="form-control" id="title">
                </div>

                <div class="form-group">
                    <label>Category <span class="error">*</span></label>

                    <select name="channel_id" id="channel_id" class="form-control">
                        <option value="">Select Category </span></option>

                        @if (!empty($channels))
                            @foreach ($channels as $channel)
                                <option value="{{ $channel->id }}"
                                    {{ !empty(old('channel_id')) && $channel->id == old('channel_id') ? 'selected' : '' }}>
                                    {{ $channel->title }}</option>
                            @endforeach

                        @endif
                    </select>

                </div>

                <div class="form-group">
                    <label for="content">Ask Question <span class="error">*</span></label>
                    <textarea type="text" name="content" class="form-control" id="content"> </textarea>
                    @error('content')
                        <label id="content-error" class="error" for="reward">The Question field is required.
                        </label>
                    @enderror
                </div>
                <div class="form-group ">
                    <label for="file">Image <span class="error"></span></label>
                    <input type="file" class="form-control" name="image" id="file" accept=".png, .jpg, .jpeg"
                        onchange="previewImage()">
                    {{-- @error('image')
                        <label id="image-error" class="error" for="image">{{ $message }}</label>
                    @enderror --}}
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3" style="max-height: 200px;">
                        <img id="imagePreview" src="#" alt="Image Preview"
                            style="max-width: 100%; max-height: 80%;dipslay: none;">
                        <button type="button" id="deleteImageButton" class="btn btn-danger btn-sm mt-2"
                            style="display: none;" onclick="deleteImage()"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" value="Save" class="btn btn-primary">
                </div>

            </form>
        </div>
    </div>
@section('js')
    <script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script>
        if (!CKEDITOR.instances['content']) {
            CKEDITOR.replace("content");
        }

        $(document).ready(function() {
            $('#community').validate({
                rules: {
                    title: {
                        required: true
                    },
                    channel_id: {
                        required: true
                    },

                },
                messages: {
                    title: {
                        required: "Please enter a title"
                    },
                    channel_id: {
                        required: "Please select a Category"
                    },

                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
@endsection
