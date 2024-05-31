@extends('company.layouts.master')
@section('title', 'Survey Form List')
@section('main-content')
    <?php $ActivePackageData = App\Helpers\Helper::GetActivePackageData(); ?>

    <div class="main-content">

        @include('company.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">Survey Form List</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Survey Form List</h4>
                <a class="btn btn-primary float-right" href="{{ route('company.survey.form.create') }}" role="button">Add
                    New</a>
                <div>
                    <table id="surveyform" class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                {{-- <th></th> --}}
                                <th>Title</th>
                                <th>Shortcuts</th>
                                <th>Public</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade send-mail-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4">Send mail</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="anticon anticon-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <input type="hidden" class="form-control " name="add_more_count" id="add-more-count" value="0"
                            placeholder="=">
                        <form id="mailForm" method="POST" action="{{ route('company.survey.sendMail') }}"
                            data-parsley-validate="">
                            @csrf
                            <div class="row mb-3">
                                <div class="form-group col-md-12">
                                    <label for="tempHtml">Html</label>
                                    {{-- <textarea type="text" class="form-control" id="tempHtml" name="tempHtml" placeholder="Html" >{{ !empty($mailTemplate) && !empty($mailTemplate->template_html)  ? $mailTemplate->template_html : '' }}</textarea> --}}
                                    <textarea class="form-control ckeditor" id="tempHtml" cols="30" name="tempHtml" placeholder="Html">{{ !empty($mailTemplate) && !empty($mailTemplate->template_html) ? $mailTemplate->template_html : '' }}</textarea>
                                    @error('tempHtml')
                                        <label id="tempHtml-error" class="error" for="reward">The html field is required.
                                        </label>
                                    @enderror
                                </div>
                                <div class="col-md-8">
                                    <label for="title" class=" col-form-label">Email addres</label>
                                    <input type="email" class="form-control" name="mail[]" id="em" value=""
                                        placeholder="Enter mail">
                                </div>
                                <div class=" col-md-2">
                                    <label for="title" class="col-form-label"></label>

                                    <button type="button" onclick="addMailMore(this)" class="btn btn-primary"
                                        style="margin-top:35px">Add</button>
                                </div>
                            </div>
                            <div class="add-more">

                            </div>
                            <div class="row mb-2">
                                <input type="hidden" class="" name="template_id" id="template_id" value="">
                                <input type="hidden" class="" name="template_type" id="" value="">
                                <div class="col-md-12">
                                    <button type="submit" id="mailSubmit" class="btn btn-primary mr-2">Submit</button>

                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade send-sms-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4">Send SMS</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="anticon anticon-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <input type="hidden" class="form-control " name="add_sms_more_count" id="add-sms-more-count"
                            value="0" placeholder="=">
                        <form id="smsForm" method="POST" action="{{ route('company.survey.sendSms') }}"
                            data-parsley-validate="">
                            @csrf

                            <div class="row mb-3">
                                <div class=" col-md-12">
                                    <label for="tempHtml">Html</label>
                                    {{-- <textarea type="text" class="form-control" id="tempHtml" name="tempHtml" placeholder="Html" >{{ !empty($mailTemplate) && !empty($mailTemplate->template_html)  ? $mailTemplate->template_html : '' }}</textarea> --}}
                                    <textarea class="form-control " required id="smsHtml" cols="50" name="smsHtml" placeholder="Html"></textarea>

                                </div>
                                <div class="col-md-8">
                                    <label for="contact_number" class=" col-form-label">Contact Number</label>
                                    <input type="text" class="form-control" id="contact"
                                        placeholder="Contact Number" maxlength="20" name="contact_number[]"
                                        value=""
                                        oninput="this.value = this.value.replace(/[^0-9\-+]+/g, '').replace(/(\..*)\./g, '$1');"">
                                </div>
                                <div class=" col-md-2">
                                    <label for="title" class="col-form-label"></label>

                                    <button type="button" onclick="addSmsMore(this)" class="btn btn-primary"
                                        style="margin-top:35px">Add</button>
                                </div>
                            </div>
                            <div class="add-more">

                            </div>
                            <div class="row mb-2">
                                <input type="hidden" class="" name="template_id" id="template_id"
                                    value="">
                                <input type="hidden" class="" name="template_type" id=""
                                    value="">
                                <div class="col-md-12">
                                    <button type="submit" id="smsSubmit" class="btn btn-primary mr-2">Submit</button>

                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        $(document).ready(function() {

            $('#smsSubmit').click(function() {


                $('#smsForm').validate({
                    rules: {

                        'contact_number[]': {
                            required: true,
                            minlength: '10',

                        }
                    },
                    messages: {

                        'contact_number[]': {
                            required: "Please enter a contact number",
                        }
                    }
                });
            });
            $('#mailSubmit').click(function() {


                $('#mailForm').validate({
                    rules: {

                        'mail[]': {
                            required: true,
                            email: true
                        }
                    },
                    messages: {

                        'contact_number[]': {
                            required: "Please enter a Email",
                        }
                    }
                });
            });
        });
        $(document).ready(function() {

            var table = $('#surveyform').DataTable({
                // Processing indicator
                "processing": true,
                // DataTables server-side processing mode
                "serverSide": true,
                responsive: true,
                pageLength: 10,
                // Initial no order.
                'order': [
                    [0, 'desc']
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search Here",
                },
                // Load data from an Ajax source
                "ajax": {
                    "url": "{{ route('company.survey.form.list') }}",
                    "type": "GET",
                    "headers": {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    "data": function(d) {
                        // Additional data if needed
                    }
                },
                'columnDefs': [{
                        'targets': 0,
                        'visible': false,
                        'orderable': false,
                        'render': function(data, type, row) {
                            return '<input type="checkbox" name="chk_row" value="' + row[0] +
                                '" class="chk-row">';
                        },
                    },
                    {
                        'targets': 1,
                        'visible': false,
                        'orderable': false,
                        'render': function(data, type, row) {
                            return '<input type="checkbox" name="chk_row" value="' + row[0] +
                                '" class="chk-row">';
                        },
                    },
                    {
                        'targets': 3,
                        'visible': true,
                        'orderable': false,
                        'render': function(data, type, row) {
                            return '[survey[' + row[0] + ']]';
                        },
                    },

                    // Adjust column indexes based on your data structure
                    {
                        'targets': 5, // Assuming the sixth column in your data corresponds to this action column
                        'visible': true,
                        'orderable': false,
                        'render': function(data, type, row) {
                            var copy = '{{ route('front.survey.form', ':survey') }}'.replace(
                                ':survey', row[1]);
                            var view = '{{ route('company.survey.form.view', ':survey') }}'.replace(
                                ':survey', row[0]);
                            var count = row[3];
                            var editUrl = '{{ route('company.survey.form.edit', ':survey') }}'
                                .replace(':survey', row[0]);
                            var deleteUrl = '{{ route('company.survey.form.delete', ':survey') }}'
                                .replace(':survey', row[0]);
                            var facebookUrl = "https://www.facebook.com/sharer/sharer.php?u=" +
                                copy;
                            var twitterUrl = "https://www.twitter.com/share?u=" + copy;
                            var instagramUrl = "https://www.instagram.com/sharer/sharer.php?u=" +
                                copy;
                            var shortcut = '[survey[' + row[0] + ']]';

                            var actionsHtml = '<a class="btn btn-info btn-sm" href="' + view +
                                '" role="button" title="View">Total Submitted ' + count + '</a>';
                            actionsHtml += '<p id="url_copy_' + row[1] + '" style="display:none">' +
                                copy + '</p>';
                            actionsHtml +=
                                '<span class="btn btn-success btn-sm" role="button" title="Url Copy" onclick="copyToClipboard(\'#url_copy_' +
                                row[1] + '\')"><i class="anticon anticon-copy"></i> Copy</span>';
                            actionsHtml += '<a class="btn btn-success btn-sm" href="' + view +
                                '" role="button" title="View"><i class="fa fa-eye"></i></a>';
                            actionsHtml += '<a class="btn btn-primary btn-sm" href="' + editUrl +
                                '" role="button" title="Edit"><i class="fa fa-pencil"></i></a>';
                            actionsHtml += '<a class="btn btn-primary btn-sm" href="' +
                                facebookUrl +
                                '" title="Facebook"><i class="fab fa-facebook-square"></i></a>';
                            actionsHtml += '<a class="btn btn-sm btn-danger btn-sm" href="' +
                                instagramUrl +
                                '" title="Instagram"><i class="fab fa-instagram-square"></i></a>';
                            actionsHtml += '<a class="btn btn-sm btn-primary" href="' + twitterUrl +
                                '" title="Twitter"><i class="fab fa-twitter-square"></i></a>';

                            if (row[5] == 'true') {
                                actionsHtml +=
                                    '<a class="btn btn-primary btn-sm" href="javascript:void(0);" onclick="openSmsModels(\'' +
                                    shortcut + '\')" role="button" title="SMS">Send SMS</a>';
                            }

                            if (row[6] == 'true') {
                                actionsHtml +=
                                    '<a class="btn btn-primary btn-sm" href="javascript:void(0);" onclick="openMailModels(\'' +
                                    shortcut + '\')" role="button" title="Mail">Send Mail</a>';
                            }

                            actionsHtml +=
                                '<a class="btn btn-danger btn-sm" href="javascript:void(0)" onclick="sweetAlertAjax(\'' +
                                deleteUrl + '\')" title="Delete"><i class="fa fa-trash"></i></a>';

                            return actionsHtml;
                        },
                    }

                ],
            });
        });
        //SMS
        function openSmsModels(shortcut) {
            console.log(shortcut);
            $('#smsHtml').val(shortcut)

            $('.remove-div').remove()
            $('input[id="contact_number"]').val("");
            $('input[name="template_type"]').val('custom');
            $('.send-sms-modal').modal('show');
            if ('custom' === 'custom') {

                $(".all-sms-send").removeClass("d-none");

            } else {
                $(".all-sms-send").addClass("d-none");
            }

        }

        function addSmsMore(shortcut) {

            count = 1;

            oldCount = $('#add-sms-more-count').val()
            nc = parseInt(oldCount) + 1
            newCount = $('#add-sms-more-count').val(nc)


            html = `<div  class="row mb-3 remove-div">
         <div class="col-md-8">
             <label for="" class=" col-form-label">Contact Number</label>
             <input type="text" class="form-control" id="contact` + nc + `"
                                                     placeholder="Contact Number" maxlength="15"
                                                     name="contact_number[]" value="" required
                                                     onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                                     <label id="contact` + nc + `-error" class="error" for="contact` +
                nc + `" style=""></label>
         </div>
         <div class=" col-md-2">
             <label for="title" class="col-form-label"></label>

             <button type="button" onclick="removeMore(this)" class="btn btn-danger" style="margin-top:35px">
                 <li class="fa fa-trash"></li>
             </button>
         </div>
     </div>`;
            $('.add-more').append(html);


        }

        //Mail
        function openMailModels(shortcut) {
            $('.remove-div').remove()
            $('input[id="email"]').val("");
            $('input[name="template_type"]').val('custom');
            $('.send-mail-modal').modal('show');


            CKEDITOR.instances['tempHtml'].setData(shortcut)


            if ('custom' === 'custom') {

                $(".all-mail-send").removeClass("d-none");

            }

        }

        function addMailMore(e) {
            count = 1;
            oldCount = $('#add-more-count').val()
            nc = parseInt(oldCount) + 1

            newCount = $('#add-more-count').val(nc)


            html = `<div  class="row mb-3 remove-div">
                <div class="col-md-8">
                    <label for="" class=" col-form-label">Email addres</label>
                    <input type="email" class="form-control" name="mail[]" id="email` + nc + `" value=""
                        placeholder="Enter mail" required >
                </div>
                <div class=" col-md-2">
                    <label for="title" class="col-form-label"></label>

                    <button type="button" onclick="removeMore(this)" class="btn btn-danger"
                        style="margin-top:35px"><li class="fa fa-trash"></li></button>
                </div>
            </div>`;
            $('.add-more').append(html);


        }


        //sms

        function removeMore(e) {
            $(e).parent().parent().remove()
        }

        function sweetAlertAjax(deleteUrl) {
            // Use SweetAlert for confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If the user confirms, proceed with AJAX deletion
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: (response) => {

                            if (!response.success) {
                                // Handle error case
                                Swal.fire({
                                    text: response.message,
                                    icon: "error",
                                    button: "Ok",
                                }).then(() => {
                                    // Reload the page or take appropriate action
                                    location.reload();
                                });
                            } else {

                                // Handle success case
                                Swal.fire({
                                    text: response.message,
                                    icon: "success",
                                    button: "Ok",
                                }).then(() => {
                                    // Reload the page or take appropriate action
                                    location.reload();
                                });
                            }
                        },
                        error: (xhr, status, error) => {
                            // Handle AJAX request error
                            console.error(xhr.responseText);
                            Swal.fire({
                                text: 'An error occurred while processing your request.',
                                icon: "error",
                                button: "Ok",
                            });
                        }
                    });
                }
            });
        }

        function copyToClipboard(elementId) {
            var el = document.querySelector(elementId);
            var textArea = document.createElement("textarea");
            textArea.value = el.textContent;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);

            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: 'URL copied to clipboard.',
                showConfirmButton: false,
                timer: 1500
            });
        }
    </script>


@endsection
