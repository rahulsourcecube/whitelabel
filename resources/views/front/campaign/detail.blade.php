@extends('front.layouts.master')
@section('title', 'Task')
@section('main-content')




    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-content w-40 m-auto">
                        @if (isset($campagin_detail) &&
                                $campagin_detail->image != '' &&
                                file_exists(base_path() . '/uploads/company/campaign/' . $campagin_detail->image))
                            <img src="{{ asset('uploads/company/campaign/' . $campagin_detail->image) }}"
                                class="img-responsive w-100">
                        @else
                            <img src="{{ asset('assets/images/others/No_image_available.png') }}"
                                class="img-responsive w-100">
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



                            <a href="{{ route('front.campaign.Join', base64_encode($campagin_detail->id)) }}"
                                class="btn btn-primary btn-tone" id="btnJoined">
                                <span class="m-l-5">Join Now</span>
                            </a>


                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-12">
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
        </div>

        <input type="hidden" id="status" value="1">
    </div>

@endsection
