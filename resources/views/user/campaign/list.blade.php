@extends('user.layouts.master')
@section('title', 'Campaign List')
@section('main-content')

<div class="main-content">

    @include('user.includes.message')
    <div class="page-header">
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a href="{{ route('user.dashboard') }}" class="breadcrumb-item"><i
                        class="anticon anticon-home m-r-5"></i>Dashboard</a>
                <span class="breadcrumb-item active">Campaign </span>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4>Campaign List</h4>           
            <div class="m-t-25">
                <table id="user_tables" class="table">
                    <thead>
                        <tr>
                            <th>Campaign</th>
                            <th>Reward</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Join Our Facebook Page</td>
                            <td>$500</td>
                            <td>Now you can browse privately, and other people who ...</td>
                            <td>Referral</td>                           
                            <td>
                                <button class="btn btn-primary  btn-sm"  onclick="showSuccessAlert()"
                                    role="button" title="View">Join</button>                               
                            </td>                           
                            <td>
                                <a class="btn btn-success  btn-sm" href="{{route('user.campaign.view')}}"
                                    role="button" title="View"><i class="fa fa-eye"></i></a>                               
                            </td>
                        </tr>
                        <tr>
                            <td>Join Our Facebook Page</td>
                            <td>$500</td>
                            <td>Now you can browse privately, and other people who ...</td>
                            <td>Referral</td>                           
                            <td>
                                <button class="btn btn-primary  btn-sm"  onclick="showSuccessAlert()"
                                    role="button" title="View">Join</button>                               
                            </td>                           
                            <td>
                                <a class="btn btn-success  btn-sm" href="{{route('user.campaign.view')}}"
                                    role="button" title="View"><i class="fa fa-eye"></i></a>                               
                            </td>
                        </tr>
                        <tr>
                            <td>Join Our Facebook Page</td>
                            <td>$500</td>
                            <td>Now you can browse privately, and other people who ...</td>
                            <td>Completed</td>                           
                            <td>
                                <button class="btn btn-primary  btn-sm"  onclick="showSuccessAlert()"
                                    role="button" title="View">Join</button>                               
                            </td>                           
                            <td>
                                <a class="btn btn-success  btn-sm" href="{{route('user.campaign.view')}}"
                                    role="button" title="View"><i class="fa fa-eye"></i></a>                               
                            </td>
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    function showSuccessAlert() {
        // Trigger a success sweet alert
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Joined',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    }
</script>

@endsection