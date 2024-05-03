@extends('front.layouts.master')
@section('title', 'Community Show')
@section('main-content')







    <div class="mail-wrapper  p-h-20 p-v-20 bg full-height">
        @include('front.community.sidebar')
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <h2 class="font-weight-normal m-b-10">
                            {{ !empty($questions) && $questions->title ? $questions->title : '' }}</h2>
                        <div class="d-flex m-b-30">
                            <div class="avatar avatar-cyan avatar-img">

                                @if (isset($questions) &&
                                        !empty($questions->users->profile_image) &&
                                        file_exists(base_path() . '/uploads/user/user-profile/' . $questions->users->profile_image))
                                    <img src="{{ asset('uploads/user/user-profile/' . $questions->users->profile_image) }}"
                                        width="200">
                                @else
                                    <img src="{{ asset('assets/images/profile_image.jpg') }}" width="200">
                                @endif
                            </div>
                            <div class="m-l-15">
                                <a href="javascript:void(0);" class="text-dark m-b-0 font-weight-semibold">Riley Newman</a>
                                <p class="m-b-0 text-muted font-size-13">{{ $questions->created_at->diffForhumans() }}</p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center " style="align-content: center">

                            @if (isset($questions) &&
                                    !empty($questions->image) &&
                                    file_exists(base_path() . '/uploads/community/' . $questions->image))
                                <img src="{{ asset('uploads/community/' . $questions->image) }}"
                                    style="max-width:80%; max-height: 500px;">
                            @else
                                <img src="{{ asset('assets/images/profile_image.jpg') }}"
                                    style="max-width:80%; max-height: 500px;">
                            @endif


                        </div>
                        <div class="m-t-30">
                            @if (!empty($questions) && $questions->content)
                                {!! htmlspecialchars_decode(strip_tags($questions->content, 200)) !!}
                            @endif

                        </div>
                        <hr>
                        <h5>Replies ({{ !empty($questions->reply) ? $questions->reply->count() : 0 }})</h5>

                        @if (!empty($questionsReplys) && $questionsReplys)
                            <div class="m-t-20">

                                <ul class="list-group list-group-flush">

                                    @foreach ($questionsReplys as $reply)
                                        <li class="list-group-item p-h-0">
                                            <div class="m-t-20 text-right">
                                                @if (!empty(Auth::check()) && !empty(Auth::user()->id) && !empty($reply->user_id) && Auth::user()->id == $reply->user_id)
                                                    <a href="javascript:void(0)"
                                                        onclick="sweetAlertAjax('{{ route('community.questions.delete', base64_encode($reply->id)) }}')"
                                                        class="
                                                    text-right btn btn-danger"
                                                        href="javascript:void(0)"><i class="fa fa-trash"></i></a>
                                                @endif
                                            </div>
                                            <div class="media m-b-15">

                                                <div class="avatar avatar-image">
                                                    @if (isset($reply) &&
                                                            !empty($reply->users->profile_image) &&
                                                            file_exists(base_path() . '/uploads/user/user-profile/' . $reply->users->profile_image))
                                                        <img src="{{ asset('uploads/user/user-profile/' . $reply->users->profile_image) }}"
                                                            width="">
                                                    @else
                                                        <img src="{{ asset('assets/images/profile_image.jpg') }}"
                                                            width="">
                                                    @endif
                                                </div>

                                                <div class="media-body m-l-20">

                                                    <h6 class="m-b-0">
                                                        <p class="text-dark">Posted by
                                                            {{ !empty($reply->users->FullName) ? $reply->users->FullName : '' }}</a>
                                                    </h6>
                                                    <span
                                                        class="font-size-13 text-gray">{{ $reply->created_at->diffForhumans() }}</span>
                                                </div>
                                            </div>
                                            <span>
                                                @if (!empty($reply) && $reply->content)
                                                    {!! htmlspecialchars_decode(strip_tags($reply->content)) !!}
                                                @endif
                                            </span>

                                        </li>
                                    @endforeach


                                </ul>
                            </div>

                            <div class="m-t-30">
                                <nav>
                                    <div class="pagination justify-content-end">
                                        {{ $questionsReplys->appends(Request::all())->links() }}
                                    </div>

                                </nav>
                            </div>
                        @endif


                    </div>
                </div>
            </div>


            <div class="card card-default">
                <div class="card-body">

                    @if (Auth::check())
                        <form id="replyForm" action="{{ route('community.reply.store', ['id' => $questions->id]) }}"
                            method="POST">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label>Reply to this Questions</label>
                                <!-- Add a textarea with a specific ID for CKEditor to replace -->
                                <textarea id="editor" name="content" class="form-control" cols="20" rows="5"></textarea>
                                @error('content')
                                    <label id="content-error" class="error" for="reward">The Reply field is required.
                                    </label>
                                @enderror
                            </div>

                            <div class="form-group">
                                <input type="submit" name="submit" value="Save" class="btn btn-primary">
                            </div>
                        </form>
                    @else
                        <div class="text-center ">
                            <h1>You need to login to leave a reply</h1>
                        </div>
                    @endif
                </div>
            </div>


        </div>
    </div>












@section('js')
    <script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script>
        if (!CKEDITOR.instances['content']) {
            CKEDITOR.replace("content");
        }
        $(document).ready(function() {
            $('#replyForm').validate({
                rules: {
                    content: {
                        required: true
                    }
                },
                messages: {
                    content: {
                        required: "Please enter Answer"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });

        function sweetAlertAjax(deleteUrl) {
            // Use SweetAlert for confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If the user confirms, proceed with AJAX deletion
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: (response) => {

                            if (response.success == 'error') {
                                // Handle error case
                                Swal.fire({
                                    text: response.message,
                                    icon: "error",
                                    button: "Ok",
                                }).then(() => {
                                    // Reload the page or take appropriate action
                                    location.reload();
                                });
                            } else {

                                // Handle success case
                                Swal.fire({
                                    text: response.message,
                                    icon: "success",
                                    button: "Ok",
                                }).then(() => {
                                    // Reload the page or take appropriate action
                                    location.reload();
                                });
                            }
                        },
                        error: (xhr, status, error) => {
                            // Handle AJAX request error
                            console.error(xhr.responseText);
                            Swal.fire({
                                text: 'An error occurred while processing your request.',
                                icon: "error",
                                button: "Ok",
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
@endsection
