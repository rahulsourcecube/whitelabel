@if (\Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show alert-message" role="alert">
        <i class="uil uil-check me-2"></i>
        {!! \Session::get('success') !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    {{ \Session::forget('success') }}
@endif
@if (\Session::has('error'))
    <div class="alert alert-danger alert-dismissible fade show alert-message" role="alert">
        <i class="uil uil-times me-2"></i>
        {!! \Session::get('error') !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    {{ \Session::forget('error') }}
@endif
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
@if (\Session::has('error_hold') && !empty(\Session::get('error_hold')))
    <div class="alert alert-danger alert-dismissible fade show alert-message-mail" role="alert">
        <i class="uil uil-times me-2"></i>
        <strong>Mail not sent this email</strong> : {!! \Session::get('error_hold') !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    {{ \Session::forget('error_hold') }}
@endif
@if (\Session::has('error_sms_hold') && !empty(\Session::get('error_sms_hold')))
    <div class="alert alert-danger alert-dismissible fade show alert-message-mail" role="alert">
        <i class="uil uil-times me-2"></i>
        {!! \Session::get('error_sms_hold') !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    {{ \Session::forget('error_sms_hold') }}
@endif
