@extends('user.layouts.master')
@section('title', 'Survey Form List')
@section('main-content')

    <div class="main-content">

        @include('user.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('user.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">Survey Form List</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Survey List</h4>

                <div>
                    <table id="surveyform" class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Title</th>
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
                    "url": "{{ route('user.survey.list') }}",
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


                    // Adjust column indexes based on your data structure
                    {
                        'targets': 3, // Assuming the second column in your data corresponds to this action column
                        'visible': true,
                        'orderable': false,
                        'render': function(data, type, row) {
                            var view = '{{ route('front.survey.form', ':survey') }}';
                            view = view.replace(':survey', row[1]);

                            return '<a class="btn btn-success btn-sm" href="' +
                                view +
                                '" role="button"  title="View"><i class="fa fa-eye"></i></a>'
                        },
                    }
                ],
            });
        });
    </script>


@endsection
