@extends('company.layouts.master')
@section('title', 'Role List')
@section('main-content')

    <div class="main-content">

        @include('company.includes.message')
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">Role Manage</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Roles List</h4>
                @can('role-create')
                    <a class="btn btn-primary float-right" href="{{ route('company.role.rolecreate') }}" role="button">Add
                        New</a>
                @endcan
                <div>
                    <table id="user_tables" class="table">
                        <thead>
                            <tr>
                                <th>Role Name</th>

                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $key => $role)
                                <tr>
                                    <td>{{ $role->name ?? '' }}</td>

                                    <td>
                                        <a class="btn btn-success  btn-sm"
                                            href="{{ route('company.role.roleview', $role->id) }}" role="button"
                                            title="View"><i class="fa fa-eye"></i></a>
                                        @can('role-edit')
                                            <a href="{{ route('company.role.edit', $role->id) }}"
                                                class="btn btn-primary btn-sm action-btn" title="Update Role"><i
                                                    class="fas fa-pen-fancy"></i></a>
                                        @endcan
                                        @can('role-delete')
                                            <a href="{{ route('company.role.destroy', $role->id) }}"
                                                class="btn btn-danger btn-sm action-btn show_confirm"
                                                title="Deactivate Profile"><i class="fas fa-trash-alt"></i></a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        /*This is data table for partership Request */
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
