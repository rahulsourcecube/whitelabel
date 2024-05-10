@extends('user.layouts.master')
@section('title', 'Ntification Setting')
@section('main-content')
    <div class="main-content">
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">Notification Setting</span>
                </nav>
            </div>
        </div>
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="row">
                            <div class="col-md-12 col-lg-12">

                                <div class="d-md-flex align-items-center">
                                    <div class="text-center text-sm-left ">
                                        <div class="avatar avatar-image" style="width: 150px; height:150px">
                                            @if (isset($userData) &&
                                                    !empty($userData->profile_image) &&
                                                    file_exists(base_path() . '/uploads/user/user-profile/' . $userData->profile_image))
                                                <img
                                                    src="{{ asset('uploads/user/user-profile/' . $userData->profile_image) }}">
                                            @else
                                                <img src="{{ asset('assets/images/profile_image.jpg') }}">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-center text-sm-left m-v-15 p-l-30">
                                        <h2 class="m-b-5">
                                            {{ isset($userData->first_name) ? $userData->first_name : '' }}
                                            {{ isset($userData->last_name) ? $userData->last_name : '' }}
                                        </h2>
                                        <div class="row">
                                            <div class="d-md-block d-none border-left col-1"></div>
                                            <div class="col-md-12">
                                                <ul class="list-unstyled m-t-10">
                                                    <li class="row">
                                                        <p class="font-weight-semibold text-dark m-b-5">
                                                            <i class="m-r-8 text-primary anticon anticon-mail"></i>
                                                        </p>
                                                        <p class="col font-weight-semibold">
                                                            {{ isset($userData->email) ? $userData->email : '-' }}</p>
                                                    </li>

                                                    <li class="row">
                                                        <p class="font-weight-semibold text-dark m-b-5">
                                                            <i class="m-r-8 text-primary anticon anticon-phone"></i>
                                                        </p>
                                                        <p class="col font-weight-semibold">
                                                            {{ isset($userData->contact_number) ? $userData->contact_number : '-' }}
                                                        </p>
                                                    </li>

                                                    <li class="row">
                                                        <p class="font-weight-semibold text-dark m-b-5">
                                                            <i class="m-r-8 text-primary anticon anticon-home"></i>
                                                        </p>
                                                        <p class="col font-weight-semibold">
                                                            {{ isset($userData->city->name) ? $userData->city->name : '-' }}
                                                            {{ isset($userData->state->name) ? $userData->state->name : '-' }}
                                                            {{ isset($userData->country->name) ? $userData->country->name : '-' }}
                                                        </p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Email Notification Setting</h4>
                </div>
                <div class="card-body">
                    <form id="changePassword" action="{{ route('user.changePasswordStore') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label class="font-weight-semibold" for="confirmPassword">New Task notification:</label>
                            </div>
                            <div class="form-group col-md-8">
                                <div class="switch m-r-10">
                                    <input type="checkbox" id="mail-1" data-toggle="switch" name="public" value="true"
                                        @if (isset($userData) && $userData->mail_new_task_notification != '1') checked="" @endif
                                        onclick="handleClickMail(this,{{ $userData->id }},'new_task')">
                                    <label for="mail-1"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label class="font-weight-semibold" for="confirmPassword">Custom notification:</label>
                            </div>
                            <div class="form-group col-md-8">
                                <div class="switch m-r-10">
                                    <input type="checkbox" id="mail-2" data-toggle="switch" name="publics" value="true"
                                        @if (isset($userData) && $userData->mail_custom_notification != '1') checked="" @endif
                                        onclick="handleClickMail(this,{{ $userData->id }},'custom')">
                                    <label for="mail-2"></label>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">SMS Notification Setting</h4>
                </div>
                <div class="card-body">
                    <form id="changePassword" action="{{ route('user.changePasswordStore') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label class="font-weight-semibold" for="confirmPassword">New Task notification:</label>
                            </div>
                            <div class="form-group col-md-8">
                                <div class="switch m-r-10">
                                    <input type="checkbox" id="sms-1" data-toggle="switch" name="public" value="true"
                                        @if (isset($userData) && $userData->sms_new_task_notification != '1') checked="" @endif
                                        onclick="handleClickSms(this,{{ $userData->id }},'new_task')">
                                    <label for="sms-1"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label class="font-weight-semibold" for="confirmPassword">Custom notification:</label>
                            </div>
                            <div class="form-group col-md-8">
                                <div class="switch m-r-10">
                                    <input type="checkbox" id="sms-2" data-toggle="switch" name="publics"
                                        value="true" @if (isset($userData) && $userData->sms_custom_notification != '1') checked="" @endif
                                        onclick="handleClickSms(this,{{ $userData->id }},'custom')">
                                    <label for="sms-2"></label>
                                </div>
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
            var table = $('#user_tables').DataTable({
                // Processing indicator
                "processing": false,
                // DataTables server-side processing mode
                "serverSide": false,
                responsive: true,
                pageLength: 10,
                // Initial no order.
                'order': [],
                language: {
                    search: "",
                    searchPlaceholder: "Search Here",
                },
            });
        });

        function handleClickMail(checkbox, user_id, type) {

            var isChecked = checkbox.checked;
            var message = isChecked ? 'make it Yes' : 'make it No';

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to ' + message + ', right?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, ' + message + '!'
            }).then((result) => {
                checkbox.checked = result.isConfirmed ? isChecked : !isChecked;

                var val = isChecked ? '0' : '1';
                $.ajax({
                    url: "{{ route('user.notification.change') }}",
                    type: 'POST',
                    data: {
                        id: user_id,
                        status: val,
                        type: type,
                        _token: "{{ csrf_token() }}"
                    }

                });
            });
        }

        function handleClickSms(checkbox, user_id, type) {

            var isChecked = checkbox.checked;
            var message = isChecked ? 'make it Yes' : 'make it No';

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to ' + message + ', right?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, ' + message + '!'
            }).then((result) => {
                checkbox.checked = result.isConfirmed ? isChecked : !isChecked;


                var val = isChecked ? '0' : '1';

                $.ajax({
                    url: "{{ route('user.notification.change') }}",
                    type: 'POST',
                    data: {
                        id: user_id,
                        status: val,
                        type: type,
                        filed: 'sms',
                        _token: "{{ csrf_token() }}"
                    }

                });
            });
        }
    </script>
@endsection
