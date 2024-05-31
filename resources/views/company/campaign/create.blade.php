<?php $ActivePackageData = App\Helpers\Helper::GetActivePackageData(); ?>
@extends('company.layouts.master')
@section('title', 'Add Task')
@section('main-content')
    <div class="main-content">
        @include('company.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <a href="{{ route('admin.package.list') }}" class="breadcrumb-item">{{ $typeInText }} Task</a>
                    <span class="breadcrumb-item active">Add</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Add {{ $typeInText }} Task</h4>
                <div class="m-t-50" style="">
                    <form id="taskadd" method="POST" action="{{ route('company.campaign.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="title">Task Titles <span class="error">*</span></label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Task Title" maxlength="150" value="{{ old('title') }}">
                                @error('title')
                                    <label id="title-error" class="error" for="title">{{ $message }}</label>
                                @enderror
                            </div>
                            {{-- <div class="form-group col-md-6">
                            <label for="reward"> Reward <span class="error">*</span></label>
                            <input type="text" class="form-control" id="reward" name="reward" placeholder="Reward"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                value="{{ old('reward') }}" min="1">
                            @error('reward')
                            <label id="reward-error" class="error" for="reward">{{ $message }}</label>
                            @enderror
                        </div> --}}
                            <div class="col-md-6">
                                <div class="int-reward w-100">
                                    <label for="reward"> Reward <span class="error">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"
                                                id="basic-addon2">{{ App\Helpers\Helper::getcurrency() }}</span>
                                        </div>
                                        <input type="number" class="form-control" id="reward" name="reward"
                                            placeholder="Reward"
                                            onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                            value="{{ old('reward') }}" min="1">
                                    </div>
                                    @error('reward')
                                        <label id="reward-error" class="error" for="reward">{{ $message }}</label>
                                    @else
                                        <label id="reward-error" class="error" for="reward"></label>
                                    @enderror
                                </div>
                                <div class="custom-reward-text w-100" style="display: none;">
                                    <label for="text_reward"> Custom Reward Title <span class="error">*</span></label>
                                    <input type="text" name="text_reward" class="form-control" id="text_reward"
                                        maxlength="250" required>
                                    @error('text_reward')
                                        <label id="text_reward-error" class="error"
                                            for="text_reward">{{ $message }}</label>
                                    @else
                                        <label id="text_reward-error" class="error" for="text_reward"></label>
                                    @enderror
                                </div>
                                <div class="custom-reward-chk w-100  mt-2">
                                    <label for="custom_reward_chk"> Custom Reward</label>
                                    <input type="checkbox" name="custom_reward_chk" id="custom_reward_chk" value="1">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="description">Description</label>
                                <textarea type="text" class="form-control" id="description" name="description" placeholder="description">{{ old('description') }}</textarea>
                                @error('description')
                                    <label id="description-error" class="error" for="description">{{ $message }}</label>
                                @enderror
                            </div>
                            @if (isset($typeInText) && $typeInText == 'Referral')
                                <div class="form-group col-md-4">
                                    <label for="no_of_referral_users"> No of referral users <span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control" id="no_of_referral_users"
                                        name="no_of_referral_users" placeholder="No of referral users"
                                        onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                        value="{{ old('no_of_referral_users') }}" min="1">
                                    @error('no_of_referral_users')
                                        <label id="no_of_referral_users-error" class="error"
                                            for="reward">{{ $message }}</label>
                                    @enderror
                                </div>
                            @endif
                            <div class="form-group col-md-4">
                                <label for="priority">Priority<span class="error">*</span></label>
                                <select name="priority" id="priority" class="form-control">

                                    <option value="1">High</option>
                                    <option value="2">Medium</option>
                                    <option value="3" selected>Low</option>
                                </select>

                                @error('priority')
                                    <label id="priority-error" class="error" for="priority">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="expiry_date">End date <span class="error">*</span></label>
                                <input type="date" class="form-control" id="expiry_date" name="expiry_date"
                                    placeholder="No Of Task"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                    value="{{ old('expiry_date') }}" min="{{ date('Y-m-d') }}">
                                @error('expiry_date')
                                    <label id="expiry_date-error" class="error"
                                        for="expiry_date">{{ $message }}</label>
                                @enderror
                            </div>
                            <input type="hidden" name="type" value="{{ $type }}">


                        </div>
                        <div class="row">
                            <div class="col-md-1 pl-4">
                                <label for="expiry_date">Status</label>
                                <div class="form-group align-items-center">
                                    <div class="switch m-r-10">
                                        <input type="checkbox" id="switch-1" data-toggle="switch" name="status"
                                            value="true" checked>
                                        <label for="switch-1"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 pl-4">
                                <label for="expiry_date">Public</label>
                                <div class="form-group align-items-center">
                                    <div class="switch m-r-10">
                                        <input type="checkbox" id="public-1" data-toggle="switch" name="public"
                                            value="true" onclick='handleClickpublic(this)';>
                                        <label for="public-1"></label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-3">
                                <label class="font-weight-semibold" for="country">Country:</label>
                                <select name="country" id="country" class="form-control">
                                    <option value="">Select Country</option>
                                    @if ($country_data)
                                        @foreach ($country_data as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach

                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-semibold" for="state">State:</label>
                                <select name="state" id="state" class="form-control">
                                    <option value="">Select State</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-semibold" for="city">City:</label>
                                <select name="city" id="city" class="form-control">
                                    <option value="">Select City</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="file">Image <span class="error">*</span></label>
                                <input type="file" class="form-control" name="image" id="file"
                                    accept=".png, .jpg, .jpeg" onchange="previewImage()" required>
                                @error('image')
                                    <label id="image-error" class="error" for="image">{{ $message }}</label>
                                @enderror
                            </div>
                            @if (isset($typeInText) && $typeInText == 'Referral')
                                <div class="form-group col-md-6">
                                    <label for="referral_url">URl Setting <span class="error"></span></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-append">
                                            <span class="input-group-text"
                                                id="basic-addon2">{{ 'XYZ.' . App\Helpers\Helper::mainDomain() . '/4TvDSrAhrC' }}</span>
                                        </div>
                                        <input type="text" name="referral_url" class="form-control" id="referral_url"
                                            maxlength="250" oninput="this.value = this.value.replace(/\s/g, '');">
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3" style="max-height: 200px;">
                                <img id="imagePreview" src="#" alt="Image Preview"
                                    style="max-width: 100%; max-height: 80%;display: none;">
                                <button type="button" id="deleteImageButton" class="btn btn-danger btn-sm mt-2"
                                    style="display: none;" onclick="deleteImage()"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <h4>Feedback Management</h4>
                                <label for="feedback_type">Feedback Type <span class="error">*</span></label>
                                <select id="feedback_type" name="feedback_type" class="form-control type">
                                    <option value="rating">Rating
                                    </option>
                                    <option selected value="description">Description
                                    </option>
                                    <option value="both">Both
                                    </option>
                                </select>
                            </div>


                            @if (
                                (!empty($mail) &&
                                    $ActivePackageData->sms_temp_status == '1' &&
                                    !empty($smsTemplate) &&
                                    !empty($smsTemplate->template_html_sms) &&
                                    !empty($mail->sms_account_sid) &&
                                    !empty($mail->sms_account_token) &&
                                    !empty($mail->sms_account_number)) ||
                                    (!empty($mail) &&
                                        $ActivePackageData->mail_temp_status == '1' &&
                                        !empty($mailTemplate) &&
                                        !empty($mailTemplate->template_html) &&
                                        !empty($mail->mail_username) &&
                                        !empty($mail->mail_host) &&
                                        !empty($mail->mail_password)))
                                <div class="form-group col-md-6">
                                    <h4>Real-Time Task Notifications</h4>
                                    <label for="notifications_type">Notifications Type <span
                                            class="error"></span></label>
                                    <select id="notifications_type" name="notifications_type" class="form-control type">
                                        <option value="">Select Type
                                        </option>
                                        @if (
                                            $ActivePackageData->mail_temp_status == '1' &&
                                                !empty($mail) &&
                                                !empty($mailTemplate) &&
                                                !empty($mailTemplate->template_html) &&
                                                !empty($mail->mail_username) &&
                                                !empty($mail->mail_host) &&
                                                !empty($mail->mail_password))
                                            <option value="1">Mail
                                            </option>
                                        @endif
                                        @if (
                                            $ActivePackageData->sms_temp_status == '1' &&
                                                !empty($mail) &&
                                                !empty($smsTemplate) &&
                                                !empty($smsTemplate->template_html_sms) &&
                                                !empty($mail->sms_account_sid) &&
                                                !empty($mail->sms_account_token) &&
                                                !empty($mail->sms_account_number))
                                            <option value="2">SMS
                                            </option>
                                        @endif
                                        @if (
                                            !empty($mail) &&
                                                !empty($smsTemplate) &&
                                                !empty($smsTemplate->template_html_sms) &&
                                                $ActivePackageData->sms_temp_status == '1' &&
                                                $ActivePackageData->mail_temp_status == '1' &&
                                                !empty($mail->sms_account_sid) &&
                                                !empty($mail->sms_account_token) &&
                                                !empty($mail->sms_account_number) &&
                                                (!empty($mail) &&
                                                    !empty($mailTemplate) &&
                                                    !empty($mailTemplate->template_html) &&
                                                    !empty($mail->mail_username) &&
                                                    !empty($mail->mail_host) &&
                                                    !empty($mail->mail_password)))
                                            <option value="3">Both (Mail and SMS)
                                            </option>
                                        @endif
                                    </select>
                                </div>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection
@section('js')
    <script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>

    <script>
        function handleClickpublic(checkbox) {
            var isChecked = checkbox.checked;
            var message = isChecked ? 'make it public' : 'make it uncheck';

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to ' + message + ', right?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, ' + message + '!'
            }).then((result) => {
                checkbox.checked = result.isConfirmed ? isChecked : !isChecked;
            });
        }

        function handleClick(cb) {
            cb.value = cb.checked ? 1 : 0;
            // console.log(cb.value);
        }
        $('#taskadd').validate({
            rules: {
                title: {
                    required: true
                },
                reward: {
                    required: true
                },

                no_of_referral_users: {
                    required: true
                },
                description: {
                    required: true
                },
                type: {
                    required: true
                },
                expiry_date: {
                    required: true
                },
                image: {
                    fileExtension: true,
                    fileSize: true,
                },
            },
            messages: {
                title: {
                    required: "Please enter title"
                },
                reward: {
                    required: "Please enter reward"
                },

                no_of_referral_users: {
                    required: "Please enter no of referral users"
                },
                description: {
                    required: "Please enter description"
                },
                type: {
                    required: "Please select task type"
                },
                expiry_date: {
                    required: "Please select end date"
                },
            }
        });
        $("#custom_reward_chk").on("click", function() {
            $(".custom-reward-text, .int-reward").toggle();
            $("#text_reward, #reward").val("");
        })
    </script>
@endsection
