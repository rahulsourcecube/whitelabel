@extends('user.layouts.master')
@section('title', 'Campaign List')
@section('main-content')
    <?php use Illuminate\Support\Facades\URL; ?>
    <!-- Content Wrapper START -->
    <div class="main-content">
        @include('user.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('user.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <a class="breadcrumb-item" href="#">Campaign</a>
                    <span class="breadcrumb-item active">Campaign View</span>
                </nav>
            </div>
        </div>
        <div class="container1">
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-content">
                            @if (isset($campagin_detail) &&
                                    $campagin_detail->image != '' &&
                                    file_exists('uploads/company/campaign/' . $campagin_detail->image))
                                <img class="card-img-top"
                                    src="{{ asset('uploads/company/campaign/' . $campagin_detail->image) }}"
                                    class="w-100 img-responsive">
                            @else
                                <img src="{{ asset('assets/images/others/No_image_available.png') }}"
                                    class="w-100 img-responsive">
                            @endif
                        </div>
                        <div class="card-footer">
                            <input type="hidden" name="campagin_id" value="{{ $campagin_detail->id }}">
                            @if (!empty($campagin_detail->task_type) && $campagin_detail->task_type == 'Social')
                                <div class="text-center m-t-15">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
                                        target="_blank" class="m-r-5 btn btn-icon btn-hover btn-rounded">
                                        <i class="anticon anticon-facebook"></i>
                                    </a>
                                    <a href="https://www.twitter.com/share?u={{ url()->current() }}" target="_blank"
                                        class="m-r-5 btn btn-icon btn-hover btn-rounded">
                                        <i class="anticon anticon-twitter"></i>
                                    </a>
                                    <a href="https://www.instagram.com//sharer/sharer.php?u={{ url()->current() }}"
                                        target="_blank" class="m-r-5 btn btn-icon btn-hover btn-rounded">
                                        <i class="anticon anticon-instagram"></i>
                                    </a>
                                </div>
                            @endif
                            <div class="text-center m-t-30">

                                @if (!empty($user_Campaign) && $user_Campaign->status != '0')
                                    @if (isset($user_Campaign->status) && $user_Campaign->status == 1)
                                        <a class="btn btn-primary btn-tone"><span class="m-l-5">Claim reward</span></a>
                                    @endif
                                    @if (isset($user_Campaign->status) && $user_Campaign->status == 2)
                                        <a class="btn btn-primary btn-tone"><span class="m-l-5">Claim Pending</span></a>
                                    @endif
                                    @if (isset($user_Campaign->status) && $user_Campaign->status == 5)
                                        <a class="btn btn-primary btn-tone"><span class="m-l-5">Reopen</span></a>
                                    @endif
                                @else
                                    <a onclick="showSuccessAlert()" href="#" data-id=""
                                        class="btn btn-primary btn-tone" id="btnJoined">
                                        <span class="m-l-5">Join</span>
                                    </a>
                                @endif

                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-9">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="m-b-10">{{ isset($campagin_detail->title) ? $campagin_detail->title : '' }}</h4>
                            <div class="d-flex align-items-center m-t-5 m-b-15">
                                <div class="m-l-1">
                                    <span class="text-gray font-weight-semibold">Reward:
                                        <b>{{ isset($campagin_detail->reward) ? \App\Helpers\Helper::getcurrency() . $campagin_detail->reward : '' }}</b></span>
                                    <span class="m-h-5 text-gray">|</span>
                                    <span
                                        class="text-gray">{{ isset($campagin_detail->task_type) ? $campagin_detail->task_type : '' }}</span>
                                    @if ($campagin_detail->type == '1')
                                        <span class="m-h-5 text-gray">|</span>
                                        <span class="text-gray"> No of referral users:
                                            <b>{{ $campagin_detail->no_of_referral_users ?? '' }} </b></span>
                                    @endif
                                    <span class="m-h-5 text-gray">|</span>
                                    <span class="text-gray">Expire on
                                        {{ isset($campagin_detail->expiry_date) ? App\Helpers\Helper::Dateformat($campagin_detail->expiry_date) : '' }}</span>
                                </div>
                            </div>
                            <p class="m-b-20">{!! isset($campagin_detail->description) ? $campagin_detail->description : '' !!}
                            </p>
                        </div>
                    </div>
                </div>
                @if ($campagin_detail->type == 1 && $user_Campaign != null )
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>Recent Referral Users</h5>
                                    @if (
                                        $user_Campaign != null &&
                                            $user_Campaign->referral_link != '' &&
                                            $user_Campaign->getCampaign->task_expired != 'Expired')
                                        <div class="text-center mt-4 ml-3">
                                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('campaign.referral', $user_Campaign->referral_link) }}"
                                                target="_blank">
                                                <button class="m-r-5 btn btn-icon btn-hover btn-rounded">
                                                    <i class="anticon anticon-facebook"></i>
                                                </button>
                                            </a>
                                            <a
                                                href="https://www.twitter.com/share?u={{ route('campaign.referral', $user_Campaign->referral_link) }}">
                                                <button class="m-r-5 btn btn-icon btn-hover btn-rounded">
                                                    <i class="anticon anticon-twitter"></i>
                                                </button>
                                            </a>
                                            <a href="https://www.instagram.com//sharer/sharer.php?u={{ route('campaign.referral', $user_Campaign->referral_link) }}"
                                                target="_blank">
                                                <button class="m-r-5 btn btn-icon btn-hover btn-rounded">
                                                    <i class="anticon anticon-instagram"></i>
                                                </button>
                                            </a>
                                            <p id="referral_code_copy" style="display: none;">
                                                {{ route('campaign.referral', $user_Campaign->referral_link) }}</p>
                                            <button onclick="copyToClipboard('#referral_code_copy')"
                                                class="btn btn-primary btn-tone">
                                                <i class="anticon anticon-copy"></i>
                                                <span class="m-l-5">Copy referral link</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="m-t-30">
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="referral_user_tables">
                                            <thead>
                                                <tr>
                                                    <th>User</th>
                                                    <th>Reward</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($user_Campaign != null)
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>Recent Conected Users</h5>
                                </div>
                                <div class="m-t-30">
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="user_tables">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>User</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $i = 1;
                                                @endphp
                                                @if ($user_detail->count() != 0)
                                                    @foreach ($user_detail as $user_detail_get)
                                                        <tr>
                                                            <td>{{ isset($i) ? $i : '' }}
                                                            </td>
                                                            <td>{{ isset($user_detail_get->getuser->first_name) ? $user_detail_get->getuser->first_name : '' }}
                                                            </td>
                                                            <td>{{ isset($user_detail_get->getuser->created_at) ? App\Helpers\Helper::Dateformat($user_detail_get->getuser->created_at) : '' }}
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $i++;
                                                        @endphp
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan='3' align='center'>No data available in table</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- Content Wrapper START -->
        @php
        $showConversationBox = false;
        if ($user_Campaign != null && $user_Campaign->getCampaign->type=='1'){
            if($user_Campaign->getCampaign->task_expired == 'Expired' )
                $showConversationBox = true;
        }else {
            $showConversationBox = true; 
        }
        @endphp
        @if ($user_Campaign != null && $showConversationBox )
            <div class="container-fluid p-h-0 m-t-20">
                <div class="chat chat-app row">
                    <div class="chat-content "style="width:100%;">
                        <div class="conversation">
                            <div class="conversation-wrapper">
                                <div class="conversation-body scrollbar" style="overflow-y: auto;" id="style-4">
                                    @if (!empty($chats) && $chats->count() != 0)
                                        @foreach ($chats as $item)
                                            @if ($item->sender_id == $user->id)
                                                <div class="msg msg-sent">
                                                @else
                                                    <div class="msg msg-recipient">
                                                        @if (isset($user) && !empty($user->profile_image) && file_exists('uploads/user/user-profile/' . $user->profile_image))
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
                                                        <span>{!! $item->message ?? '' !!}</span>
                                                    </div>
                                                </div>
                                            @endif
                                </div>
        @endforeach
    @else
        <div class="msg justify-content-center">
            <div class="font-weight-semibold font-size-12">
                <h3> Please drop message and add
                    attachment
                    for
                    Claim reward <h3>
            </div>
        </div>

        @endif
    </div>
    @if (isset($user_Campaign->status) && $user_Campaign->status != 3)
        <div class="conversation-footer custom-footer">
            <textarea class="chat-input chat-style" type="text" placeholder="Type a message..." maxlength="255" required></textarea>
            <ul class="list-inline d-flex align-items-center m-b-0">
                <li class="list-inline-item m-r-15">
                    <a class="text-gray font-size-20 img_file_remove" href="javascript:void(0);" title="Attachment"
                        data-toggle="modal" data-target="#exampleModal">
                        <i class="anticon anticon-paper-clip"></i>
                    </a>
                </li>
                <li class="list-inline-item">
                    <button class="d-none d-md-block btn btn-primary custom-button"
                        @if ($user_Campaign != null) onclick="loadDataAndShowModal({{ $user_Campaign->id }})" @endif>
                        <span class="m-r-10">Send</span>
                        <i class="far fa-paper-plane"></i>
                    </button>
                    <a href="javascript:void(0);" class="text-gray font-size-20 d-md-none d-block">
                        <i class="far fa-paper-plane"></i>
                    </a>
                </li>
            </ul>
        </div>
    @endif
    </div>
    </div>
    </div>
    </div>
    @endif
    </div>
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
                    <button type="button" class="btn btn-default img_file_remove" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"
                        @if ($user_Campaign != null) onclick="loadDataAndShowModal({{ $user_Campaign->id }})" @endif>Save
                        changes</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function showSuccessAlert() {
            var ID = "{{ base64_encode($campagin_detail->id) }}";
            var url = "{{ route('user.campaign.getusercampaign', ':id') }}"
            url = url.replace(':id', ID);
            $.ajax({
                url: url,
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $("#btnJoined").hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Campaign joined successfully',
                        confirmButtonColor: '#3085D6',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    </script>
    <script>
        function copyToClipboard(elementId) {
            var el = document.querySelector(elementId);
            var textArea = document.createElement("textarea");
            textArea.value = el.textContent;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);

            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: 'URL copied to clipboard.',
                showConfirmButton: false,
                timer: 1500
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            // Call the function to fetch data and render the chart
            fetchReferralUserDetail();
        });

        function fetchReferralUserDetail() {
            var campagin_id = $('input[name="campagin_id"]').val();
            var _token = $('input[name="_token"]').val();

            $('#referral_user_tables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('GetReferralUserDetail') }}',
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "campagin_id": campagin_id,
                    },
                },
                columns: [{
                        data: 'User',
                        name: 'User'
                    },
                    {
                        data: 'Reward',
                        name: 'Reward'
                    },
                    {
                        data: 'Date',
                        name: 'Date'
                    },
                ]
            });
        };
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
                        location.reload();
                    },
                    error: function() {
                        alert("Something went wrong, please try again");
                    }
                });
            }
        }
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
                setTimeout(function() {
                    btnOuter.addClass("file_uploaded");
                }, 3000);
                var uploadedFile = URL.createObjectURL(e.target.files[0]);
                setTimeout(function() {
                    $("#uploaded_view").append('<img src="' + uploadedFile + '" />').addClass("show");
                }, 3500);
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
            var storeChatUrl = '{{ route('user.storeChat', ':id') }}';
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
