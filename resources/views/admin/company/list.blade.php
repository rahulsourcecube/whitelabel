@extends('admin.layouts.master')
@section('title', 'Company List')
@section('main-content')

    <div class="main-content">
        <div class="card">
            <div class="card-body">
                <h4>Company List</h4>

                <div class="m-t-25 table-responsive">
                    <table id="package_tbale" class="table  dataTable  w-100" role="grid" aria-describedby="data-table_info">
                        <thead>
                            <tr>
                                <th><input type="checkbox" name="chk_all" value="1" class="chk-all"></th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Company Name </th>
                                <th>Sub Domain</th>
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
    <!-- Modal -->
    <div class="modal fade bd-example-modal-xl">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4">Add Package</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="anticon anticon-close"></i>
                    </button>
                </div>
                <div class="modal-body d-flex">

                </div>
            </div>
        </div>
    </div>
    <script>
        /*This is data table for partership Request */
        $(document).ready(function() {
            var table = $('#package_tbale').DataTable({
                "processing": true,
                "serverSide": true,
                responsive: true,
                pageLength: 10,
                'order': [
                    [0, 'desc']
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search Here",
                },
              // Load data from an Ajax source
                "ajax": {
                    "url": "{{ route('admin.company.dtlist') }}",
                    "type": "POST",
                    "headers": {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    "data": function(d) {
                        // d.search_name = $('#search_name').val();
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
                }, {
                    'targets': 7,
                    'visible': true,
                    'orderable': false,
                    'render': function(data, type, row) {
                        var viewUrl = '{{ route('admin.company.view', ':id') }}';
                        var editUrl = '{{ route('admin.company.edit', ':package') }}';
                        editUrl = editUrl.replace(':package', row[0]);
                        viewUrl = viewUrl.replace(':id', row[0]);
                        return '<a class="btn btn-success btn-sm " href="' + viewUrl +
                            '" role="button"><i class="fa fa-eye"></i></a> <a class="btn btn-primary btn-sm" href="' +
                            editUrl +
                            '" role="button"  title="Edit"><i class="fa fa-pencil"></i></a> <button type="button" class="btn btn-primary btn-sm" onclick=loadDataAndShowModal(' +
                            row[0] +
                            ');><i class="anticon anticon-shopping-cart"></i></button>';
                    },
                }],
            });
        });
    </script>
    <script>
        function loadDataAndShowModal(id) {
            var viewUrl = '{{ route('admin.company.AddPackages', ':id') }}';
            viewUrl = viewUrl.replace(':id', id);
            $.ajax({
                url: viewUrl, // Example URL
                method: "post",
                dataType: "json",
                "headers": {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                success: function(data) {
                    // Display data in the modal
                    $(".modal-body").html("<p>" + data.html + "</p>");
                    // Show the modal
                    $(".bd-example-modal-xl").modal('show');
                },
                error: function() {
                    alert("Error loading data");
                }
            });
        }
    </script>
@endsection
