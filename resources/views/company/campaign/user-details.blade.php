@extends('company.layouts.master')
@section('title', 'Campaign User Details')
@section('main-content')

    <div class="main-content">
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">User Details</span>
                </nav>
            </div>
        </div>
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="row">
                            <div class="col-md-12 col-lg-12">

                                <div class="d-md-flex align-items-center">
                                    <div class="text-center text-sm-left ">
                                        <div class="avatar avatar-image" style="width: 150px; height:150px">
                                            @if (isset($user) && !empty($user->profile_image) && file_exists(base_path().'/uploads/user/user-profile/' . $user->profile_image))
                                                <img src="{{ asset('uploads/user/user-profile/' . $user->profile_image) }}">
                                            @else
                                                <img src="{{ asset('assets/images/profile_image.jpg') }}">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-center text-sm-left m-v-15 p-l-30">
                                        <h2 class="m-b-5">
                                            {{ isset($user->first_name) ? $user->first_name : '' }}
                                            {{ isset($user->last_name) ? $user->last_name : '' }}
                                        </h2>
                                        <div class="row">
                                            <div class="d-md-block d-none border-left col-1"></div>
                                            <div class="col-md-12">
                                                <ul class="list-unstyled m-t-10">
                                                    <li class="row">
                                                        <p class="font-weight-semibold text-dark m-b-5">
                                                            <i class="m-r-8 text-primary anticon anticon-mail"></i>
                                                        </p>
                                                        <p class="col font-weight-semibold">
                                                            {{ isset($user->email) ? $user->email : '-' }}</p>
                                                    </li>

                                                    <li class="row">
                                                        <p class="font-weight-semibold text-dark m-b-5">
                                                            <i class="m-r-8 text-primary anticon anticon-phone"></i>
                                                        </p>
                                                        <p class="col font-weight-semibold">
                                                            {{ isset($user->contact_number) ? $user->contact_number : '-' }}
                                                        </p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="@if ($referral_user_detail->count() != 0) col-lg-6 @else col-lg-12 @endif">
                    <div class="card">
                        <div class="card-body tab-content" id="pills-tabContent">
                            <h2>Payout Detail:</h2>
                            <div class="table-responsive m-b-20">
                                <table class="product-info-table m-t-20">
                                    <tbody>
                                        <tr>
                                            <td><b>Paypal Id : </b> {{ $user->paypal_id ?? $user->paypal_id }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Stripe Id : </b> {{ $user->stripe_id ?? $user->stripe_id }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Bank Name : </b> {{ $user->bank_name ?? $user->bank_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Bank Holder : </b> {{ $user->ac_holder ?? $user->ac_holder }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>IFSC Code : </b> {{ $user->ifsc_code ?? $user->ifsc_code }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Account No : </b> {{ $user->ac_no ?? $user->ac_no }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($referral_user_detail->count() != 0)
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body" style="height: 340px;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h2>Referral Users</h2>
                                </div>
                                <div class="m-t-30">
                                    <div class="user-table-scroll">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>User</th>
                                                    <th>Reward</th>
                                                    <th>Date</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($referral_user_detail as $list)
                                                    <tr>
                                                        <td>{{ $loop->index + 1 }}</td>
                                                        <td>{{ optional($list->getuser)->first_name }}</td>
                                                        <td>{{ App\Helpers\Helper::getcurrency() . $list->reward }}</td>
                                                        <td>{{ App\Helpers\Helper::Dateformat($list->created_at) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-lg-12">
                    {{-- <div style="float: inline-start;">
                        Current task status :  <B>{{$camphistory->task_status}}</B>
                    </div>
                    <div style="float: inline-end;">
                        <button class="btn btn-success btn-sm action" data-action="3" data-id="{{ base64_encode($id) }}"
                            data-url="{{ route('company.campaign.action') }}">Accept</button>

                        <button class="btn btn-danger btn-sm action" data-action="4" data-id="{{ base64_encode($id) }}"
                            data-url="{{ route('company.campaign.action') }}" data-action="Reject">Reject</button>
                    </div> --}}
                    <div style="float: inline-start;">
                        Current task status : <B>{{ $camphistory->task_status }}</B>
                    </div>
                    <div style="float: inline-end;">
                        {{-- @if ($camphistory->status == 2) --}}
                        {{-- <button class="btn btn-success btn-sm action" data-action="3"
                                data-id="{{ base64_encode($id) }}"
                                data-url="{{ route('company.campaign.action') }}">Accept</button> --}}
                        @if ($camphistory->status == 2)
                            <button class="btn btn-success btn-sm action" data-action="3"
                                data-id="{{ base64_encode($id) }}"
                                data-url="{{ route('company.campaign.action') }}">Accept</button>
                            <button class="btn btn-danger btn-sm action" data-action="4" data-id="{{ base64_encode($id) }}"
                                data-url="{{ route('company.campaign.action') }}" data-action="Reject">Reject</button>
                        @else
                            @if ($camphistory->status == 3)
                                <button class="btn btn-danger btn-sm action" data-action="4"
                                    data-id="{{ base64_encode($id) }}" data-url="{{ route('company.campaign.action') }}"
                                    data-action="Reject">Reject</button>
                            @else
                                <button class="btn btn-success btn-sm action" data-action="3"
                                    data-id="{{ base64_encode($id) }}"
                                    data-url="{{ route('company.campaign.action') }}">Accept</button>
                            @endif
                        @endif
                        {{-- @endif --}}
                    </div>
                </div>

                <!-- Content Wrapper START -->
                <div class="container-fluid p-h-0 m-t-20">
                    <div class="chat chat-app row">
                        <div class="chat-content "style="width:100%;">
                            <div class="conversation">
                                <div class="conversation-wrapper">
                                    <div class="conversation-body scrollbar  @if (!empty($chats) && $chats->count() == 0) empty-chat @endif"
                                        style="overflow-y: auto;" id="style-4">
                                        @if (!empty($chats) && $chats->count() != 0)
                                            @foreach ($chats as $item)
                                                @if ($item->sender_id == Auth::user()->id)
                                                    <div class="msg msg-sent">
                                                    @else
                                                        <div class="msg msg-recipient">
                                                            @if (isset($user) && !empty($user->profile_image) && file_exists(base_path().'/uploads/user/user-profile/' . $user->profile_image))
                                                                <div class="m-r-10">
                                                                    <div class="avatar avatar-image">
                                                                        <img src="{{ asset('uploads/user/user-profile/' . $user->profile_image) }}"
                                                                            alt="">
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="m-r-10">
                                                                    <div class="avatar avatar-image">
                                                                        <img
                                                                            src="{{ asset('assets/images/profile_image.jpg') }}">
                                                                    </div>
                                                                </div>
                                                            @endif
                                                @endif
                                                @if (isset($item) && !empty($item->document) && file_exists('public/' . $item->document))
                                                    <div class="bubble">
                                                        <div class="bubble-wrapper p-5" style="max-width: 220px;">
                                                            <img src="{{ asset('public/' . $item->document) }}"
                                                                alt="{{ asset('public/' . $item->document) }}"
                                                                style="inline-size: -webkit-fill-available;">
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="bubble">
                                                        <div class="bubble-wrapper">
                                                            <span>{!! $item->message ?? '' !!} <br>
                                                                <p
                                                                    style="font-size: x-small;color: black; margin-bottom:0px;">
                                                                    {{ $item->created_at->format('Y-m-d H:i A') }} </p>
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endif
                                    </div>
                                    @endforeach
                                    @endif

                                </div>
                                <div class="conversation-footer custom-footer">
                                    <textarea class="chat-input chat-style" type="text" placeholder="Type a message..." maxlength="255" required></textarea>
                                    <ul class="list-inline d-flex align-items-center m-b-0">
                                        <li class="list-inline-item m-r-15">
                                            <a class="text-gray font-size-20 img_file_remove" href="javascript:void(0);"
                                                title="Attachment" data-toggle="modal" data-target="#exampleModal">
                                                <i class="anticon anticon-paper-clip"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item">
                                            <button class="d-none d-md-block btn btn-primary custom-button"
                                                onclick="loadDataAndShowModal({{ $id }})">
                                                <span class="m-r-10">Send</span>
                                                <i class="far fa-paper-plane"></i>
                                            </button>
                                            <a href="javascript:void(0);"
                                                class="text-gray font-size-20 d-md-none d-block">
                                                <i class="far fa-paper-plane"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Content Wrapper END -->
            <!-- Modal -->
            <div class="modal fade" id="exampleModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Attachment</h5>
                            <button type="button" class="close img_file_remove" data-dismiss="modal">
                                <i class="anticon anticon-close"></i>
                            </button>
                        </div>
                        <div class="modal-body custom-modal">
                            <main class="main_full">
                                <div class="container">
                                    <div class="panel">
                                        <div class="button_outer">
                                            <div class="btn_upload">
                                                <input type="file" id="upload_file" name="">
                                                Upload Image
                                            </div>
                                            <div class="processing_bar"></div>
                                            <div class="success_box"></div>
                                        </div>
                                    </div>
                                    <div class="error_msg"></div>
                                    <div class="uploaded_file_view" id="uploaded_view">
                                        <span class="file_remove img_file_remove">X</span>
                                    </div>
                                </div>
                            </main>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default img_file_remove"
                                data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary"
                                onclick="loadDataAndShowModal({{ $id }})">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        // Scroll down when the page loads
        window.addEventListener('load', function() {
            var element = document.querySelector(
                '.conversation-body'); // replace 'your-class' with your actual class name

            // Check if the element exists
            if (element) {
                // Set the scroll position to the bottom
                element.scrollTop = element.scrollHeight;
            }
        });
    </script>
    <script>
        $(document).on("click", ".action", function() {
            action = $(this).data('action');
            id = $(this).data('id');
            url = "{{ route('company.campaign.action') }}";
            $.ajax({
                url: url,
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'action': action,
                    'id': id,
                    'dataType': 'json'
                },
                success: (response) => {

                    if (response.success == 'error') {
                        Swal.fire({
                            text: response.messages,
                            icon: "error",
                            button: "Ok",
                        }).then(() => {
                            $('#view-modal').modal('hide');
                        });
                    } else {
                        Swal.fire({
                            text: response.messages,
                            icon: "success",
                            button: "Ok",
                        }).then(() => {
                            location.reload(true);
                            $('#view-modal').modal('hide');
                        });
                    }
                },
                error: (xhr, status, error) => {
                    console.error(xhr.responseText);
                    Swal.fire({
                        text: 'An error occurred while processing your request.',
                        icon: "error",
                        button: "Ok",
                    });
                }
            });
        });
    </script>
    <script>
        var btnUpload = $("#upload_file"),
            btnOuter = $(".button_outer");
        btnUpload.on("change", function(e) {
            var ext = btnUpload.val().split('.').pop().toLowerCase();
            if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                $(".error_msg").text("Not an Image...");
            } else {
                $(".error_msg").text("");
                btnOuter.addClass("file_uploading");
                    btnOuter.addClass("file_uploaded");
                var uploadedFile = URL.createObjectURL(e.target.files[0]);
                $("#uploaded_view").append('<img src="' + uploadedFile + '" />').addClass("show");
               
            }
        });
        $(".img_file_remove").on("click", function(e) {
        
            $("#uploaded_view").removeClass("show");
            $("#uploaded_view").find("img").remove();
            btnOuter.removeClass("file_uploading");
            btnOuter.removeClass("file_uploaded");
            $('#upload_file').val('');
        });
    </script>
    <script>
        function loadDataAndShowModal(id) {
            var storeChatUrl = '{{ route('company.campaign.storeChat', ':id') }}';
            storeChatUrl = storeChatUrl.replace(':id', id);

            var upload_file = $('#upload_file')[0].files[0];
            var chat_input = $('.chat-input').val();

            // Check if either chat_input or upload_file is not null
            if (chat_input !== '' || upload_file !== undefined) {
                var formData = new FormData();
                formData.append('image', upload_file);
                formData.append('chat_input', chat_input);

                $.ajax({
                    url: storeChatUrl,
                    method: "post",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('.chat-input').val('');
                        location.reload();
                    },
                    error: function() {
                        alert("Something went wrong, please try again");
                    }
                });
            }
        }
    </script>
@endsection
