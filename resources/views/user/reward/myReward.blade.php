@extends('user.layouts.master')
@section('title', 'my Reward List')
@section('main-content')

<div class="main-content">

    @include('user.includes.message')
    <div class="page-header">
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a href="{{ route('user.dashboard') }}" class="breadcrumb-item"><i
                        class="anticon anticon-home m-r-5"></i>Dashboard</a>
                <span class="breadcrumb-item active">My Reward </span>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4>my Reward</h4>           
            <div class="m-t-25">
                <table id="user_tables" class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Reward</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Status</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Join Our Facebook Page</td>
                            <td>$500</td>
                            <td>Now you can browse privately, and other people who ...</td>
                            <td>Referral</td>                           
                            <td><a href="#"  class="btn btn-success  btn-sm"
                                role="button" title="View">Completed</a> </td>                           
                        </tr>
                        <tr>
                            <td>Join Our Facebook Page</td>
                            <td>$500</td>
                            <td>Now you can browse privately, and other people who ...</td>
                            <td>Referral</td>                           
                            <td><a  href="#" class="btn btn-success  btn-sm"
                                role="button" title="View">Completed</a> </td>                           
                        </tr>
                        <tr>
                            <td>Join Our Facebook Page</td>
                            <td>$500</td>
                            <td>Now you can browse privately, and other people who ...</td>
                            <td>Social</td>                           
                            <td><a href="#" class="btn btn-success  btn-sm"
                                role="button" title="View">Completed</a> </td>                           
                           
                        </tr>
                      
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        var table = $('#user_tables').DataTable({
            // Processing indicator
            "processing": false,
            // DataTables server-side processing mode
            "serverSide": false,
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
    });
    });
</script>
@endsection