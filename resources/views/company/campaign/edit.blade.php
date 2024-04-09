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
                <a href="{{ route('admin.package.list') }}" class="breadcrumb-item">Task</a>
                <span class="breadcrumb-item active">Edit</span>
            </nav>
        </div>
    </div>
 
    <div class="card">
        <div class="card-body">
            <h4>Edit Task</h4>
            <div class="m-t-50" style="">
                <form id="taskadd" method="POST" action="{{ route('company.campaign.update', $task->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="title">Task Titles <span class="error">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Task Title"
                                maxlength="150" value="{{$task->title ?? ''}}">
                            @error('title')
                            <label id="title-error" class="error" for="title">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            {{-- <label for="reward"> Reward <span class="error">*</span></label>
                            <input type="text" class="form-control" id="reward" name="reward" placeholder="Reward"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                value="{{$task->reward ?? ''}}">
                            @error('reward')
                            <label id="reward-error" class="error" for="reward">{{ $message }}</label>
                            @enderror --}}
                            <div class="int-reward w-80" @if ($task->text_reward) style="display: none;" @endif>
                                <label for="reward"> Reward <span class="error">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon2">{{ App\Helpers\Helper::getcurrency() }}</span>
                                    </div>
                                    <input type="number" class="form-control" id="reward" name="reward" placeholder="Reward"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                    value="{{$task->reward ?? ''}}" min="1">
                                </div>
                                @error('reward')
                                    <label id="reward-error" class="error" for="reward">{{ $message }}</label>
                                @else
                                    <label id="reward-error" class="error" for="reward"></label>
                                @enderror
                            </div>
                            <div class="custom-reward-text w-80" @if (!$task->text_reward) style="display: none;" @endif>
                                <label for="text_reward"> Custom Reward Title <span class="error">*</span></label>
                                <input type="text" name="text_reward" class="form-control" id="text_reward" maxlength="250"  value="{{$task->text_reward ?? ''}}" required>
                                @error('text_reward')
                                    <label id="text_reward-error" class="error" for="text_reward">{{ $message }}</label>
                                @else
                                    <label id="text_reward-error" class="error" for="text_reward"></label>
                                @enderror
                            </div>
                            <div class="custom-reward-chk w-20  mt-2">
                                <label for="custom_reward_chk"> Custom Reward</label>
                                <input type="checkbox" name="custom_reward_chk" id="custom_reward_chk" value="1" @if ($task->text_reward) checked @endif>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="description">Description</label>
                            <textarea type="text" class="form-control" id="description" name="description"
                                placeholder="description">{{ $task->description ?? ''}}</textarea>
                            @error('description')
                            <label id="description-error" class="error" for="description">{{ $message }}</label>
                            @enderror
                        </div>
                        @if(isset($type) && $type == "1")
                         <div class="form-group col-md-4">
                            <label for="no_of_referral_users"> No of referral users <span class="error">*</span></label>
                            <input type="text" class="form-control" id="no_of_referral_users" name="no_of_referral_users" placeholder="No of referral users"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                value="{{ $task->no_of_referral_users ?? '0'}}">
                            @error('no_of_referral_users')
                            <label id="no_of_referral_users-error" class="error" for="reward">{{ $message }}</label>
                            @enderror
                        </div>
                        @endif
                        <div class="form-group col-md-4">
                            <label for="expiry_date">End date <span class="error">*</span></label>
                            <input type="date" class="form-control" id="expiry_date" name="expiry_date"
                                placeholder="No Of Task"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                value="{{ $task->expiry_date ?? '' }}" min="{{ date($task->expiry_date) }}">
                            @error('expiry_date')
                            <label id="expiry_date-error" class="error" for="expiry_date">{{ $message }}</label>
                            @enderror
                        </div>
                        <input type="hidden" name="type" value="{{ $type }}">
                        <div class="col-md-4 pl-5">
                            <label for="expiry_date">Status</label>
                            <div class="form-group align-items-center">
                                <div class="switch m-r-10">
                                    <input type="checkbox" id="switch-1" name="status" value="true" @if (isset( $task->status) && $task->status == 1 ) checked="" @endif>
                                    <label for="switch-1"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="file">Image <span class="error">*</span></label>
                            <input type="file" class="form-control" name="image" id="file" accept=".png, .jpg, .jpeg"
                                onchange="previewImage()">
                            @error('image')
                            <label id="image-error" class="error" for="image">{{ $message }}</label>
                            @enderror
                        </div>
                        @if(isset($type) && $type == "1")
                            <div class="form-group col-md-6">
                                <label for="referral_url">URl Setting <span class="error"></span></label>

                                <input type="text" name="referral_url" class="form-control" value="{{$task->referral_url_segment ?? ''}}" id="referral_url" maxlength="250"  oninput="this.value = this.value.replace(/\s/g, '');">
                      
                            </div>
                        @endif
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-3" style="max-height: 200px;">
                            @if (isset($task) && !empty($task->image) && file_exists(base_path('uploads/company/campaign/' . $task->image)))
                                    <img id="imagePreview"
                                        src="{{ asset('uploads/company/campaign/' . $task->image) }}"
                                        alt="Image Preview" style="max-width: 100%; max-height: 80%;">
                                @else
                                    <img id="imagePreview" src="#" alt="Image Preview"
                                        style="max-width: 100%; max-height: 80%; display: none;">
                                    <button type="button" id="deleteImageButton" class="btn btn-danger btn-sm mt-2"
                                        style="display: none;" onclick="deleteImage()"><i
                                            class="fa fa-trash"></i></button>
                                @endif
                        </div>
                    </div>
                    <hr>
                <h4>Feedback Management</h4>
                <div class="form-group col-md-3">
                    <label for="feedback_type">Feedback Type <span class="error">*</span></label>
                    <select id="feedback_type" name="feedback_type" class="form-control type">

                        <option  value="rating"  {{ !empty($task->feedback_type) && $task->feedback_type == 'rating' ? 'selected' : '' }} >Rating </option>
                        <option  value="description"  {{ !empty($task->feedback_type) && $task->feedback_type == 'description' ? 'selected' : '' }} >Description</option>
                        <option value="both"  {{ !empty($task->feedback_type) && $task->feedback_type == 'both' ? 'selected' : '' }}>Both</option>
                    </select>
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
        $('#taskadd').validate({
            rules: {
                title: {
                    required: true
                },
                reward: {
                    required: true
                },
                description: {
                    required: true
                },
                type: {
                    required: true
                },
                no_of_referral_users: {
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
        
        $("#custom_reward_chk").on("click", function ()  {
            $(".custom-reward-text, .int-reward").toggle();
            $("#text_reward, #reward").val("");
        })
        CKEDITOR.replace("description");

</script>
@endsection