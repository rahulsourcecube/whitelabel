@if ($errors->any())
    <div class="alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li style="    color: red;
            ">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (\Session::has('success'))


    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="uil uil-check me-2"></i>
        {!! \Session::get('success') !!}
    </div>
    {{ \Session::forget('success') }}

@endif

@if (\Session::has('error'))
    <div class="alert alert-
     alert-dismissible fade show" role="alert">
        <i class="uil uil-times me-2"></i>
        {!! \Session::get('error') !!}
    </div>
    {{ \Session::forget('error') }}

@endif
@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
<script>
    $(document).ready(function() {
 // Show the alert
         $("alert").fadeIn();

         // Hide the alert after 3 seconds
         setTimeout(function() {
         $("#alert").fadeOut();
         }, 2000);
 });
</script>