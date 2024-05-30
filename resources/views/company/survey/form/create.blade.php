@extends('company.layouts.master')
@section('title', 'Add Survey Form')
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
        <form id="questionForm" method="POST" action="{{ route('company.survey.form.store') }}" data-parsley-validate="">
            <div class="card">
                <div class="card-body">
                    <h4>Add Survey </h4>
                    <div class="m-t-50" style="">
                        @csrf
                        <div class="row">
                            <div class=" form-group col-md-4">
                                <label for="survey_title" class="col-sm-3 col-form-label">Title</label>
                                <input type="text" class="form-control" name="survey_title" id="survey_title"
                                    placeholder="Enter Title">
                            </div>
                            <div class=" col-md-4">
                                <label for="slug" class="col-sm-3 col-form-label">Slug</label>

                                <input type="text" class="form-control" name="slug" id="slug"
                                    placeholder="Enter Slug">

                            </div>
                            <div class=" form-group col-md-4">
                                <label for="public" class="col-sm-3 col-form-label">Public</label>
                                <div class="switch m-r-10">
                                    <input type="checkbox" id="public-1" data-toggle="switch" name="public"
                                        value="true">
                                    <label for="public-1"></label>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class=" form-group col-md-12">
                                <label for="description" class="col-sm-3 col-form-label">Description</label>
                                <textarea class="form-control ckeditor" name="description" id="description" placeholder="Enter description" required></textarea>
                            </div>

                        </div>


                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4>Add Question </h4>
                    <div>
                        <input type="hidden" class="addMoreCount" value="0">
                        <div class="form-group row ">
                            <div class="col-md-12">
                                <label for="question" class=" col-form-label">Question </label>
                                <input type="text" class="form-control" name="question[]" id="question"
                                    placeholder="Enter Question">
                            </div>
                            <div class="col-md-3">
                                <label for="type" class="col-form-label">Type</label>
                                <select id="type" name="type[]" class="form-control templateType type"
                                    onchange="onchangeType(this)" data-count="0">
                                    <option value="">Select Type</option>
                                    <option value="text">Text</option>
                                    <option value="number">Number</option>
                                    {{-- <option value="textarea">Textarea</option> --}}
                                    <option value="select">Select</option>
                                    <option value="radio">Radio</option>
                                    <option value="checkbox">Checkbox</option>
                                </select>
                            </div>


                            {{-- <div class="col-sm-6">
                                <label for="placeholder" class=" col-form-label">Placeholder</label>
                                <input type="text" class="form-control" name="placeholder[]" id="placeholder"
                                    placeholder="Enter Placeholder">
                            </div> --}}
                            <div class="col-md-3">
                                <label for="required" class="col-form-label">Required</label>
                                <select id="required" name="required[]" class="form-control ">
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>
                        <div id="additionalFieldsContainer0">

                        </div>

                        <div id="addFiledMore0">

                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <span class="btn btn-info addFiledMore">+ Add More Field</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-2">
                    <button type="submit" id="submit" class="btn btn-primary w-100">Submit</button>
                </div>
            </div>

        </form>

    </div>

@endsection

@section('js')
    <script src="{{ asset('assets/js/parsley.min.js?v=' . time()) }}"></script>
    <script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script>
        if (!CKEDITOR.instances['ckeditor']) {
            CKEDITOR.replace("ckeditor");
        }
    </script>
    <script>
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
                        $('.addFiledRemove').click(function() {
                            var removeCount = $(this).data(
                                "removecount"
                            ); // Then remove the parent container itself
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

                $('#questionForm').validate({
                    rules: {
                        survey_title: 'required',
                        'type[]': {
                            required: true
                        },
                        'question[]': {
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

                                headers: {
                                    "X-CSRF-TOKEN": " {{ csrf_token() }}"
                                },
                            }
                        }
                    },
                    messages: {
                        survey_title: 'Please enter a survey title',
                        'type[]': 'Please select at least one type',
                        'question[]': 'Please enter a label',
                        'inputName[]': 'Please enter a name',
                        slug: {
                            required: "Please enter a slug",
                            remote: "Slug already exists"
                        }
                    },
                });
            });
        });
    </script>
@endsection
