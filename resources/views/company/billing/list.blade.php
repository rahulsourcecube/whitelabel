@extends('company.layouts.master')
@section('title', 'Billing And Payments')
@section('main-content')
@php
$ActivePackageData = App\Helpers\Helper::GetActivePackageData();
@endphp
<div class="main-content">
    <div class="card">
        <div class="card-body">
            <h4>Billing And Payments</h4>
            @can('package-list')
            <a class="btn btn-primary float-right" href="{{ route('company.package.list', 'Free') }}" role="button" style="margin-top: 18px; ">Buy Package</a>
            @endcan
            <div class="m-t-25">
                <table id="package_tbale" class="table dataTable " role="grid" aria-describedby="data-table_info">
                    <thead>
                        <tr>
                            {{-- <th>#</th>  --}}
                            <th>Package</th>
                            <th>Start Date</th>
                            <th>Expiry Date</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($bills && $bills->count() != 0)
                        @foreach ($bills as $item)
                        <tr>
                            {{-- <td>{{ $item->id ?? '-' }}</td> --}}
                            <td>{{ $item->GetPackageData->title ?? '-' }}</td>
                            <td>{{ App\Helpers\Helper::Dateformat($item->start_date) ?? '-' }}</td>
                            <td>{{ App\Helpers\Helper::Dateformat($item->end_date) ?? '-' }}</td>
                            <td>{{ App\Helpers\Helper::getcurrency()}}{{ $item->GetPackageData->price ?? '-' }}</td>
                            @if ($ActivePackageData && $ActivePackageData->id && $ActivePackageData->id != $item->id)
                            <td>
                                <a class="btn btn-danger btn-sm" href="#" role="button" title="Deactive">Deactive</a>
                            </td>
                            @else
                            <td>
                                <a class="btn btn-primary btn-sm" href="#" role="button" title="Active">Active</a>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    /*tdis is data table for partership Request */
    $(document).ready(function() {
        var table = $('#package_tbale').DataTable({
            // Processing indicator
            "processing": true,
            // DataTables server-side processing mode
            "serverSide": false
            , responsive: true
            , pageLengtd: 25,
            // Initial no order.
            'order': []
            , language: {
                search: ""
                , searchPlaceholder: "Search Here"
            , },

        });
    });

</script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    function showSuccessAlert() {
        // Trigger a success sweet alert
        Swal.fire({
            icon: 'success'
            , title: 'Success!'
            , text: 'Package is activated successful.'
            , confirmButtonColor: '#3085d6'
            , confirmButtonText: 'OK'
        });
    }

</script>>
@endsection
