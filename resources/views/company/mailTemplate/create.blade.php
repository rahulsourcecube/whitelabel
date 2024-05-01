@extends('company.layouts.master')
@section('title', 'Add Employee')
@section('main-content')
    <div class="main-content">
        @include('company.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <a href="{{ route('company.employee.list') }}" class="breadcrumb-item">Template</a>
                    <span class="breadcrumb-item active">{{ !empty($mailTemplate) ? 'Edit' : 'Add' }}</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>{{ !empty($mailTemplate) ? 'Edit' : 'Add' }} Mail Template</h4>

                <div class="m-t-50" style="">
                    <form id="mailTemplate" method="POST" action="{{ route('company.mail.template.store') }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="type">Template Type <span class="error">*</span></label>

                                <select id="type" name="type" class="form-control templateType"
                                    {{ !empty($mailTemplate) && $mailTemplate->template_type ? 'disabled' : '' }}>
                                    <option value="">Selcet Type
                                    </option>
                                    <option value="forgot_password"
                                        {{ (!empty($mailTemplate) && $mailTemplate->template_type == 'forgot_password') || (!empty(old('type')) && old('type') == 'forgot_password') ? 'selected' : '' }}>
                                        Forgot Password
                                    </option>

                                    <option value="welcome"
                                        {{ (!empty($mailTemplate) && $mailTemplate->template_type == 'welcome') || (!empty(old('type')) && old('type') == 'welcome') ? 'selected' : '' }}>
                                        Welcome
                                    </option>

                                    <option value="change_pass"
                                        {{ (!empty($mailTemplate) && $mailTemplate->template_type == 'change_pass') || (!empty(old('type')) && old('type') == 'change_pass') ? 'selected' : '' }}>
                                        Change password
                                    </option>
                                    <option value="new_task"
                                        {{ (!empty($mailTemplate) && $mailTemplate->template_type == 'new_task') || (!empty(old('type')) && old('type') == 'new_task') ? 'selected' : '' }}>
                                        New task
                                    </option>
                                    <option value="earn_reward"
                                        {{ (!empty($mailTemplate) && $mailTemplate->template_type == 'earn_reward') || (!empty(old('type')) && old('type') == 'earn_reward') ? 'selected' : '' }}>
                                        Earn reward
                                    </option>
                                </select>

                                @if (!empty($mailTemplate) && !empty($mailTemplate->template_type))
                                    <input type="hidden" name="type"
                                        value="{{ !empty($mailTemplate) && !empty($mailTemplate->template_type) ? $mailTemplate->template_type : '' }}">
                                    <input type="hidden" name="id"
                                        value="{{ !empty($mailTemplate) && !empty($mailTemplate->id) ? $mailTemplate->id : '' }}">
                                @endif


                            </div>
                            <div class="form-group col-md-4">
                                <label for="subject">Subject</label>
                                <input type="text" class="form-control" id="subject" name="subject"
                                    value="{{ !empty($mailTemplate) && !empty($mailTemplate->subject) ? $mailTemplate->subject : old('subject') }}"
                                    placeholder="Subject" maxlength="150">
                            </div>

                            <div class="form-group col-md-8 mt-2 htmltemplateClass">
                                <div class="alert alert-success" role="alert">
                                    <b>
                                        <p class="alert-heading usedPoint"> </p>
                                    </b>
                                    <p class="mb-0"></p>
                                </div>
                            </div>
                            <div class="form-group col-md-8">
                                <label for="tempHtml">Html</label>
                                {{-- <textarea type="text" class="form-control" id="tempHtml" name="tempHtml" placeholder="Html" >{{ !empty($mailTemplate) && !empty($mailTemplate->template_html)  ? $mailTemplate->template_html : '' }}</textarea> --}}
                                <textarea class="form-control ckeditor" id="tempHtml" name="tempHtml" placeholder="Html">{{ !empty($mailTemplate) && !empty($mailTemplate->template_html) ? $mailTemplate->template_html : '' }}</textarea>
                                @error('tempHtml')
                                    <label id="tempHtml-error" class="error" for="reward">The html field is required.
                                    </label>
                                @enderror
                            </div>


                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

    <script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {

            if (!CKEDITOR.instances['tempHtml']) {
                CKEDITOR.replace("tempHtml");
            }
            // Add custom validation method for CKEditor textarea
            jQuery.validator.addMethod("ckeditorContent", function(value, element) {
                // Get CKEditor instance
                var ckeditorInstance = CKEDITOR.instances[element.id];

                // Check if CKEditor instance has content
                return ckeditorInstance && ckeditorInstance.getData().trim() !== '';
            }, "Please enter HTML");

            // Form validation
            $('#mailTemplate').validate({
                rules: {
                    type: {
                        required: true
                    },
                    subject: {
                        required: true
                    }

                },
                messages: {
                    type: {
                        required: "Please select template type"
                    },
                    subject: {
                        required: "Please select template subject"
                    }

                },
                // Optional: Highlight and unhighlight fields
                highlight: function(element) {
                    $(element).closest('.form-group').addClass('has-error');
                },
                unhighlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-error');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            updateTemplate($('.templateType').val());


            $('.templateType').on('change', function() {
                var type = $(this).val();

                updateTemplate(type);
            });

            function updateTemplate(type) {
                var html = "";
                if (type == 'welcome') {
                    html = "[user_name] [company_title] [company_logo] [company_web_link] ";
                    $('.htmltemplateClass').show();
                } else if (type == 'forgot_password') {
                    html =
                        "[user_name] [company_logo] [company_title] [company_web_link] [change_password_link] ";
                    $('.htmltemplateClass').show();
                } else if (type == 'change_pass') {
                    html = "[user_name] [company_logo] [company_title] [company_web_link] ";
                    $('.htmltemplateClass').show();
                } else if (type == 'new_task') {
                    html =
                        "[user_name] [company_logo] [company_title] [company_web_link] [campaign_title] [campaign_price] [campaign_join_link]  ";
                    $('.htmltemplateClass').show();
                } else if (type == 'earn_reward') {
                    html =
                        "[user_name] [company_logo] [company_title] [company_web_link] [campaign_title] [campaign_price]";
                    $('.htmltemplateClass').show();
                } else {
                    $('.htmltemplateClass').hide();
                }

                // Update the text inside elements with class 'usedPoint'
                $('.usedPoint').text(html);
            }
        });
    </script>

    {{-- <script>
        $(document).ready(function() {
            // Define the variable
            var $name = "test";

            // Function to find and replace text
            function findAndReplaceText() {
                // Select the elements containing the text "user_name" and replace it with the value of $name
                $("body").find("*").contents().filter(function() {
                    return this.nodeType === 3;
                }).each(function() {
                    $(this).replaceWith($(this).text().replace(/user_name/g, $name));
                });
            }

            // Call the function to perform the replacement
            findAndReplaceText();
        });
    </script> --}}
@endsection
