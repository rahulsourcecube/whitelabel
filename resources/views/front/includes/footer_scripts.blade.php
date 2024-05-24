<!-- Core Vendors JS -->
<script src="{{ asset('assets/js/vendors.min.js?v=' . time()) }}"></script>
<script src="{{ asset('assets/vendors/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendors/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/vendors/chartjs/Chart.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/dashboard-default.js?v=' . time()) }}"></script>
<script src="{{ asset('assets/vendors/chartist/chartist.min.js?v=' . time()) }}"></script>
<!-- Core JS -->
<script src="{{ asset('assets/js/app.min.js') }}"></script>
{{-- JS CDNs --}}
<script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
<script src="{{ asset('assets/vendors/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Show the alert
        $(".alert-message").fadeIn();
        // Hide the alert after 2 seconds
        setTimeout(function() {
            $(".alert-message").fadeOut();
        }, 2000);
    });

    $.validator.addMethod('fileExtension', function(value, element) {
        var fileInput = $(element);
        var file = fileInput[0].files[0];
        if (file != undefined) {
            var allowedExtensions = 'jpeg,png,jpg,gif'.split(',');
            var fileExtension = file.name.split('.').pop().toLowerCase().toString();
            if ($.inArray(fileExtension, allowedExtensions) == -1) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }, 'Image type should be .png, .jpg, .jpeg or .gif');

    $.validator.addMethod('fileSize', function(value, element) {
        var fileInput = $(element);
        var file = fileInput[0].files[0];
        if (file != undefined) {
            var maxSizeKB = parseInt(2048, 10) || 0;
            return file.size <= maxSizeKB * 1024;
        } else {
            return true;
        }
    }, 'Image size is not valid');

    function previewImage() {
        var input = document.getElementById('file');
        var preview = document.getElementById('imagePreview');
        var deleteButton = document.getElementById('deleteImageButton');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                deleteButton.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            preview.style.display = 'none';
            deleteButton.style.display = 'none';
        }
    }

    function deleteImage() {
        var input = document.getElementById('file');
        var preview = document.getElementById('imagePreview');
        var deleteButton = document.getElementById('deleteImageButton');
        input.value = '';
        preview.src = '#';
        preview.style.display = 'none';
        deleteButton.style.display = 'none';
    }
</script>
<script>
    $(document).ready(function($) {



        $('#country').on('change', function() {
            var country_id = $(this).val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ route('front.campaign.getStates') }}",
                type: 'POST',
                data: {
                    country_id: country_id,
                    _token: CSRF_TOKEN
                },
                success: function(response) {
                    $("#city").empty().append("<option value=''>Select City</option>");
                    $("#state").empty().append(response);
                }
            });
        });


        $('#state').on('change', function() {
            var state_id = $(this).val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ route('front.campaign.getCity') }}",
                type: 'POST',
                data: {
                    state_id: state_id,
                    _token: CSRF_TOKEN // Include CSRF token in the request data
                },
                success: function(response) {
                    $("#city").empty().append(response);
                }
            });
        });

        // $('#search_dtt').on('click', function() {

        //     var country_id = $('#country').val(); // Corrected selector syntax
        //     var state_id = $('#state').val(); // Corrected selector syntax
        //     var city_id = $('#city').val(); // Corrected selector syntax


        //     var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        //     $.ajax({
        //         type: 'post',
        //         url: "{{ route('front.campaign.search') }}",
        //         data: {
        //             'country_id': country_id,
        //             'state_id': state_id,
        //             'city_id': city_id,
        //             _token: CSRF_TOKEN
        //         },
        //         success: function(data) {
        //             console.log("success");
        //             // $('tbody').html(data);
        //         }
        //     });
        // });

    });
</script>
@yield('js')
