@extends('company.layouts.master')
@section('title', 'Add Category')
@section('main-content')
    <div class="main-content">
        @include('company.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <a href="{{ route('company.employee.list') }}" class="breadcrumb-item">Category </a>
                    <span class="breadcrumb-item active">{{ !empty($channels) ? 'Edit' : 'Add' }}</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>{{ !empty($channels) ? 'Edit' : 'Add' }} Category </h4>

                <div class="m-t-50" style="">
                    <form id="channel" method="POST" action="{{ route('company.channel.store') }}">
                        @csrf
                        <div class="row">
                            <div class=" form-group col-md-6">
                                <label for="title" class="col-sm-3 col-form-label">Title</label>

                                <input type="text" class="form-control" name="title" id="title"
                                    value="{{ !empty($channels) ? $channels->title : '' }}" placeholder="Enter Title">
                            </div>
                            <input type="hidden" class="form-control" name="id" id="id"
                                value="{{ !empty($channels) ? base64_encode($channels->id) : '' }}">

                        </div>

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
        $(document).ready(function() {

            $('#submit').click(function() {

                $('#channel').validate({
                    rules: {

                        title: {
                            required: true
                        }
                    },
                    messages: {

                        title: {
                            required: "Please enter a Title",
                        }
                    }
                });
            });
        });
    </script>
@endsection
