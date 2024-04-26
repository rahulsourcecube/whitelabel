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
                    <form id="surveyForm" method="POST"
                        action="{{ route('company.survey.form.update', $surveyFiled->id) }}" data-parsley-validate="">
                        @csrf
                        <div class="row">
                            <div class=" form-group col-md-6">

                                <label for="survey_title" class="col-form-label">Title</label>
                                <input type="text" class="form-control" name="survey_title" id="survey_title"
                                    value="{{ !empty($surveyFiled) && !empty($surveyFiled->title) ? $surveyFiled->title : '' }}"
                                    placeholder="Enter Title">

                            </div>
                            <div class=" form-group col-md-6">

                                <label for="slug" class=" col-form-label">Slug</label>
                                <input type="text" class="form-control" name="slug" id="slug"
                                    value="{{ !empty($surveyFiled) && !empty($surveyFiled->slug) ? $surveyFiled->slug : '' }}"
                                    placeholder="Enter Slug">

                            </div>
                        </div>

                        @if (!empty($surveyFiled) && !empty($surveyFiled->fields))
                            @php
                                $fieldData = json_decode($surveyFiled->fields, true);
                                $count = 0;
                            @endphp

                            @foreach ($fieldData as $key => $field)
                                @if ($key == '0')
                                @else
                                    <span class="btn btn-danger float-right addFiledRemove  btn-sm"
                                        onclick="addFiledRemove(this)" data-removeCount="{{ $key }}"><i
                                            class="fa fa-trash"></i></span>
                                @endif
                                <div class="form-group row ">
                                    <div class="col-md-6">
                                        <label for="type" class="col-form-label">Type</label>
                                        <select id="type" name="type[]"
                                            onchange="onchangeType(this,{{ $key }})"
                                            data-count="{{ $key }}" class="form-control templateType" required>
                                            <option value="">Select Type</option>
                                            <option value="text" <?php if (!empty($field['type']) && $field['type'] == 'text') {
                                                echo 'selected';
                                            } ?>>Text</option>
                                            <option value="number" <?php if (!empty($field['type']) && $field['type'] == 'number') {
                                                echo 'selected';
                                            } ?>>Number</option>
                                            <option value="textarea" <?php if (!empty($field['type']) && $field['type'] == 'textarea') {
                                                echo 'selected';
                                            } ?>>Textarea</option>
                                            <option value="select" <?php if (!empty($field['type']) && $field['type'] == 'select') {
                                                echo 'selected';
                                            } ?>>Select</option>
                                            <option value="radio" <?php if (!empty($field['type']) && $field['type'] == 'radio') {
                                                echo 'selected';
                                            } ?>>Radio</option>
                                            <option value="checkbox" <?php if (!empty($field['type']) && $field['type'] == 'checkbox') {
                                                echo 'selected';
                                            } ?>>Checkbox</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="label" class=" col-form-label">Label</label>
                                        <input type="text" class="form-control" name="label[]" id="label"
                                            placeholder="Enter Label" value="<?= $field['label'] ?>" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="placeholder" class=" col-form-label">Placeholder</label>
                                        <input type="text" class="form-control" name="placeholder[]" id="placeholder"
                                            placeholder="Enter Placeholder" value="<?= $field['placeholder'] ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="required" class="col-form-label">Required</label>
                                        <select id="required" name="required[]" class="form-control ">
                                            <option value="yes" <?php if (!empty($field['required']) && $field['required'] == 'yes') {
                                                echo 'selected';
                                            } ?>>Yes</option>
                                            <option value="no" <?php if (!empty($field['required']) && $field['required'] == 'no') {
                                                echo 'selected';
                                            } ?>>NO</option>
                                        </select>
                                    </div>

                                    <!-- Add more fields as needed -->
                                </div>
                                <div id="additionalFieldsContainer{{ $key }}">
                                    @php
                                        $type = $field['type'];
                                    @endphp
                                    @if (!empty($field[$type]))
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
                                                        Value</label>
                                                    <input type="text" value="{{ $value }}" class="form-control"
                                                        name="{{ $name }}[{{ $key }}][]" id="label"
                                                        placeholder="Enter Value" required>
                                                </div>
                                                @if ($typeKey == '0')
                                                    <div class="col-sm-1 mt-4 float-right">
                                                        <span class="btn btn-primary btn-sm"
                                                            onclick="addFiledType({{ $key }},'{{ $type }}')">Add</span>
                                                    </div>
                                                @else
                                                    <div class="col-sm-1 mt-4 float-right">
                                                        <span class="btn btn-danger btn-sm" onclick="removeFiledType()"><i
                                                                class="fa fa-trash"></i></span>
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
                        <input type="hidden" class="id" id="id"
                            value="{{ !empty($surveyFiled) && !empty($surveyFiled->id) ? $surveyFiled->id : '' }}">

                        <div id="addFiledMore{{ $addmoreCount }}">

                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <span class="btn btn-info addFiledMore">+ Add More Field</span>
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
    <script src="{{ asset('assets/js/parsley.min.js?v=' . time()) }}"></script>
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
        $(document).ready(function() {
            $('#survey_title').on('input', function() {
                var title = $(this).val().toLowerCase();
                var slug = title.trim().replace(/\s+/g, '-').replace(/[^a-z-]/g, '').replace(/-{2,}/g,
                    '-').replace(/^-+|-+$/g, '');
                $('#slug').val(slug);
            });
            $('#slug').on('change', function() {
                var title = $(this).val().toLowerCase();
                var slug = title.trim().replace(/\s+/g, '-').replace(/[^a-z-]/g, '').replace(/-{2,}/g,
                    '-').replace(/^-+|-+$/g, '');
                $('#slug').val(slug);
            });


            $('#submit').click(function() {

                $('#surveyForm').validate({
                    rules: {
                        survey_title: 'required',
                        'type[]': {
                            required: true
                        },
                        'label[]': {
                            required: true
                        },
                        'inputName[]': {
                            required: true
                        },
                        slug: {
                            required: true,
                            remote: {
                                url: "{{ route('company.survey.checkSlug') }}",
                                type: "post",
                                data: {
                                    'id': $("#id").val()
                                },
                                headers: {
                                    "X-CSRF-TOKEN": " {{ csrf_token() }}"
                                },
                            }
                        }
                    },
                    messages: {
                        survey_title: 'Please enter a survey title',
                        'type[]': 'Please select at least one type',
                        'label[]': 'Please enter a label',
                        'inputName[]': 'Please enter a name',
                        slug: {
                            required: "Please enter a slug",
                            remote: "Slug already exists"
                        }
                    },
                });
            });
        });

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
@endsection
