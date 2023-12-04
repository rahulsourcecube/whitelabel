@extends('admin.layouts.master')
@section('title', 'Company List')
@section('main-content')
    <div class="main-content">
        <div class="card">
            <div class="card-body">
                <h4>Company List</h4>
                <div class="m-t-25">
                    <table id="package_tbale" class="table dataTable " role="grid" aria-describedby="data-table_info">
                        <thead>
                            <tr>
                                <th><input type="checkbox" name="chk_all" value="1" class="chk-all"></th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Company Name </th>
                                <th>Domain</th>
                                <th>Package</th>
                                <th>Logo</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- <tr>
                                <td>Sundar Pichai</td>
                                <td>Package@xyz.com</td>
                                <td>8974561230</td>
                                <td>Google </td>
                                <td>Google.xyz.com </td>
                                <td>Gold</td>
                                <td>logo</td>
                                <td>Company</td>
                                <td>Active</td>
                                <td>
                                    <a class="btn btn-success " href="#" role="button">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Sundar Pichai</td>
                                <td>Package@xyz.com</td>
                                <td>8974561230</td>
                                <td>Google </td>
                                <td>Google.xyz.com </td>
                                <td>Gold</td>
                                <td>logo</td>
                                <td>Company</td>
                                <td>Active</td>
                                <td>
                                    <a class="btn btn-success " href="#" role="button">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Sundar Pichai</td>
                                <td>Package@xyz.com</td>
                                <td>8974561230</td>
                                <td>Google </td>
                                <td>Google.xyz.com </td>
                                <td>Gold</td>
                                <td>logo</td>
                                <td>Company</td>
                                <td>Active</td>
                                <td>
                                    <a class="btn btn-success " href="#" role="button">View</a>
                                </td>
                            </tr> --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        /*This is data table for partership Request */
        $(document).ready(function() {
            var table = $('#package_tbale').DataTable({
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
                    'targets': 10,
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