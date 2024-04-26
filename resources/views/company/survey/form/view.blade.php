@extends('company.layouts.master')
@section('title', 'Survey Details')
@section('main-content')
    <div class="main-content">
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">
                        <i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <a href="" class="breadcrumb-item">Survey</a>
                    <span class="breadcrumb-item active">Details</span>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    {{-- <div class="col-md-6"> --}}
                    <div class="card-body">
                        <h4>{{ !empty($surveyFiled) && !empty($surveyFiled->title) ? $surveyFiled->title : '' }}</h4>
                        <div class="m-t-50" style="">
                            <form id="Surveyform" method="POST" action="" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    @if (!empty($fields))
                                        @foreach ($fields as $key => $field)
                                            <?php  if($field['type'] == 'text'){ ?>

                                            <div class="form-group col-md-12">
                                                <label
                                                    for="{{ !empty($field) && !empty($field['idname']) ? $field['idname'] : '' }}">{{ !empty($field) && !empty($field['label']) ? $field['label'] : '' }}</label>
                                                <input type="text" class="form-control" id="lname"
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
                                                    name="{{ !empty($field) && !empty($field['inputName']) ? $field['inputName'] : '' }}"
                                                    placeholder="{{ !empty($field) && !empty($field['placeholder']) ? $field['placeholder'] : '' }}"
                                                    maxlength="150" value="">
                                            </div>
                                            <?php }elseif($field['type']=='textarea'){ ?>
                                            <div class="form-group col-md-12">
                                                <label
                                                    for="{{ !empty($field) && !empty($field['idname']) ? $field['idname'] : '' }}">{{ !empty($field) && !empty($field['label']) ? $field['label'] : '' }}<span
                                                        class="error"></span></label>
                                                <textarea name="{{ !empty($field) && !empty($field['inputName']) ? $field['inputName'] : '' }}"
                                                    placeholder="{{ !empty($field) && !empty($field['placeholder']) ? $field['placeholder'] : '' }}"
                                                    id="{{ !empty($field) && !empty($field['idname']) ? $field['idname'] : '' }}" class="form-control" cols="30"
                                                    rows="10"></textarea>
                                            </div>
                                            <?php }elseif($field['type']=='select'){ ?>
                                            <div class="form-group col-md-12">
                                                <label
                                                    for="{{ !empty($field) && !empty($field['idname']) ? $field['idname'] : '' }}">{{ !empty($field) && !empty($field['label']) ? $field['label'] : '' }}
                                                    <span class="error"></span></label>
                                                <select
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
                                                            name="{{ !empty($field) && !empty($field['inputName']) ? $field['inputName'] : '' }}"
                                                            value="{{ !empty($item) ? $item : '' }}">
                                                        <label for="">{{ !empty($item) ? $item : '' }}</label><br>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <?php }elseif($field['type']=='checkbox'){ ?>
                                            <div class="form-group col-md-12">
                                                <label>{{ !empty($field) && !empty($field['label']) ? $field['label'] : '' }}<span
                                                        class="error"></span></label><br>
                                                @if (!empty($field['checkbox']))
                                                    @foreach ($field['checkbox'] as $item)
                                                        <input type="checkbox" id=""
                                                            name="{{ !empty($field) && !empty($field['inputName']) ? $field['inputName'] : '' }}"
                                                            value="{{ !empty($item) ? $item : '' }}">
                                                        <label for="">{{ !empty($item) ? $item : '' }}</label><br>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <?php } ?>
                                        @endforeach
                                    @endif
                                </div>

                            </form>
                        </div>
                    </div>
                    {{-- </div> --}}
                </div>
            </div>
            <div class="col-sm-6 ">
                <div class=" row">
                    @if (!empty($surveyDatas))
                        @foreach ($surveyDatas as $key => $surveyData)
                            @php
                                $userData = [];
                                if (!empty($surveyData)) {
                                    $userData = json_decode($surveyData->data, true);
                                }
                            @endphp
                            <div class="col-sm-12 ">
                                <div class="card">
                                    <div class="card-body">
                                        <h4>Survey Details</h4>
                                        <div class="m-t-10" style="">
                                            <div class="">
                                                <table class="table-responsive">
                                                    @foreach ($userData as $keys => $data)
                                                        <tr>
                                                            <th>{{ key($data) }}</th>
                                                            <td class="mr-4">: {{ nl2br($data[key($data)]) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="col-sm-12 float-right ">

                            {{-- {{ $surveyDatas->links() }} --}}
                            {{ $surveyDatas->appends(Request::all())->links() }}

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
