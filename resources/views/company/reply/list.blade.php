@extends('company.layouts.master')
@section('title', 'Reply list')
@section('main-content')

    <div class="main-content">

        @include('company.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">

                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Reply List</h4>
                <div>
                    <table id="replyForm" class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Category</th>
                                <th>Reply</th>
                                <th>Status</th>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).ready(function() {
            var table = $('#replyForm').DataTable({
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
                    "url": "{{ route('company.reply.list') }}",
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

                    // Adjust column indexes based on your data structure
                    {
                        'targets': 3, // Assuming the second column in your data corresponds to this action column
                        'visible': true,
                        'orderable': false,
                        'render': function(data, type, row) {
                            var color = (row[3] == '1') ? 'btn-danger' : 'btn-success';
                            var status = (row[3] == '1') ? 'Inactive' : 'Active';
                            return ' <a class="btn  btn-sm ' + color +
                                ' " role="button"  href="javascript:void(' + row[3] +
                                ')" onclick="handleClickActive(this,\'' +
                                row[0] +
                                '\')"  title="' + status + '">' + status + '</a>';
                        },

                    },
                    {
                        'targets': 4, // Assuming the second column in your data corresponds to this action column
                        'visible': true,
                        'orderable': false,
                        'render': function(data, type, row) {


                            var viewUrl = '{{ route('company.reply.view', ':view') }}';
                            viewUrl = viewUrl.replace(':view', row[4]);
                            var deleteUrl = '{{ route('company.reply.delete', ':del') }}';
                            deleteUrl = deleteUrl.replace(':del', row[0]);

                            return '<a class="btn btn-primary btn-sm" href="' +
                                viewUrl +
                                '" role="button"  title="View"><i class="fa fa-eye"></i></a> <a class="btn btn-danger btn-sm" role="button"  href="javascript:void(0)" onclick="sweetAlertAjax(\'' +
                                deleteUrl + '\')"  title="Delete"><i class="fa fa-trash"></i></a> ';
                        },
                    }
                ],
            });
        });

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
    <script>
        function handleClickActive(e, reply_id) {

            var buttonText = $(e).text();

            var isChecked = buttonText === "Inactive";
            var message = isChecked ? 'make it Active' : 'make it Inactive';

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to ' + message + ', right?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, ' + message + '!'
            }).then((result) => {
                if (result.isConfirmed) {

                    if (buttonText === "Active") {

                        $(this).text("Inactive");
                        $(this).removeClass("btn-success").addClass(
                            "btn-danger");
                        var val = "1";
                    } else {
                        $(this).text("Active");
                        $(this).removeClass("btn-danger").addClass(
                            "btn-success");
                        var val = "0";
                    }
                    $.ajax({
                        url: "{{ route('community.reply.status.change') }}",
                        type: 'POST',
                        data: {
                            id: reply_id,
                            status: val,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            location.reload();

                        }

                    });
                }
            });
        }
    </script>


@endsection
