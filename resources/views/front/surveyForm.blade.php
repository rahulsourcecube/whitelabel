@extends('front.layouts.master')
@section('title', 'Survey')
@section('main-content')

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
                                                <label for="">{{ !empty($item) ? $item : '' }}</label><br>
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
                                                <label for="">{{ !empty($item) ? $item : '' }}</label><br>
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
                                <button type="submit" class="btn btn-primary submitform">Submit</button>
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


@endsection
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
@endsection
