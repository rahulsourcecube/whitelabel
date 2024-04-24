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
                    <span class="breadcrumb-item active">Add</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Add Survey Form</h4>

                <div class="m-t-50" style="">
                    <form id="surveyForm" method="POST" action="{{ route('company.survey.form.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="title" class="col-sm-3 col-form-label">Title</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="survey_title" id="title" placeholder="Enter Title">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="btn btn-primary float-right addFiledMore">Add More</span>
                        <input type="hidden" class="addMoreCount" value="0">
                        <div class="form-group row ">
                            <div class="col-md-6">
                                <label for="type" class="col-form-label">Type</label>
                                <select id="type" name="type[]" class="form-control templateType type" onchange="onchangeType(this)" data-count="0">
                                    <option value="">Select Type</option>
                                    <option value="text">Text</option>
                                    <option value="number">Number</option>
                                    <option value="textarea">Textarea</option>
                                    <option value="select">Select</option>
                                    <option value="radio">Radio</option>
                                    <option value="checkbox">Checkbox</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="label" class=" col-form-label">Label</label>
                                <input type="text" class="form-control" name="label[]" id="label" placeholder="Enter Label">
                            </div>

                            <div class="col-sm-6">
                                <label for="placeholder" class=" col-form-label">Placeholder</label>
                                <input type="text" class="form-control" name="placeholder[]" id="placeholder" placeholder="Enter Placeholder">
                            </div>
                        </div>
                        <div id="additionalFieldsContainer0">

                        </div>

                        <div id="addFiledMore0">

                        </div>
                        {{-- <div class="col-md-6">
                            <button type="submit" id="add_more" class="add_more">Add More</button>
                        </div> --}}
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

    <script>
        // function onchangeType(selectElement) {
        //     var selectedValue = $(selectElement).val();
        //     console.log("Selected value:", selectedValue);
        //     // Add your code to handle the change here
        // }

        function onchangeType(selectElement) {
            var oldCount = $('.addMoreCount').val();
            var dataCount = $(selectElement).data("count");
            var selectedType = $(selectElement).val();

            $.ajax({
                type: 'GET',
                url: '{{ route('company.survey.form.addfield') }}',
                data: {
                    type: selectedType,
                    addCount: dataCount,
                    addrequest: 'addrequest' // Corrected spelling and added comma after addCount
                },
                success: function(response) {
                    $('#additionalFieldsContainer' + dataCount).html(response.additionalFields);
                }
            });
        }

        function addFiledType(typecount, type) {
            var typecount = typecount;
            var selectedType = type;

            $.ajax({
                type: 'GET',
                url: '{{ route('company.survey.form.addfield') }}',
                data: {
                    type: selectedType,
                    addCount: typecount
                },
                success: function(response) {
                    $('#additionalFieldsContainer' + typecount).append(response.additionalFields);

                }
            });

        }


        function removeFiledType() {
            $(event.target).closest('.form-group').remove();
        }

        $(document).ready(function() {
            $('.addFiledMore').click(function() {
                var oldCount = parseInt($('.addMoreCount').val()); // Parse the old count as an integer
                $('.addMoreCount').val(oldCount + 1); // Increment the count
                var selectedType = 'addMore';
                $.ajax({
                    type: 'GET',
                    url: '{{ route('company.survey.form.addfield') }}',
                    data: {
                        type: selectedType,
                        addCount: oldCount + 1
                    },
                    success: function(response) {
                        $('#addFiledMore' + oldCount).append(response.additionalFields);
                        // $('.type').change(function() {
                        //     oldCount = "0";
                        //     var oldCount = $('.addMoreCount').val();
                        //     var dataCount = $(this).data("count");
                        //     var selectedType = $(this).val();
                        //     $.ajax({
                        //         type: 'GET',
                        //         url: '{{ route('company.survey.form.addfield') }}',
                        //         data: {
                        //             type: selectedType,
                        //             addCount: dataCount
                        //         },
                        //         success: function(response) {

                        //             $('#additionalFieldsContainer' + dataCount).html(response.additionalFields);


                        //         }
                        //     });
                        // });
                        $('.addFiledRemove').click(function() {
                            var removeCount = $(this).data("removecount"); // Then remove the parent container itself
                            alert('#additionalFieldsContainer' + removeCount)
                            $('#additionalFieldsContainer' + removeCount).remove();
                            $(this).next('.form-group.row').remove();
                            $(this).remove();
                        }); // Append the response to the correct element

                        $("input[name='label[]']").each(function() {
                            $(this).rules("add", {
                                required: true,
                                messages: {
                                    required: "This field is required"
                                }
                            });
                        });
                    }
                });
            });
            //Radio
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#submit').click(function() {
                $('#surveyForm').validate({
                    rules: {
                        survey_title: 'required',
                        // Add custom rule for validating at least one type is selected
                        'type[]': {
                            required: function(element) {
                                return $('[name="type[]"]').filter(':checked').length === 0;
                            }
                        },
                        // Add custom rule for validating each label input in the array
                        'label[]': {
                            required: true
                        },
                        // Add custom rule for validating each inputName input in the array
                        'inputName[]': {
                            required: true
                        },
                        // Add custom rule for validating each placeholder input in the array
                        'placeholder[]': {
                            required: true
                        }
                    },
                    messages: {
                        survey_title: 'Please enter a survey title',
                        'type[]': 'Please select at least one type',
                        'label[]': 'Please enter a label',
                        'inputName[]': 'Please enter a name',
                        'placeholder[]': 'Please enter a placeholder'
                    },
                    // Handle form submission
                    submitHandler: function(form) {
                        form.submit();
                    }
                });
            });
        });
    </script>

    <script>
        // $(document).ready(function() {
        //     $('.type').change(function() {
        //         oldCount = "0";
        //         var oldCount = $('.addMoreCount').val();
        //         var dataCount = $(this).data("count");
        //         var selectedType = $(this).val();

        //         $.ajax({
        //             type: 'GET',
        //             url: '{{ route('company.survey.form.addfield') }}',
        //             data: {
        //                 type: selectedType,
        //                 addCount: dataCount,
        //                 addreqest: 'addreqest'
        //             },
        //             success: function(response) {
        //                 $('#additionalFieldsContainer' + dataCount).html(response.additionalFields);
        //             }
        //         });
        //     });
        // });
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
