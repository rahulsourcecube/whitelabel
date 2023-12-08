@extends('company.layouts.master')
@section('title', 'Billing And Payments')
@section('main-content')

    <div class="main-content">
        <div class="card">
            <div class="card-body">
                <h4>Billing And Payments</h4>
                <div class="m-t-25">
                    <table id="package_tbale" class="table dataTable " role="grid" aria-describedby="data-table_info">
                        <thead>
                            <tr>
                                <th>Package</th>
                                <th>Start Date</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Premium Plan</td>
                                <td>16-05-2023</td>
                                <td>15-12-2023</td>
                                <td>
                                    <a class="btn btn-danger btn-sm" href="#" role="button" title="Edit">Deactive</a>
                                </td>

                                <td>
                                    <button class="btn btn-success " onclick="showSuccessAlert()">Buy Package</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Standard Plan</td>
                                <td>15-01-2023</td>
                                <td>16-05-2023</td>
                                <td>
                                    <a class="btn btn-danger btn-sm" href="#" role="button" title="Edit">Deactive</a>
                                </td>

                                <td>
                                    <button class="btn btn-success " onclick="showSuccessAlert()">Buy Package</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Basic Plan</td>
                                <td>01-01-2023</td>
                                <td>15-01-2023</td>
                                <td>
                                    <a class="btn btn-danger btn-sm" href="#" role="button" title="Edit">Deactive</a>
                                </td>
                                <td>
                                    <button class="btn btn-success " onclick="showSuccessAlert()">Buy Package</button>
                                </td>
                            </tr>
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
                "serverSide": false,
                responsive: true,
                pageLengtd: 25,
                // Initial no order.
                'order': [
                    // [0, 'desc']
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search Here",
                },

            });
        });
    </script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    function showSuccessAlert() {
        // Trigger a success sweet alert
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Package is activated successful.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    }
</script>>
@endsection
