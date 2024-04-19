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
                    <a href="{{ route('company.employee.list') }}" class="breadcrumb-item">Survey Form</a>
                    <span class="breadcrumb-item active">{{ !empty($SmsTemplate) ? 'Edit' : 'Add' }}</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>{{ !empty($SmsTemplate) ? 'Edit' : 'Add' }} Survey Form</h4>
                @if (empty($surveyFiled) )
                      <div class="m-t-50" style="">
                          <form id="surveyForm" method="POST" action="{{ route('company.survey.form.store') }}">
                              @csrf
                              <div class="form-group row col-md-6">
                                  <label for="title" class="form-label">Title</label>
                                  <input type="text" class="form-control" name="survey_title" id="title"
                                      placeholder="Enter Title">
                              </div>
                              <div class="form-group row col-md-6">
                                  <button type="submit" class="btn btn-primary">Submit</button>
                              </div>
                          </form>
                    @else
                        <form id="surveyForm" method="POST" action="{{ route('company.survey.form.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Type</label>
                                <div class="col-sm-10">
                                    <select id="type" name="type" class="form-control templateType"
                                        {{ !empty($SmsTemplate) && $SmsTemplate->template_type ? 'disabled' : '' }}>
                                        <option value="">Selcet Type</option>
                                        <option value="text">Text</option>
                                        <option value="number">Number</option>
                                        <option value="textarea">Textarea</option>
                                        <option value="select">Select</option>
                                        <option value="redio">Redio</option>
                                        <option value="cehckbox">Cehckbox</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="lable" class="col-sm-2 col-form-label">Lable lable</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name ="lable" id="lable"
                                        placeholder="Enter Lable lable">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputName" id="inputName"
                                        placeholder="Enter Name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="idname" class="col-sm-2 col-form-label">Id Nmae</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="idname" id="idname"
                                        placeholder="Enter id name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Class Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="class" id="class"
                                        placeholder="Enter Class Name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="placeholder" class="col-sm-2 col-form-label">Placeholder</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="placeholder" id="placeholder"
                                        placeholder="Enter Placeholder">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="position" class="col-sm-2 col-form-label">Position</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="position" id="position"
                                        placeholder="Enter position">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                @endif
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
            $('#surveyForm').validate({
                rules: {
                    type: 'required',
                    lable: 'required',
                    inputName: 'required',
                    idname: 'required',
                    class: 'required',
                    placeholder: 'required',
                    position: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    type: 'Please select a type',
                    lable: 'Please enter a label',
                    inputName: 'Please enter a name',
                    idname: 'Please enter an ID name',
                    class: 'Please enter a class name',
                    placeholder: 'Please enter a placeholder',
                    position: {
                        required: 'Please enter a position',
                        number: 'Position must be a number'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
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
                        ckeditorContent: true // Use custom validation method for CKEditor
                    }
                },
                messages: {
                    type: {
                        required: "Please select template type"
                    },
                    tempHtml: {
                        ckeditorContent: "Please enter HTML" // Custom error message for CKEditor
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
                } else if (type == 'change_pass') {
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
