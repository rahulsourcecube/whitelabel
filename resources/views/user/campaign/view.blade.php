@extends('user.layouts.master')
@section('title', 'Campaign List')
@section('main-content')
    <style>
        .rating {
            font-size: 24px;
        }

        .rating .feedback i {
            font-size: 75px;
        }

        .rating i {
            cursor: pointer;
        }

        .rating i.hover {
            color: orange;
        }

        .rating i.selected {
            color: gold;
        }



        .emoji-result-text {
            font-size: 20px;
            margin-top: 25px;
            text-align: center;
            background: #F4D348;
            padding: 20px;
            border-radius: 8px;
            font-weight: 800;
        }

        .emoji-container {
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Helvetica, sans-serif;
            margin: 0;
        }

        #emojiForm input[type="radio"] {
            -webkit-appearance: none;
            width: 70px !important;
            height: 70px;
            border: none;
            cursor: pointer;
            transition: border .2s ease;
            filter: grayscale(100%);
            margin: 0 5px;
            transition: all .2s ease;
        }

        #emojiForm input[type="radio"]:hover,
        #emojiForm input[type="radio"]:checked {
            filter: grayscale(0);
        }

        #emojiForm input[type="radio"]:focus {
            outline: 0;
        }

        #emojiForm input[type="radio"].love {
            background: url('data:image/png;base64,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') center;
            background-size: cover;
        }

        #emojiForm input[type="radio"].happy {
            background: url('data:image/png;base64,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') center;
            background-size: cover;
        }

        #emojiForm input[type="radio"].neutral {
            background: url('data:image/png;base64,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') center;
            background-size: cover;
        }

        #emojiForm input[type="radio"].sad {
            background: url('data:image/png;base64,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') center;
            background-size: cover;
        }

        #emojiForm input[type="radio"].confused {
            background: url('data:image/png;base64,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') center;
            background-size: cover;
        }
    </style>
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
        <input type="hidden" class="user_Campaign"
            value="{{ !empty($user_Campaign->id) ? base64_encode($user_Campaign->id) : null }}">
        <div class="container1">
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-content">
                            @if (isset($campagin_detail) &&
                                    $campagin_detail->image != '' &&
                                    file_exists(base_path() . '/uploads/company/campaign/' . $campagin_detail->image))
                                <img class="card-img-top"
                                    src="{{ asset('uploads/company/campaign/' . $campagin_detail->image) }}"
                                    class="w-100 img-responsive">
                            @else
                                <img src="{{ asset('assets/images/others/No_image_available.png') }}"
                                    class="w-100 img-responsive">
                            @endif
                        </div>
                        <div class="card-footer">
                            <input type="hidden" name="campagin_id" value="{{ $campagin_detail->id ?? '' }}">
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
                                        Status: <strong
                                            class=" text-primary \" > Claim Reward</strong>
@endif
                                    @if (isset($user_Campaign->status) && $user_Campaign->status == 2)
Status: <strong class="text-primary">
                                            Claim Pending</strong>
                                    @endif
                                    @if (isset($user_Campaign->status) && $user_Campaign->status == 5)
                                        Status: <strong
                                            class=" text-primary \" > Reopen</strong>
@endif
                                    @if (isset($user_Campaign->status) && $user_Campaign->status == 4)
Status: <strong
                                        class="
                                            text-danger \"> Rejected</strong>
                                    @endif
                                    @if (isset($user_Campaign->status) && $user_Campaign->status == 3)
                                        Status: <strong
                                            class=" text-success  \" >Completed</strong>
@endif
@else
<a onclick="showSuccessAlert()"
                                            href="#" data-id="" class="btn btn-primary btn-tone" id="btnJoined">
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
                                <div class="m-l-0">
                                    <span
                                        class="text-gray">{{ isset($campagin_detail->task_type) ? $campagin_detail->task_type : '' }}</span>
                                    @if (isset($campagin_detail->type) && $campagin_detail->type == '1')
                                        <span class="m-h-5 text-gray">|</span>
                                        <span class="text-gray"> No of referral users:
                                            <b>{{ $campagin_detail->no_of_referral_users ?? '' }} </b></span>
                                    @endif
                                    <span class="m-h-5 text-gray">|</span>
                                    <span class="text-gray">Expire on
                                        {{ isset($campagin_detail->expiry_date) ? App\Helpers\Helper::Dateformat($campagin_detail->expiry_date) : '' }}</span>
                                    <p class="text-gray font-weight-semibold">Reward:
                                        <b>{{ $campagin_detail->text_reward ? $campagin_detail->text_reward : (isset($campagin_detail->reward) ? \App\Helpers\Helper::getcurrency() . $campagin_detail->reward : '0') }}</b>
                                        <span class="m-h-5 text-gray">|</span>
                                        <span
                                            class="text-gray">{{ App\Helpers\Helper::Dateformat($campagin_detail->expiry_date) ?? '' }}</span>
                                    <p class="text-gray font-weight-semibold">
                                        @if ($campagin_detail->priority == 1)
                                            <span class="badge badge-pill badge-danger"> High </span>
                                        @elseif($campagin_detail->priority == 2)
                                            <span class="badge badge-pill badge-info"> Medium </span>
                                        @elseif($campagin_detail->priority == 3)
                                            <span class="badge badge-pill badge-success"> Low </span>
                                        @else
                                            {{-- Handle other cases if needed --}}
                                        @endif
                                        Reward:
                                        <b>{{ $campagin_detail->text_reward ? $campagin_detail->text_reward : (isset($campagin_detail->reward) ? \App\Helpers\Helper::getcurrency() . $campagin_detail->reward : '0') }}</b>

                                        <span class="m-h-5 text-gray">|</span>
                                        <span class="text-gray"><b>Public: </b>
                                            @if ($campagin_detail->public == 1)
                                                Yes
                                            @elseif($campagin_detail->public == 0)
                                                No
                                            @else
                                                NA
                                                {{-- Handle other cases if needed --}}
                                            @endif
                                        </span>
                                    </p>

                                </div>
                            </div>
                            <p class="m-b-20">{!! isset($campagin_detail->description) ? $campagin_detail->description : '' !!}
                            </p>
                        </div>
                    </div>
                </div>
                @if (isset($campagin_detail->type) && $campagin_detail->type == 1 && $user_Campaign != null)
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="col-md-4">
                                        <h5>Recent Referral Users</h5>
                                    </div>
                                    <div class="col-md-4">

                                        <div class="alert alert-warning alert-dismissible alert-live show w-max-content">
                                            <b>List of users who connected with the link shared.</b>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        {{-- @if ($user_Campaign != null && $user_Campaign->referral_link != '' && $user_Campaign->getCampaign->task_expired != 'Expired' && $ReferralCount > $user_Campaign->no_of_referral_users) --}}
                                        <div class="text-center ml-3">
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
                                            @php

                                                $customUrl =
                                                    !empty($campagin_detail) &&
                                                    !empty($campagin_detail->referral_url_segment)
                                                        ? '?' . $campagin_detail->referral_url_segment
                                                        : '';
                                            @endphp
                                            <p id="referral_code_copy" style="display:none">
                                                {{ route('campaign.referral', $user_Campaign->referral_link . $customUrl) }}
                                            </p>
                                            <button onclick="copyToClipboard('#referral_code_copy')"
                                                class="btn btn-primary btn-tone">
                                                <i class="anticon anticon-copy"></i>
                                                <span class="m-l-5">Copy referral link</span>
                                            </button>
                                        </div>
                                    </div>
                                    {{-- @endif --}}
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
                                    <div class="col-md-4">
                                        <h5> Users</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="alert alert-warning alert-dismissible alert-live show w-max-content">
                                            <b>List of users who connected with the my refferel code.</b>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                                <div class="m-t-30">
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="">
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
                    @if (isset($user_Campaign->status) && $user_Campaign->status == '3')
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="container mt-5">

                                    @php
                                        $se = '';
                                        $th = '';
                                        $for = '';
                                        $fiv = '';
                                        $selectRating =
                                            !empty($ratings) && $ratings->no_of_rating ? $ratings->no_of_rating : '1';
                                        if ($selectRating == '1') {
                                            $emoji = 'unimpressed by';
                                        } elseif ($selectRating == '2') {
                                            $emoji = 'are confused about';
                                        } elseif ($selectRating == '3') {
                                            $emoji = 'are neutral about';
                                        } elseif ($selectRating == '4') {
                                            $emoji = 'had fun playing';
                                        } elseif ($selectRating == '5') {
                                            $emoji = 'loved';
                                        } else {
                                            $emoji = '';
                                        }

                                    @endphp

                                    <h2 style="text-align: center">Reviews</h2>
                                    <div class="d-flex justify-content-md-center ">
                                        <form id="emojiForm" method="POST">
                                            @csrf
                                            <input type="radio" name="emoji" value="1"
                                                {{ !empty($ratings) && $ratings->no_of_rating == '1' ? 'checked' : '' }}
                                                data-value="unimpressed by" class="sad">
                                            <input type="radio" name="emoji" value="2" class="confused"
                                                {{ !empty($ratings) && $ratings->no_of_rating == '2' ? 'checked' : '' }}
                                                data-value="are confused about">
                                            <input type="radio" name="emoji" value="3" class="neutral"
                                                {{ !empty($ratings) && $ratings->no_of_rating == '3' ? 'checked' : '' }}
                                                data-value="are neutral about">
                                            <input type="radio" name="emoji" data-value="had fun playing"
                                                {{ !empty($ratings) && $ratings->no_of_rating == '4' ? 'checked' : '' }}
                                                value="4" class="happy">
                                            <input type="radio" name="emoji" data-value="loved" value="5"
                                                {{ !empty($ratings) && $ratings->no_of_rating == '5' ? 'checked' : '' }}
                                                class="love">
                                            <input type="hidden" name="campaign_id"
                                                value="{{ $campagin_detail->id ?? '' }}">

                                        </form>
                                    </div>
                                    <div class="emoji-result-text mb-2">You <span
                                            id="result">{{ $emoji }}</span> This Campaign.</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="container mt-5">
                                    <h2 style="text-align: center">Add Feedback</h2>
                                    <form id="feedbackForm" style="" method="POST">
                                        @csrf
                                        @php
                                            $fse = '';
                                            $fth = '';
                                            $ffor = '';
                                            $ffiv = '';
                                            $feedbackSelectRating =
                                                !empty($feedback) && $feedback->no_of_rating
                                                    ? $feedback->no_of_rating
                                                    : '1';
                                            if ($feedbackSelectRating == '2') {
                                                $se = 'selected';
                                            } elseif ($feedbackSelectRating == '3') {
                                                $fse = 'selected';
                                                $fth = 'selected';
                                            } elseif ($feedbackSelectRating == '4') {
                                                $fse = 'selected';
                                                $fth = 'selected';
                                                $ffor = 'selected';
                                            } elseif ($feedbackSelectRating == '5') {
                                                $fse = 'selected';
                                                $fth = 'selected';
                                                $ffor = 'selected';
                                                $ffiv = 'selected';
                                            }

                                        @endphp
                                        @if ($campagin_detail->feedback_type == 'rating' || $campagin_detail->feedback_type == 'both')
                                            <div class="rating form-group center">
                                                <!-- Rating stars -->
                                                <div class="rating feedback " style="text-align: center;">
                                                    <i class="bi bi-star selected" style="font-size: 75px"></i>
                                                    <i class="bi bi-star {{ $fse }}"></i>
                                                    <i class="bi bi-star {{ $fth }}"></i>
                                                    <i class="bi bi-star {{ $ffor }}"></i>
                                                    <i class="bi bi-star {{ $ffiv }}"></i>
                                                </div>
                                                <div id="feedback-selected-rating" style="text-align: center">
                                                    Selected Star:
                                                    {{ !empty($feedback) && $feedback->no_of_rating ? $feedback->no_of_rating : '1' }}
                                                </div>

                                            </div>
                                            <input type="hidden" name="no_of_rating" class="valRetingFeedback"
                                                value="{{ !empty($feedback) && $feedback->no_of_rating ? $feedback->no_of_rating : '1' }}">
                                        @endif

                                        <input type="hidden" name="campaign_id"
                                            value="{{ $campagin_detail->id ?? '' }}">
                                        @if ($campagin_detail->feedback_type == 'description' || $campagin_detail->feedback_type == 'both')
                                            <div class="form-group">
                                                <label for="comment"><b>Description
                                                    </b></label>
                                                <textarea class="form-control" id="comment" name="comments" rows="3" cols="20"
                                                    placeholder="Please Enter Comment..">{{ !empty($feedback) && $feedback->comments ? $feedback->comments : '' }}</textarea>
                                                <label id="comment-error" class="error" for="comment"></label>
                                            </div>
                                        @endif
                                        <div class="mt-3 form-group">
                                            <!-- Submit button -->
                                            <button id="submitRating"
                                                class="btn btn-primary">{{ !empty($feedback) ? 'Update' : 'Send' }}</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
        <!-- Content Wrapper START -->
        @php
            $showConversationBox = false;
            if ($user_Campaign != null && $user_Campaign->getCampaign->type == '1') {
                if (
                    $user_Campaign->getCampaign->task_expired != 'Completed' ||
                    $ReferralCount <= $user_Campaign->no_of_referral_users
                ) {
                    $showConversationBox = true;
                }
            } else {
                $showConversationBox = false;
            }
        @endphp

        @if ($user_Campaign != null)
            <div class="row">
                <div class="col-md-4">
                </div>
                <div class="col-md-4">
                    <div class="alert alert-info alert-dismissible alert-live show mb-0 w-max-content">
                        <b>Contact with support to verify your task.</b>
                    </div>
                </div>
                <div class="col-md-4">
                </div>
            </div>
            <div class="container-fluid p-h-0 m-t-10">
                <div class="chat chat-app row">
                    <div class="chat-content "style="width:100%;">
                        <div class="conversation">
                            <div class="conversation-wrapper">

                                <div class="conversation-body scrollbar @if (!empty($chats) && $chats->count() == 0) empty-chat @endif"
                                    style="overflow-y: auto; " id="style-4">
                                    @if (!empty($chats) && $chats->count() != 0)

                                        @foreach ($chats as $item)
                                            @if ($item->sender_id == $user->id)
                                                <div class="msg msg-sent">
                                                @else
                                                    <div class="msg msg-recipient">
                                                        @if (isset($item->getCompanySetting->logo) &&
                                                                !empty($item->getCompanySetting->logo) &&
                                                                file_exists(base_path() . '/uploads/setting/' . $item->getCompanySetting->logo))
                                                            <div class="m-r-10">
                                                                <div class="avatar avatar-image">
                                                                    <img src="{{ asset('/uploads/setting') . '/' . $item->getCompanySetting->logo }}"
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

                                            @if (isset($item) && !empty($item->document) && file_exists(base_path('public/' . $item->document)))
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
                                                        <span> {!! $item->message ?? '' !!} <br>
                                                            <p
                                                                style="font-size: x-small;color: black;  margin-bottom:0px;">
                                                                {{ $item->created_at->format('Y-m-d H:i A') }} </p>
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif
                                </div>
        @endforeach
    @else
        <div class="msg justify-content-center align-items-center" style="text-align: center;">
            <div class="font-weight-semibold font-size-12" style="margin: auto;">
                <h3> Please drop message and add attachment for claim reward. </h3>
            </div>
        </div>

        @endif
    </div>

    {{-- @if (isset($user_Campaign->status) && $user_Campaign->status != '3') --}}
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

            @if (
                ($user_Campaign != null && $ReferralCount >= $user_Campaign->no_of_referral_users && $user_Campaign->status == 1) ||
                    $user_Campaign->status == 4)

                @if ($user_Campaign->status != 4)
                    <li class="list-inline-item">
                        <button onclick="requestSuccessAlert()" class="d-none d-md-block btn btn-primary custom-button"
                            id="done"><span class="m-r-10">Send
                                For Approval</span>
                        </button>
                        <a href="javascript:void(0);" class="text-gray font-size-20 d-md-none d-block">
                            <i class="far fa-paper-plane"></i>
                        </a>
                    </li>
                @else
                    <li class="list-inline-item">
                        <button onclick="reopenSuccessAlert()" class="d-none d-md-block btn btn-danger custom-button"
                            id="Reopen"><span class="m-r-10">Reopen</span>
                        </button>
                        <a href="javascript:void(0);" class="text-gray font-size-20 d-md-none d-block">
                            <i class="far fa-paper-plane"></i>
                        </a>
                    </li>
                @endif

            @endif

        </ul>
    </div>

    {{-- @endif --}}

    </div>
    </div>
    </div>
    </div>

    @endif
    </div>
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
                    <button type="button" class="btn btn-primary submitform"
                        @if ($user_Campaign != null) onclick="loadDataAndShowModal({{ $user_Campaign->id }})" @endif>Upload
                    </button>
                </div>
            </div>
        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    {{-- <script>
          $(document).ready(function() {
            chat = "{{ ($chat) }}"
          });
    </script> --}}
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script>
        $(document).ready(function() {


            $('#emojiForm input').on('click', function() {

                $('#result').html($(this).attr('data-value'));
                $.ajax({
                    url: '{{ route('user.store.reivews') }}',
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    data: $('#emojiForm').serialize(),
                    success: function(result) {
                        $("#btnJoined").hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Thankyou for given review',
                            confirmButtonColor: '#3085D6',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            // Reload the page
                            location.reload();
                        });
                    },
                    error: function(error) {

                    }
                });
            });
            // Initially, no star is selected
            var selectedRating = 0;

            // Highlight stars on hover
            $(".rating i").hover(function() {
                $(this).prevAll().addBack().addClass("hover");
            }, function() {
                $(this).prevAll().addBack().removeClass("hover");
            });

            // Set rating on click
            $(".reivews i").click(function() {
                selectedRating = $(this).index() + 1;
                $(".reivews i").removeClass("selected");
                $(this).prevAll().addBack().addClass("selected");
                $("#selected-rating").text("Selected rating: " + selectedRating);
                $(".valRarting").val(selectedRating);
            });

            $(".feedback i").click(function() {
                selectedRating = $(this).index() + 1;
                $(".feedback i").removeClass("selected");
                $(this).prevAll().addBack().addClass("selected");
                $(" #feedback-selected-rating").text("Selected rating: " + selectedRating);
                $(".valRetingFeedback").val(selectedRating);
            });
        });
    </script>
    <script>
        // $(document).ready(function() {
        //     // Add validation rules
        //     var ratingTask = "";
        //     $("#ratingForm").validate({
        //         rules: {
        //             // Define rules for each form field
        //             comments: {
        //                 required: true,
        //                 minlength: 10 // Example: Minimum length of 10 characters
        //             }
        //         },
        //         messages: {
        //             // Define custom error messages
        //             comment: {
        //                 required: "Please enter your comment.",
        //                 minlength: "Your comment must be at least {0} characters long."
        //             }
        //         },
        //         // Specify where to display error messages
        //         errorPlacement: function(error, element) {
        //             error.appendTo(element.parent().next());
        //         },
        //         submitHandler: function(form, e) {
        //             e.preventDefault();
        //             console.log('Form submitted');
        //             $.ajax({
        //
        //                 type: 'POST',
        //                 data: {
        //                     "_token": "{{ csrf_token() }}",
        //                 },
        //                 data: $('#ratingForm').serialize(),
        //                 success: function(result) {
        //                     $("#btnJoined").hide();
        //                     Swal.fire({
        //                         icon: 'success',
        //                         title: 'Success!',
        //                         text: 'Thankyou for given rating',
        //                         confirmButtonColor: '#3085D6',
        //                         confirmButtonText: 'OK'
        //                     }).then(function() {
        //                         // Reload the page
        //                         location.reload();
        //                     });
        //                 },
        //                 error: function(error) {

        //                 }
        //             });
        //             return false;
        //         }

        //     });
        // });
    </script>
    {{-- feedback Form --}}
    <script>
        $(document).ready(function() {
            // Add validation rules
            var ratingTask = "";
            $("#feedbackForm").validate({
                rules: {
                    // Define rules for each form field
                    comments: {
                        required: true,
                        minlength: 10 // Example: Minimum length of 10 characters
                    }
                },
                messages: {
                    // Define custom error messages
                    comment: {
                        required: "Please enter your comment.",
                        minlength: "Your comment must be at least {0} characters long."
                    }
                },
                // Specify where to display error messages
                errorPlacement: function(error, element) {
                    error.appendTo(element.parent().next());
                },
                submitHandler: function(form, e) {
                    e.preventDefault();
                    console.log('Form submitted');
                    $.ajax({
                        url: '{{ route('user.store.feedback.task') }}',
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        data: $('#feedbackForm').serialize(),
                        success: function(result) {
                            $("#btnJoined").hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Thankyou for given feedback',
                                confirmButtonColor: '#3085D6',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                // Reload the page
                                location.reload();
                            });
                        },
                        error: function(error) {

                        }
                    });
                    return false;
                }

            });
        });
    </script>
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
                    }).then(function() {
                        // Reload the page
                        location.reload();
                    });
                }
            });
        }
    </script>
    <script>
        function requestSuccessAlert() {

            var ID = $('.user_Campaign').val();

            var url = "{{ route('user.campaign.requestSend', ':id') }}"
            url = url.replace(':id', ID);

            $.ajax({
                url: url,
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",

                },
                success: function(data) {
                    $("#done").hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Request send successfully',
                        confirmButtonColor: '#3085D6',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        // Reload the page
                        location.reload();
                    });
                }
            });
        }
    </script>
    <script>
        function reopenSuccessAlert() {

            var ID = $('.user_Campaign').val();

            var url = "{{ route('user.campaign.reopenSend', ':id') }}"
            url = url.replace(':id', ID);

            $.ajax({
                url: url,
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",

                },
                success: function(data) {
                    $("#done").hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Request send successfully',
                        confirmButtonColor: '#3085D6',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        // Reload the page
                        location.reload();
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
            $("#uploaded_view").removeClass("show").find("img").remove();

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
                $('.submitform').html(
                    'Upload <div id="button-spinner" style="margin-left: 10px; width: 15px; height: 15px; display: none" class="spinner-border"></div>'
                ).attr('disabled', true);
                $('#button-spinner').show();
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
                        $('#button-spinner').hide();
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
