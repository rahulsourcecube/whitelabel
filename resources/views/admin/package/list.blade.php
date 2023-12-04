@extends('admin.layouts.master')
@section('title', 'Package List')
@section('main-content')
    <div class="main-content">

        <div class="card">
            <div class="card-body">
                <h4>Package List</h4>

                <a class="btn btn-primary float-right" href="{{ route('admin.package.create') }}" role="button">Add New</a>
                <div class="m-t-25">
                    <table id="package_tbales" class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                {{-- <th>Description </th> --}}
                                <th>Type</th>
                                <th>Duration</th>
                                <th>Price</th>
                                <th>No Of Campaign</th>
                                <th>Image</th>
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

    <script>
        /*This is data table for partership Request */
        // $(document).ready(function() {
        //     $('#package_tbale').DataTable({
        //         "paging": true, // Enable pagination
        //         "searching": false // Enable search bar
        //     });
        // });
        $(document).ready(function() {
            var table = $('#package_tbales').DataTable({
                // Processing indicator
                "processing": true,
                // DataTables server-side processing mode
                "serverSide": true,
                responsive: true,
                pageLength: 25,
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
                    "url": "{{ route('admin.package.dtlist') }}",
                    "type": "POST",
                    "headers": {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    "data": function(d) {
                        // d.search_name = $('#search_name').val();
                    }
                },
                'columnDefs': [{
                //     'targets': 0,
                //     'visible': true,
                //     'orderable': false,
                //     'render': function(data, type, row) {
                //         return '<input type="checkbox" name="chk-row" value="' + row[1] +
                //             '" class="chk-row">';
                //     },
                // }, {
                    'targets': 8,
                    'visible': true,
                    'orderable': false,
                    'render': function(data, type, row) {
                        var viewUrl = '{{ route('admin.company.view', ':id') }}';
                        viewUrl = viewUrl.replace(':id', row[0]);
                        return '<a class="btn btn-success " href="' + viewUrl +
                            '" role="button">View</a>';
                    },
            }],
            });
        });
    </script>
@endsection
