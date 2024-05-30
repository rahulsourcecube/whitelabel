@extends('company.layouts.master')
@section('title', 'Reply VIew')
@section('main-content')
    <!-- Content Wrapper START -->
    <div class="main-content">
        @include('company.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item">Reply</span>
                    <span class="breadcrumb-item active">VIew </span>
                </nav>
            </div>
        </div>
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <h2 class="font-weight-normal m-b-10">
                            {{ !empty($questions) && $questions->title ? $questions->title : '' }}</h2>
                        <div class="m-t-20 text-right">
                            <div class=" text-right top">
                                <button href="#" type="button" id="handleClickActive"
                                    class="btn  {{ $questions->status == '1' ? 'btn-danger' : 'btn-success' }} btn-sm  "onclick="questionHandleClickActive(this,'{{ $questions->id ? base64_encode($questions->id) : '' }}')">{{ $questions->status == '1' ? 'Inactive' : 'Active' }}</button>
                                <a href="javascript:void(0)"
                                    onclick="sweetAlertAjaxQuestions('{{ route('community.questions.delete', !empty($questions->id) ? base64_encode($questions->id) : '') }}')"
                                    class="
                                        text-right btn btn-danger btn-sm "
                                    href="javascript:void(0)"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
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
                                <a href="javascript:void(0);" class="text-dark m-b-0 font-weight-semibold">Riley
                                    Newman</a>
                                <p class="m-b-0 text-muted font-size-13">{{ $questions->created_at->diffForhumans() }}
                                </p>

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

                                                @if (!empty(Auth::check()) && $companyAdmin == true)
                                                    <button href="#" type="button" id="handleClickActive"
                                                        class="btn {{ !empty($reply) && $reply->status == '1' ? 'btn-danger' : 'btn-success' }} btn-sm  "
                                                        onclick="handleClickActive(this,{{ !empty($reply) && $reply->id ? $reply->id : '' }})">{{ !empty($reply) && $reply->status == '1' ? 'Inactive' : 'Active' }}</button>
                                                @endif
                                                @if (
                                                    (!empty(Auth::check()) &&
                                                        (!empty(Auth::user()->id) && !empty($reply->user_id) && Auth::user()->id == $reply->user_id)) ||
                                                        $companyAdmin == true)
                                                    <a href="javascript:void(0)"
                                                        onclick="sweetAlertAjax('{{ route('community.reply.delete', base64_encode($reply->id)) }}')"
                                                        class="
                                                    text-right btn btn-danger btn-sm"
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
        </div>
    </div>

    <!-- return '<button class="btn btn-success  btn-sm" data-action="accept" onclick="Accept(\'' + actionUrl + '\',\'3\',\'' + id + '\')">Accept</button>' + -->


@endsection
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
    <script>
        function handleClickActive(element, reply_id) {
            var buttonText = $(element).text();
            var isChecked = buttonText === "Inactive";
            var message = isChecked ? 'make it Active' : 'make it Inactive';

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to ' + message + ', right?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, ' + message + '!'
            }).then((result) => {
                if (result.isConfirmed) {

                    if (buttonText === "Active") {
                        $(element).text("Inactive");
                        $(element).removeClass("btn-success").addClass(
                            "btn-danger");
                        var val = "1";
                    } else {
                        $(element).text("Active");
                        $(element).removeClass("btn-danger").addClass(
                            "btn-success");
                        var val = "0";
                    }
                    $.ajax({
                        url: "{{ route('community.reply.status.change') }}",
                        type: 'POST',
                        data: {
                            id: reply_id,
                            status: val,
                            _token: "{{ csrf_token() }}"
                        }

                    });
                }
            });
        }

        function questionHandleClickActive(element, community_id) {
            var buttonText = $(element).text();
            var isChecked = buttonText === "Inactive";
            var message = isChecked ? 'make it Active' : 'make it Inactive';

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to ' + message + ', right?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, ' + message + '!'
            }).then((result) => {
                if (result.isConfirmed) {

                    if (buttonText === "Active") {
                        $(element).text("Inactive");
                        $(element).removeClass("btn-success").addClass(
                            "btn-danger");
                        var val = "1";
                    } else {
                        $(element).text("Active");
                        $(element).removeClass("btn-danger").addClass(
                            "btn-success");
                        var val = "0";
                    }
                    $.ajax({
                        url: "{{ route('community.status.change') }}",
                        type: 'POST',
                        data: {
                            id: community_id,
                            status: val,
                            _token: "{{ csrf_token() }}"
                        }

                    });
                }
            });
        }

        function sweetAlertAjaxQuestions(deleteUrl) {
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
                                    window.location.href =
                                        '{{ route('company.reply.index') }}';
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