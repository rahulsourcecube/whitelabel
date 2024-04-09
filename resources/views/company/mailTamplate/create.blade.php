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
                    <span class="breadcrumb-item active">Add</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Add Mail Template</h4>
               
                <div class="m-t-50" style="">
                    <form id="mailTemplate" method="POST" action="{{ route('company.mail.template.store') }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <label for="type">Template Type <span class="error">*</span></label>
                               
                                <select id="type" name="type" class="form-control templateType" {{ !empty($mailTemplate)?'disabled':"";  }}>
                                    <option value="">Selcet Type
                                    </option>
                                    <option value="forgot_password" {{ !empty($mailTemplate) && $mailTemplate->template_type == 'forgot_password' ? 'selected' : '' }}>Forgot Password</option>
                                    <option  value="welcome"  {{ !empty($mailTemplate) && $mailTemplate->template_type == 'welcome' ? 'selected' : '' }}>Welcome
                                    </option>
                                </select>
                           
                                @if(!empty($mailTemplate) && !empty($mailTemplate->template_type))
                                    <input type="hidden" name="type" value="{{!empty($mailTemplate) && !empty($mailTemplate->template_type)?$mailTemplate->template_type : '' }}">
                           
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
                                <textarea type="text" class="form-control" id="tempHtml" name="tempHtml" placeholder="Html" >{{ !empty($mailTemplate) && !empty($mailTemplate->template_html)  ? $mailTemplate->template_html : '' }}</textarea>
                                
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
    <script>
        $(document).ready(function() {
           $('.htmltemplateClass').hide();
            window.onload = () => {
                    CKEDITOR.replace("tempHtml");
                };
    
            // Form validation
            $('#mailTemplate').validate({
                rules: {
                    type: {
                        required: true
                    },
                    tempHtml: {
                        required: true
                    }
                },
                messages: {
                    type: {
                        required: "Please select template type"
                    },
                    tempHtml: {
                        required: "Please enter HTML"
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
            $('.templateType').on('change', function() {
                var type = $(this).val();
                var html = "";
                if (type == 'welcome') {
                    html = "[user_name] [company_title] [company_logo]";
                   
                    $('.htmltemplateClass').show();
                } else if (type == 'forgot_password') {
                    html = "[user_name] [company_logo] [company_title] [route]";
                    $('.htmltemplateClass').show();
                } else {
                    $('.htmltemplateClass').hide();
                }
                $('.usedPoint').text(html);
            });
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
