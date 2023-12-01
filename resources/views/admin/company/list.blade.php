@extends('admin.layouts.master')
@section('title', 'Company List')

@section('main-content')
    <div class="main-content">

        <div class="card">
            <div class="card-body">
                <h4>Company List</h4>


                <div class="m-t-25">
                    <table id="package_tbale" class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th> Number</th>
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
                            {{-- <tr>
                        <td>Package 2</td>
                        <td>Package 1  Package 1  Package 1 </td>
                        <td>Monthly</td>
                        <td>7 Month</td>
                        <td>$50</td>

                        <td>image</td>
                        <td>Active</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Package 2</td>
                        <td>Package 1  Package 1  Package 1 </td>
                        <td>Yearly</td>
                        <td>7 Month</td>
                        <td>$50</td>
                        <td>15</td>
                        <td>image</td>
                        <td>Active</td>
                        <td></td>
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
            $('#package_tbale').DataTable({
                "paging": true, // Enable pagination
                "searching": false // Enable search bar
            });
        });
    </script>
@endsection
