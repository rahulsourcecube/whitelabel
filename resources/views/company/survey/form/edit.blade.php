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
                    <span class="breadcrumb-item active">Edit</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Edit Survey Form</h4>

                <div class="m-t-50" style="">
                    <form id="surveyForm" method="POST" action="{{ route('company.survey.form.update', $surveyFiled->id) }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="title" class="col-sm-3 col-form-label">Title</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="survey_title" id="title"
                                            value="{{ !empty($surveyFiled) && !empty($surveyFiled->title) ? $surveyFiled->title : '' }}" placeholder="Enter Title">
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <span class="btn btn-primary float-right addFiledMore">Add More</span> --}}

                        @if (!empty($surveyFiled) && !empty($surveyFiled->fields))
                            @php
                                $fieldData = json_decode($surveyFiled->fields, true);
                                $count = 0;
                            @endphp

                            @foreach ($fieldData as $key => $field)
                                @if ($key == '0')
                                    <span class="btn btn-primary float-right addFiledMore">Add More</span>
                                @else
                                    <span class="btn btn-danger float-right addFiledRemove" onclick="addFiledRemove(this)" data-removeCount="{{ $key }}"><i
                                            class="fa fa-trash"></i></span>
                                @endif
                                <div class="form-group row ">
                                    <div class="col-md-6">
                                        <label for="type" class="col-form-label">Type</label>
                                        <select id="type" name="type[]" onchange="onchangeType(this,{{ $key }})" data-count="{{ $key }}"
                                            class="form-control templateType">
                                            <option value="">Select Type</option>
                                            <option value="text" <?php if ($field['type'] == 'text') {
                                                echo 'selected';
                                            } ?>>Text</option>
                                            <option value="number" <?php if ($field['type'] == 'number') {
                                                echo 'selected';
                                            } ?>>Number</option>
                                            <option value="textarea" <?php if ($field['type'] == 'textarea') {
                                                echo 'selected';
                                            } ?>>Textarea</option>
                                            <option value="select" <?php if ($field['type'] == 'select') {
                                                echo 'selected';
                                            } ?>>Select</option>
                                            <option value="radio" <?php if ($field['type'] == 'radio') {
                                                echo 'selected';
                                            } ?>>Radio</option>
                                            <option value="checkbox" <?php if ($field['type'] == 'checkbox') {
                                                echo 'selected';
                                            } ?>>Checkbox</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="label" class=" col-form-label">Label</label>
                                        <input type="text" class="form-control" name="label[]" id="label" placeholder="Enter Label"
                                            value="<?= $field['label'] ?>">
                                    </div>

                                    {{-- <div class="col-md-6">
                                        <label for="inputName" class=" col-form-label">Name</label>
                                        <input type="hidden" class="form-control" name="inputName[]" id="inputName" placeholder="Enter Name"
                                            value="<?= $field->inputName ?>">
                                    </div> --}}
                                    <div class="col-sm-6">
                                        <label for="placeholder" class=" col-form-label">Placeholder</label>
                                        <input type="text" class="form-control" name="placeholder[]" id="placeholder" placeholder="Enter Placeholder"
                                            value="<?= $field['placeholder'] ?>">
                                    </div>

                                    <!-- Add more fields as needed -->
                                </div>
                                <div id="additionalFieldsContainer{{ $key }}">
                                    @php
                                        $type = $field['type'];
                                        // echo "<pre>"; print_r($field[$type]); die();
                                    @endphp
                                    @if (!empty($field[$type]))
                                        {{-- @dd($field['select']) --}}

                                        @foreach ($field[$type] as $typeKey => $val)
                                            {{-- @dd($val); --}}
                                            @php
                                                $k = [$key];
                                                $value = $val;
                                                $name = $type;

                                            @endphp
                                            <div class="form-group row">
                                                <div class="col-sm-2">
                                                    <label for="label"
                                                        class="col-form-label">{{ !empty($type) && $type == 'select' ? 'Option' : Str::ucfirst($type) }}
                                                        Name</label>
                                                    <input type="text" value="{{ $value }}" class="form-control"
                                                        name="{{ $name }}[{{ $key }}][]" id="label" placeholder="Enter Name">
                                                </div>
                                                @if ($typeKey == '0')
                                                    <div class="col-sm-1 mt-4 float-right">
                                                        <span class="btn btn-primary" onclick="addFiledType({{ $key }},'{{ $type }}')">Add</span>
                                                    </div>
                                                @else
                                                    <div class="col-sm-1 mt-4 float-right">
                                                        <span class="btn btn-danger" onclick="removeFiledType()"><i class="fa fa-trash"></i></span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <hr>
                            @endforeach
                        @endif
                        @php
                            $addmoreCount = 0;
                            $addmoreCount = $key++;
                        @endphp
                        <input type="hidden" class="addMoreCount" value="{{ $addmoreCount }}">

                        <div id="addFiledMore{{ $addmoreCount }}">

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

        function addFiledRemove(th) {
            var removeCount = $(th).data("removecount"); // Then remove the parent container itself

            $(th).next('.form-group.row').remove();
            $('#additionalFieldsContainer' + removeCount).remove();
            $(th).remove();
        }
        $(document).ready(function() {
            // $('.addFiledRemove').click(function() {

            //     // Then remove the parent container itself
            //     $(this).next('.form-group.row').remove();
            //     $(this).remove();
            // });
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
                        $('.addFiledRemove').click(function() {
                            // Then remove the parent container itself
                            $(this).next('.form-group.row').remove();
                            $(this).remove();
                        });
                    }
                });
            });

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
        function onchangeType(selectElement, count) {

            oldCount = "0";
            var oldCount = $('.addMoreCount').val();
            var dataCount = count;
            var selectedType = $(selectElement).val();
            $.ajax({
                type: 'GET',
                url: '{{ route('company.survey.form.addfield') }}',
                data: {
                    type: selectedType,
                    addCount: dataCount,
                    addrequest: 'addrequest'
                },
                success: function(response) {
                    $('#additionalFieldsContainer' + dataCount).html(response.additionalFields);
                }
            });

        }
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
