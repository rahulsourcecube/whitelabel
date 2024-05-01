<!DOCTYPE html>
<html lang="en">
@php
    $siteSetting = App\Helpers\Helper::getSiteSetting();
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Survey </title>
    <!-- Favicon -->
    <link rel="shortcut icon"
        href=" @if (
            !empty($siteSetting) &&
                !empty($siteSetting->favicon) &&
                base_path(public_path('uploads/setting/' . $siteSetting->favicon))) {{ asset('uploads/setting/' . $siteSetting->favicon) }} @else{{ asset('assets/images/logo/logo.png') }} @endif">
    <!-- page css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/dataTables.bootstrap.min.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Core css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/admin/common.css') }}">

</head>

<body>
    <div class="app">
        <div class="layout">
            <!-- Header START -->
            @include('front.includes.header')
            <!-- Header END -->

            <!-- Page Container START -->
            <div class=" p-h-0 p-v-20 bg full-height"
                style="background-image: url('{{ asset('assets/images/others/login-3.png') }}">
                <div class="page-containers">

                    <div class="row align-items-center ">
                        <div class="col-md-2 "></div>
                        <div class="col-md-8 col-lg-8 m-w-auto">
                            <div class="card shadow-lg">
                                <div class="card-body  m-w-auto">
                                    <div class="d-flex align-items-center justify-content-between m-b-30">
                                        <img style="width: 130px ; hight:50px"
                                            src="@if (!empty($siteSetting) && !empty($siteSetting->logo) && file_exists('uploads/setting/' . $siteSetting->logo)) {{ url('uploads/setting/' . $siteSetting->logo) }} @else {{ asset('assets/images/logo/logo.png') }} @endif "
                                            alt="Logo">
                                        <h2 class="m-b-0 m-l-10">
                                            {{ !empty($surveyFiled->title) && !empty($surveyFiled) ? $surveyFiled->title : '' }}
                                        </h2>
                                    </div>
                                    <form id="survey" method="POST" action="{{ route('front.survey.store') }}">
                                        @csrf
                                        <div class="form-row">
                                            @if (!empty($fields))

                                                @foreach ($fields as $key => $field)
                                                    <?php  if($field['type'] == 'text'){ ?>

                                                    <div class="form-group col-md-12">
                                                        <label
                                                            for="{{ !empty($field) && !empty($field['idname']) ? $field['idname'] : '' }}">{{ !empty($field) && !empty($field['label']) ? $field['label'] : '' }}</label>
                                                        <input type="text" class="form-control"
                                                            id="{{ !empty($field) && !empty($field['inputName']) ? $field['inputName'] : '' }}"
                                                            {{ !empty($field) && !empty($field['required']) && $field['required'] == 'yes' ? 'required' : '' }}
                                                            name="{{ !empty($field) && !empty($field['inputName']) ? $field['inputName'] : '' }}"
                                                            placeholder="{{ !empty($field) && !empty($field['placeholder']) ? $field['placeholder'] : '' }}"
                                                            id="{{ !empty($field) && !empty($field['idname']) ? $field['idname'] : '' }}"
                                                            maxlength="150" value="">
                                                    </div>

                                                    <?php }elseif($field['type']=='number'){ ?>
                                                    <div class="form-group col-md-12">
                                                        <label
                                                            for="{{ !empty($field) && !empty($field['idname']) ? $field['idname'] : '' }}">{{ !empty($field) && !empty($field['label']) ? $field['label'] : '' }}
                                                            <span class="error"></span></label>
                                                        <input type="text" class="form-control"
                                                            id="{{ !empty($field) && !empty($field['idname']) ? $field['idname'] : '' }}"
                                                            {{ !empty($field) && !empty($field['required']) && $field['required'] == 'yes' ? 'required' : '' }}
                                                            name="{{ !empty($field) && !empty($field['inputName']) ? $field['inputName'] : '' }}"
                                                            placeholder="{{ !empty($field) && !empty($field['placeholder']) ? $field['placeholder'] : '' }}"
                                                            maxlength="150" value=""
                                                            onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                                    </div>
                                                    <?php }elseif($field['type']=='textarea'){ ?>
                                                    <div class="form-group col-md-12">
                                                        <label
                                                            for="{{ !empty($field) && !empty($field['idname']) ? $field['idname'] : '' }}">{{ !empty($field) && !empty($field['label']) ? $field['label'] : '' }}<span
                                                                class="error"></span></label>
                                                        <textarea {{ !empty($field) && !empty($field['required']) && $field['required'] == 'yes' ? 'required' : '' }}
                                                            name="{{ !empty($field) && !empty($field['inputName']) ? $field['inputName'] : '' }}"
                                                            placeholder="{{ !empty($field) && !empty($field['placeholder']) ? $field['placeholder'] : '' }}"
                                                            id="{{ !empty($field) && !empty($field['idname']) ? $field['idname'] : '' }}" class="form-control" cols="30"
                                                            rows="10" maxlength="250"></textarea>
                                                    </div>
                                                    <?php }elseif($field['type']=='select'){ ?>
                                                    <div class="form-group col-md-12">
                                                        <label
                                                            for="{{ !empty($field) && !empty($field['idname']) ? $field['idname'] : '' }}">{{ !empty($field) && !empty($field['label']) ? $field['label'] : '' }}
                                                            <span class="error"></span></label>
                                                        <select
                                                            {{ !empty($field) && !empty($field['required']) && $field['required'] == 'yes' ? 'required' : '' }}
                                                            name="{{ !empty($field) && !empty($field['inputName']) ? $field['inputName'] : '' }}"
                                                            data-placeholder="{{ !empty($field) && !empty($field['placeholder']) ? $field['placeholder'] : '' }}"
                                                            id="{{ !empty($field) && !empty($field['idname']) ? $field['idname'] : '' }}"
                                                            class="form-control">
                                                            <option value="">Select
                                                                {{ !empty($field) && !empty($field['label']) ? $field['label'] : '' }}
                                                            </option>
                                                            @if (!empty($field['select']))
                                                                @foreach ($field['select'] as $item)
                                                                    <option value="{{ !empty($item) ? $item : '' }}">
                                                                        {{ !empty($item) ? $item : '' }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <?php }elseif($field['type']=='radio'){ ?>
                                                    <div class="form-group col-md-12">
                                                        <label>{{ !empty($field) && !empty($field['label']) ? $field['label'] : '' }}<span
                                                                class="error"></span></label><br>
                                                        @if (!empty($field['radio']))
                                                            @foreach ($field['radio'] as $item)
                                                                <input type="radio" id=""
                                                                    {{ !empty($field) && !empty($field['required']) && $field['required'] == 'yes' ? 'required' : '' }}
                                                                    name="{{ !empty($field) && !empty($field['inputName']) ? $field['inputName'] : '' }}"
                                                                    value="{{ !empty($item) ? $item : '' }}">
                                                                <label
                                                                    for="">{{ !empty($item) ? $item : '' }}</label><br>
                                                            @endforeach
                                                            <label
                                                                id="{{ !empty($field) && !empty($field['inputName']) ? $field['inputName'] : '' }}-error"
                                                                class="error"
                                                                for="{{ !empty($field) && !empty($field['inputName']) ? $field['inputName'] : '' }}"></label>
                                                        @endif
                                                    </div>
                                                    <?php }elseif($field['type']=='checkbox'){ ?>
                                                    <div class="form-group col-md-12">
                                                        <label>{{ !empty($field) && !empty($field['label']) ? $field['label'] : '' }}<span
                                                                class="error"></span></label><br>
                                                        @if (!empty($field['checkbox']))
                                                            @foreach ($field['checkbox'] as $item)
                                                                <input type="checkbox" id=""
                                                                    {{ !empty($field) && !empty($field['required']) && $field['required'] == 'yes' ? 'required' : '' }}
                                                                    name="{{ !empty($field) && !empty($field['inputName']) ? $field['inputName'] : '' }}[]"
                                                                    value="{{ !empty($item) ? $item : '' }}">
                                                                <label
                                                                    for="">{{ !empty($item) ? $item : '' }}</label><br>
                                                            @endforeach
                                                        @endif
                                                        <label
                                                            id="{{ !empty($field) && !empty($field['inputName']) ? $field['inputName'] : '' }}-error"
                                                            class="error"
                                                            for="{{ !empty($field) && !empty($field['inputName']) ? $field['inputName'] : '' }}"></label>
                                                    </div>

                                                    <?php } ?>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <div class="d-flex align-items-center justify-content-between p-t-15">
                                                <button type="submit"
                                                    class="btn btn-primary submitform">Submit</button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="form_id"
                                            value="{{ !empty($surveyFiled->id) && !empty($surveyFiled) ? $surveyFiled->id : '' }}">
                                    </form>
                                    {{-- <label for="checkbox"><span>Already have an account? <a
                                                href="{{route('company.signin')}}">Login</a></span></label> --}}
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <!-- Page Container END -->
        </div>
    </div>
    <!--  Footer Scripts -->
    @include('front.includes.footer_scripts')
</body>

</html>
@section('js')
    <script>
        $('#survey').validate({

            submitHandler: function(form) {
                // Show the spinner
                $('.submitform').html(
                    'Sign Up <div id="button-spinner" style="margin-left: 10px; width: 15px; height: 15px; display: none" class="spinner-border"></div>'
                ).attr('disabled', true);
                $('#button-spinner').show();
                form.submit();
            }
        });
        $(document).ready(function() {
            // Show the alert
            $("alert").fadeIn();

            // Hide the alert after 3 seconds
            setTimeout(function() {
                $("#alert").fadeOut();
            }, 2000);
        });
    </script>
