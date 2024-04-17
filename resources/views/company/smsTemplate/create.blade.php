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
                    <span class="breadcrumb-item active">{{!empty($SmsTemplate)?'Edit': "Add"}}</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>{{!empty($SmsTemplate)?'Edit': "Add"}} Sms Template</h4>
               
                <div class="m-t-50" style="">
                    <form id="mailTemplate" method="POST" action="{{ route('company.sms.template.store') }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <label for="type">Template Type <span class="error">*</span></label>
                               
                                <select id="type" name="type" class="form-control templateType" {{ !empty($SmsTemplate) && $SmsTemplate->template_type?'disabled':"";  }}>
                                    <option value="">Selcet Type
                                    </option>
                                        <option value="forgot_password" {{ !empty($SmsTemplate) && $SmsTemplate->template_type == 'forgot_password' ? 'selected' : '' }}>Forgot Password</option>
                                        <option  value="welcome"  {{ !empty($SmsTemplate) && $SmsTemplate->template_type == 'welcome' ? 'selected' : '' }}>Welcome
                                        <option  value="change_pass"  {{ !empty($SmsTemplate) && $SmsTemplate->template_type == 'change_pass' ? 'selected' : '' }}>Change password
                                    </option>
                                </select>
                           
                                @if(!empty($SmsTemplate) && !empty($SmsTemplate->template_type))
                                <input type="hidden" name="type" value="{{!empty($SmsTemplate) && !empty($SmsTemplate->template_type)?$SmsTemplate->template_type : '' }}">
                                <input type="hidden" name="id" value="{{!empty($SmsTemplate) && !empty($SmsTemplate->id)?$SmsTemplate->id : '' }}">
                           
                               @endif 
                                

                            </div>
                          
                           
                            <div class="form-group col-md-8 mt-2 htmltemplateClass">
                                <div class="alert alert-success" role="alert">
                                    <b><p class="alert-heading usedPoint" > </p></b>
                                    <p class="mb-0"></p>
                                </div>
                            </div>
                            <div class="form-group col-md-8">
                                <label for="tempHtml">Html</label>
                                {{-- <textarea type="text" class="form-control" id="tempHtml" name="tempHtml" placeholder="Html" >{{ !empty($SmsTemplate) && !empty($SmsTemplate->template_html)  ? $SmsTemplate->template_html : '' }}</textarea> --}}
                                <textarea class="form-control ckeditor" id="tempHtml" name="tempHtml" placeholder="Html">{{ !empty($SmsTemplate) && !empty($SmsTemplate->template_html_sms) ? $SmsTemplate->template_html_sms : '' }}</textarea>

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
                    tempHtml: {
                        ckeditorContent: true  // Use custom validation method for CKEditor
                    }
                },
                messages: {
                    type: {
                        required: "Please select template type"
                    },
                    tempHtml: {
                        ckeditorContent: "Please enter HTML"  // Custom error message for CKEditor
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
                    html = "[user_name] [company_title] [company_web_link]";
                    $('.htmltemplateClass').show();
                } else if (type == 'forgot_password') {
                    html = "[user_name]  [company_title] [company_web_link] [change_password_link] ";
                    $('.htmltemplateClass').show();
                }  else if (type == 'change_pass') {
                    html = "[user_name]  [company_title] [company_web_link] ";
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
