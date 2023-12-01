@extends('admin.layouts.master')
@section('title', 'Package List')
@section('main-content')
    <div class="main-content">

        <div class="card">
            <div class="card-body">
                <h4>Package List</h4>

                <a class="btn btn-primary float-right" href="{{ route('admin.package.create') }}" role="button">Add New</a>
                <div class="m-t-25">
                    <table id="package_tbale" class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description </th>
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
                            <tr>
                                <td>Package 1</td>
                                <td>Package 1 Package 1 Package 1 </td>
                                <td>Free Trial</td>
                                <td>7 Month</td>
                                <td>$50</td>
                                <td>15</td>
                                <td>image</td>
                                <td>Active</td>
                                <td>
                                    <a class="btn btn-success " href="{{ route('admin.package.view') }}"
                                        role="button">View</a>
                                    <a class="btn btn-primary" href="#" role="button">Edit</a>
                                    <a class="btn btn-danger " href="#" role="button">Delete</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Package 2</td>
                                <td>Package 1 Package 1 Package 1 </td>
                                <td>Monthly</td>
                                <td>7 Month</td>
                                <td>$50</td>
                                <td>15</td>
                                <td>image</td>
                                <td>Active</td>
                                <td>
                                    <a class="btn btn-success " href="{{ route('admin.package.view') }}"
                                        role="button">View</a>
                                    <a class="btn btn-primary" href="#" role="button">Edit</a>
                                    <a class="btn btn-danger " href="#" role="button">Delete</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Package 2</td>
                                <td>Package 1 Package 1 Package 1 </td>
                                <td>Yearly</td>
                                <td>7 Month</td>
                                <td>$50</td>
                                <td>15</td>
                                <td>image</td>
                                <td>Active</td>
                                <td>
                                    <a class="btn btn-success " href="{{ route('admin.package.view') }}"
                                        role="button">View</a>
                                    <a class="btn btn-primary" href="#" role="button">Edit</a>
                                    <a class="btn btn-danger " href="#" role="button">Delete</a>
                                </td>
                            </tr>

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
