@extends('user.layouts.master')
@section('title', 'Dashboard')
@section('main-content')
    <!-- Page Container START -->
    <!-- Content Wrapper START -->
    <div class="main-content">
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="avatar avatar-icon avatar-lg avatar-blue">
                                <i class="anticon anticon-dollar"></i>
                            </div>
                            <div class="m-l-15">
                                <h2 class="m-b-0">0</h2>
                                <p class="m-b-0 text-muted">Total Referral User</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="avatar avatar-icon avatar-lg avatar-cyan">
                                <i class="anticon anticon-line-chart"></i>
                            </div>
                            <div class="m-l-15">
                                <h2 class="m-b-0">5000</h2>
                                <p class="m-b-0 text-muted">My Total Reward </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="avatar avatar-icon avatar-lg avatar-gold">
                                <i class="anticon anticon-profile"></i>
                            </div>
                            <div class="m-l-15">
                                <h2 class="m-b-0">3</h2>
                                <p class="m-b-0 text-muted">Joined Campaigns</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="avatar avatar-icon avatar-lg avatar-purple">
                                <i class="anticon anticon-user"></i>
                            </div>
                            <div class="m-l-15">
                                <h2 class="m-b-0">5</h2>
                                <p class="m-b-0 text-muted">Completed Campaigns</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h4>Recent Activity Feed</h4>
                        <div class="m-t-25">
                            <table id="user_tables" class="table">
                                <thead>
                                    <tr>
                                        <th>Campaign</th>
                                        <th>Reward</th>
                                        <th>Description</th>
                                        <th>Type</th>
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
                                            <a class="btn btn-success  btn-sm" href="#" role="button"
                                                title="View"><i class="fa fa-eye"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Join Our Facebook Page</td>
                                        <td>$500</td>
                                        <td>Now you can browse privately, and other people who ...</td>
                                        <td>Referral</td>
                                        <td>
                                            <a class="btn btn-success  btn-sm" href="#" role="button"
                                                title="View"><i class="fa fa-eye"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Join Our Facebook Page</td>
                                        <td>$500</td>
                                        <td>Now you can browse privately, and other people who ...</td>
                                        <td>Completed</td>
                                        <td>
                                            <a class="btn btn-success  btn-sm" href="#" role="button"
                                                title="View"><i class="fa fa-eye"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5>My Referral code</h5>
                        <div class="card-body">
                            <div class="m-t-20 text-center">
                                <div class="avatar avatar-image" style="height: 100px; width: 100px;">
                                    <img src="{{ asset('assets/images/avatars/thumb-1.jpg') }}" alt="">
                                </div>
                                <h3 class="m-t-30">
                                    {{ isset(Auth::user()->referral_code) ? Auth::user()->referral_code : '' }}</h3>
                            </div>

                            <div class="text-center m-t-15">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ url(isset(Auth::user()->referral_code) ? 'user/signup/' . Auth::user()->referral_code : '') }}"
                                    target="_blank">
                                    <button class="m-r-5 btn btn-icon btn-hover btn-rounded">
                                        <i class="anticon anticon-facebook"></i>
                                    </button>
                                </a>
                                {{-- <a href="http://www.twitter.com/share?url={{ url(isset(Auth::user()->referral_code) ? 'user/signup/' . Auth::user()->referral_code : '') }}">Tweet</a> --}}
                                <a href="https://www.twitter.com/share?u={{ url(isset(Auth::user()->referral_code) ? 'user/signup/' . Auth::user()->referral_code : '') }}">
                                {{-- <a href="#" onclick="shareOnTwitter()"> --}}
                                    <button class="m-r-5 btn btn-icon btn-hover btn-rounded">
                                        <i class="anticon anticon-twitter"></i>
                                    </button>
                                </a>
                                <a href="https://www.instagram.com//sharer/sharer.php?u={{ url(isset(Auth::user()->referral_code) ? 'user/signup/' . Auth::user()->referral_code : '') }}"
                                    target="_blank">
                                    <button class="m-r-5 btn btn-icon btn-hover btn-rounded">
                                        <i class="anticon anticon-instagram"></i>
                                    </button>
                                </a>
                            </div>
                            <div class="text-center m-t-30">
                                <a href="#" onclick="showSuccessAlert()" class="btn btn-primary btn-tone">
                                    <i class="anticon anticon-copy"></i>
                                    <span class="m-l-5">Copy</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Total Revenue</h5>
                            <div>
                                <div class="btn-group">
                                    <button class="btn btn-default active">
                                        <span>Month</span>
                                    </button>
                                    <button class="btn btn-default">
                                        <span>Year</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="m-t-50" style="height: 330px">
                            <canvas class="chart" id="revenue-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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

        <script>
            function showSuccessAlert() {
                // Trigger a success sweet alert
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Referrel code link copied',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            }
        </script>

        <script>
            function shareOnTwitter(message) {
                // Construct the Twitter sharing URL
                var twitterShareURL = 'https://twitter.com/intent/tweet?' +
                    'text=' + encodeURIComponent(message) +
                    '&url=' + encodeURIComponent(window.location.href); // URL to be shared, in this case, the current page
                // Open the Twitter sharing dialog in a new tab or window
                window.open(twitterShareURL, '_blank');
            }
        </script>
    @endsection
