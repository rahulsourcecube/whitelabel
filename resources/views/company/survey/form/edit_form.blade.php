@extends('company.layouts.master')
@section('title', 'edit Employee')
@section('main-content')
    <div class="main-content">
        @include('company.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <a href="{{ route('company.employee.list') }}" class="breadcrumb-item">Survey Form</a>
                    <span class="breadcrumb-item active">Edit</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Edit Survey Form</h4>
                <div class="m-t-50" style="">
                    <form id="surveyForm" method="POST" action="{{ route('company.survey.form.updateform') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="title" class="col-sm-3 col-form-label">Title</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="survey_title" id="title"
                                            value="{{ $surveyFiled->title }}" placeholder="Enter Title">
                                        <input type="hidden" class="form-" name="id" id="id"
                                            value="{{ $surveyFiled->id }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-md-6">

                                <div class="form-group row">
                                    <label for="type" class="col-sm-3 col-form-label">Type</label>
                                    <div class="col-sm-9">
                                        <select id="type" name="type" class="form-control templateType"
                                            {{ !empty($SmsTemplate) && $SmsTemplate->template_type ? 'disabled' : '' }}>
                                            <option value="">Select Type</option>
                                            <option value="text">Text</option>
                                            <option value="number">Number</option>
                                            {{-- <option value="textarea">Textarea</option> --}}
                                            <option value="select">Select</option>
                                            <option value="radio">Radio</option>
                                            <option value="checkbox">Checkbox</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="label" class="col-sm-3 col-form-label">Label</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="label" id="label"
                                            placeholder="Enter Label">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputName" class="col-sm-3 col-form-label">Name</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="inputName" id="inputName"
                                            placeholder="Enter Name">
                                    </div>
                                </div>
                                <!-- Add more fields as needed -->
                            </div>
                            <div class="col-md-6">

                                <div class="form-group row">
                                    <label for="idname" class="col-sm-3 col-form-label">Id Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="idname" id="idname"
                                            placeholder="Enter ID Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="class" class="col-sm-3 col-form-label">Class Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="class" id="class"
                                            placeholder="Enter Class Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="placeholder" class="col-sm-3 col-form-label">Placeholder</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="placeholder" id="placeholder"
                                            placeholder="Enter Placeholder">
                                    </div>
                                </div>
                                <!-- Add more fields as needed -->
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <button type="submit" id="add_more" class="add_more">Add More</button>
                        </div> --}}
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
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
        if (!CKEDITOR.instances['ckeditor']) {
            CKEDITOR.replace("ckeditor");
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#surveyForm').validate({
                rules: {
                    survey_title: 'required',
                    type: 'required',
                    label: 'required',
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
                    survey_title: 'Please enter a survey title',
                    type: 'Please select a type',
                    label: 'Please enter a label',
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
    <script>
        $(document).ready(function() {
            $('#type').change(function() {
                var selectedType = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('company.survey.form.addfield') }}',
                    data: {
                        type: selectedType
                    },
                    success: function(response) {
                        $('#additionalFieldsContainer').html(response.additionalFields);
                    }
                });
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
