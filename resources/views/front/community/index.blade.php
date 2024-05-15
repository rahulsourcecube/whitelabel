@extends('front.layouts.master')
@section('title', 'community')
@section('main-content')


    <!-- Content Wrapper START -->


    <div class="mail-wrapper  p-h-20 p-v-20 bg full-height">
        @include('front.community.sidebar')


        <div class="container">
            @include('front.includes.message')
            @if (!empty($questions) && count($questions) > 0)

                @foreach ($questions as $question)
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                {{-- <div class="col-md-4">
                                    <img class="img-fluid" src="assets/images/others/img-2.jpg" alt="">
                                </div> --}}
                                <div class="col-md-12">
                                    @if ($companyAdmin == true)
                                        <div class=" text-right top">
                                            <button href="#" type="button" id="handleClickActive"
                                                class="btn  {{ $question->status == '1' ? 'btn-danger' : 'btn-success' }} btn-sm  "onclick="handleClickActive(this,'{{ $question->id ? base64_encode($question->id) : '' }}')">{{ $question->status == '1' ? 'Inactive' : 'Active' }}</button>
                                            <a href="javascript:void(0)"
                                                onclick="sweetAlertAjax('{{ route('community.questions.delete', !empty($question->id) ? base64_encode($question->id) : '') }}')"
                                                class="
                                                    text-right btn btn-danger btn-sm "
                                                href="javascript:void(0)"><i class="fa fa-trash"></i></a>
                                        </div>
                                    @endif

                                    <h4 class="m-b-10">{{ $question->title }}</h4>
                                    <div class="d-flex align-items-center m-t-5 m-b-15">
                                        <div class="avatar avatar-image avatar-sm ">
                                            @if (isset($question) &&
                                                    !empty($question->users->profile_image) &&
                                                    file_exists(base_path() . '/uploads/user/user-profile/' . $question->users->profile_image))
                                                <img
                                                    src="{{ asset('uploads/user/user-profile/' . $question->users->profile_image) }}">
                                            @else
                                                <img src="{{ asset('assets/images/profile_image.jpg') }}">
                                            @endif
                                        </div>
                                        <div class="m-l-10">
                                            <span
                                                class="text-gray font-weight-semibold">{{ !empty($question) && !empty($question->user_id) && !empty($question->users->FullName) ? $question->users->FullName : '' }}</span>
                                            <span class="m-h-5 text-gray">|</span>
                                            <span class="text-gray">{{ $question->created_at->diffForhumans() }}</span>
                                        </div>
                                    </div>
                                    <p class="m-b-20">
                                        <?php ?>
                                        {!! htmlspecialchars_decode(strip_tags(Str::limit($question->content, 200))) !!}</p>
                                    <div class="text-left">
                                        <a href="  {{ route('community.show', ['id' => base64_encode($question->id)]) }}"
                                            class="btn btn-hover font-weight-semibold">
                                            <span>Read More</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-sm-12 h-15 w-50 pagination justify-content-center">
                    {{ $questions->appends(Request::all())->links() }}
                </div>
            @else
                @include('front.error.notFoundQuestions')

            @endif
        </div>
    </div>


@endsection
@section('js')
    <script>
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

        function handleClickActive(element, community_id) {
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
    </script>

@endsection
