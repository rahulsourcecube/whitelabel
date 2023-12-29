@extends('user.layouts.master')
@section('title', 'Dashboard')
@section('main-content')
    <!-- Page Container START -->
    <!-- Content Wrapper START -->
    <input type="hidden" value="{{ json_encode($chartReward) }}" class="chartReward">
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
                                <h2 class="m-b-0">
                                    {{ isset($totalReferralUser) ? $totalReferralUser->count() : 0 }}
                                </h2>
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
                                <h2 class="m-b-0">
                                    {{ isset($totalReward) ? \App\Helpers\Helper::getcurrency() . $totalReward : '' }}</h2>
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
                                <h2 class="m-b-0">{{ isset($totalJoinedCampaign) ? $totalJoinedCampaign->count() : 0 }}
                                </h2>
                                <p class="m-b-0 text-muted">Joined Campaign
                                </p>
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
                                <h2 class="m-b-0">
                                    {{ isset($totalCompletedCampaign) ? $totalCompletedCampaign->count() : 0 }}</h2>
                                <p class="m-b-0 text-muted">Completed Campaign
                                </p>
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
                                    @foreach ($campaignList as $data)
                                        <tr>
                                            <td>{{ isset($data->getCampaign->title) ? $data->getCampaign->title : '' }}</td>
                                            <td>{{ isset($data->reward) ? \App\Helpers\Helper::getcurrency() . $data->reward : '' }}
                                            </td>
                                            {{-- <td>{!! isset($data->getCampaign->description) ? $data->getCampaign->description : '' !!}</td> --}}
                                            <td>
                                                @if (isset($data->getCampaign->description))
                                                    <span class="truncated-description" style="cursor: pointer;"
                                                        data-full-description="{!! strip_tags($data->getCampaign->description) !!}">
                                                        {!! \Illuminate\Support\Str::limit(strip_tags($data->getCampaign->description), 10) !!}
                                                    </span>
                                                @else
                                                    -
                                                @endif

                                                <div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog"
                                                    aria-labelledby="descriptionModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="descriptionModalLabel">Full
                                                                    Description</h5>
                                                                <button type="button" class="close" data- dismiss="modal"
                                                                    aria-label="Close">
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p id="fullDescription"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ isset($data->getCampaign->task_type) ? $data->getCampaign->task_type : '' }}
                                            </td>
                                            <td>
                                                <a class="btn btn-success  btn-sm"
                                                    href="{{ route('user.campaign.view', base64_encode($data->campaign_id)) }}"
                                                    role="button" title="View"><i class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach

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
                                    @if (isset(Auth::user()->profile_image) &&
                                            !empty(Auth::user()->profile_image) &&
                                            file_exists('uploads/user/user-profile/' . Auth::user()->profile_image))
                                        <img src="{{ asset('uploads/user/user-profile/' . Auth::user()->profile_image) }}">
                                    @else
                                        <img src="{{ asset('assets/images/profile_image.jpg') }}">
                                    @endif
                                </div>
                                <div class="row justify-content-center">
                                    <h3 class="m-t-30">
                                        {{ isset(Auth::user()->referral_code) ? Auth::user()->referral_code : '' }}
                                    </h3>

                                    <div class="text-center mt-4 ml-3">
                                        <p id="referral_code_copy" style="display: none;">
                                            {{ url(isset(Auth::user()->referral_code) ? 'user/signup/' . Auth::user()->referral_code : 'user/signup') }}
                                        </p>
                                        <button onclick="copyToClipboard('#referral_code_copy')"
                                            class="btn btn-primary btn-tone">
                                            <i class="anticon anticon-copy"></i>
                                            <span class="m-l-5">Copy</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center m-t-15">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ url(isset(Auth::user()->referral_code) ? 'user/signup/' . Auth::user()->referral_code : '') }}"
                                    target="_blank">
                                    <button class="m-r-5 btn btn-icon btn-hover btn-rounded">
                                        <i class="anticon anticon-facebook"></i>
                                    </button>
                                </a>
                                {{-- <a href="http://www.twitter.com/share?url={{ url(isset(Auth::user()->referral_code) ? 'user/signup/' . Auth::user()->referral_code : '') }}">Tweet</a> --}}
                                <a
                                    href="https://www.twitter.com/share?u={{ url(isset(Auth::user()->referral_code) ? 'user/signup/' . Auth::user()->referral_code : '') }}">
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
                            {{-- <div> --}}
                            {{-- <div class="btn-group"> --}}
                            {{-- <button class="btn btn-default active">
                                        <span>Month</span>
                                    </button>
                                    <button class="btn btn-default">
                                        <span>Year</span>
                                    </button> --}}
                            {{-- </div> --}}
                            {{-- </div> --}}
                        </div>
                        <div class="">
                            <canvas class="chart" id="myChart"></canvas>
                        </div>
                        {{-- <div class="m-t-50">
                            <canvas class="chart" id="revenue-chart"></canvas>
                        </div> --}}
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

        <script>
            function copyToClipboard(elementId) {
                var el = document.querySelector(elementId);
                var textArea = document.createElement("textarea");
                textArea.value = el.textContent;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);

                showSuccessAlert();
            }

            function showSuccessAlert() {
                Swal.fire({
                    icon: 'success',
                    title: 'Copied!',
                    text: 'URL copied to clipboard.',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        </script>

    @endsection
    @section('js')
        <script>
            $(document).ready(function() {
                var chartReward = $(".chartReward").val();
                chartReward = JSON.parse(chartReward);
                console.log(chartReward);

                var start = new Date();
                var start_date = new Date(start);
                start_date.setDate(start.getDate() - 9);

                var currentDate = new Date();
                var xArray = [];
                var yArray = [];

                while (start_date <= currentDate) {
                    xArray.push((new Date(start_date)).getDate() + "th");
                    var check_date = (new Date(start_date)).getFullYear() + '-' + (parseInt((new Date(start_date))
                        .getMonth()) + parseInt(1)) + '-' + (new Date(start_date)).getDate();
                    let obj = chartReward.find(x => x.day == check_date);
                    if (obj && obj != '') {
                        yArray.push(obj.total_day_reward)
                    } else {
                        yArray.push(0)
                    }
                    start_date.setDate(start_date.getDate() + 1);
                }
                console.log("xArray",xArray);
                console.log("yArray",yArray);
                var ctx = document.getElementById('myChart').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: xArray,
                        datasets: [{
                            label: {
                                display: false,
                            },
                            borderColor: '#3f87f5',
                            data: yArray,
                        }]
                    },
                    options: {
                        layout: {
                            padding: 10,
                        },
                        legend: {
                            position: 'bottom',
                        },
                        title: {
                            display: true,
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    stepSize: 1
                                }
                            }],
                            xAxes: [{
                                scaleLabel: {
                                    display: true,
                                }
                            }]
                        }
                    }
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                $('.truncated-description').click(function() {
                    var fullDescription = $(this).data('full-description');
                    $('#fullDescription').text(fullDescription);
                    $('#descriptionModal').modal('show');
                });
            });
        </script>

    @endsection
