@extends('user.layouts.master')
@section('title', 'Campaign List')
@section('main-content')
    <?php use Illuminate\Support\Facades\URL; ?>
    <!-- Content Wrapper START -->
    <div class="main-content">
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('user.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <a class="breadcrumb-item" href="#">Campaign</a>
                    <span class="breadcrumb-item active">Campaign View</span>
                </nav>
            </div>
        </div>
        <div class="container1">
            <div class="row">
                <div class="col-md-3">
                    <div class="card">

                        <div class="card-content">
                            @if (isset($campagin_detail) && $campagin_detail->image == '')
                                <img src="{{ asset('assets/images/others/No_image_available.png') }}"
                                    class="w-100 img-responsive">
                            @else
                                <img class="card-img-top"
                                    src="{{ asset('uploads/company/campaign/' . $campagin_detail->image) }}"
                                    class="w-100 img-responsive">
                            @endif
                        </div>
                        <div class="card-footer">
                            <input type="hidden" name="campagin_id" value="{{ $campagin_detail->id }}">
                            @if (!empty($campagin_detail->task_type) && $campagin_detail->task_type == 'Social')
                                <div class="text-center m-t-15">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
                                        target="_blank" class="m-r-5 btn btn-icon btn-hover btn-rounded">
                                        <i class="anticon anticon-facebook"></i>
                                    </a>
                                    <a href="https://www.twitter.com/share?u={{ url()->current() }}" target="_blank"
                                        class="m-r-5 btn btn-icon btn-hover btn-rounded">
                                        <i class="anticon anticon-twitter"></i>
                                    </a>
                                    <a href="https://www.instagram.com//sharer/sharer.php?u={{ url()->current() }}"
                                        target="_blank" class="m-r-5 btn btn-icon btn-hover btn-rounded">
                                        <i class="anticon anticon-instagram"></i>
                                    </a>
                                </div>
                            @endif
                            <div class="text-center m-t-30">

                                @if (!empty($user_plan) && $user_plan->status != '0')
                                    @if (isset($user_plan->status) && $user_plan->status == 1)
                                        @if ($user_plan->getCampaign->task_expired == 'Expired')
                                            <form method="post"
                                                action="{{ route('user.progress.claimReward', $user_plan->id) }}">
                                                @csrf
                                                <button class="btn btn-primary  btn-tone" role="button"><span
                                                        class="m-l-5">Claim
                                                        reward</span></button>
                                            </form>
                                        @else
                                            <a class="btn btn-primary  btn-sm" role="button"
                                                style="background-color: rgba(0, 123, 255, 0.5);">Claim reward</a>
                                        @endif
                                    @endif
                                    @if (isset($user_plan->status) && $user_plan->status == 2)
                                        <a class="btn btn-primary btn-tone"><span class="m-l-5">Claim Pending</span></a>
                                    @endif
                                @else
                                    <a onclick="showSuccessAlert()" href="#" data-id=""
                                        class="btn btn-primary btn-tone">
                                        <span class="m-l-5">Join</span>
                                    </a>
                                @endif

                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-9">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="m-b-10">{{ isset($campagin_detail->title) ? $campagin_detail->title : '' }}</h4>
                            <div class="d-flex align-items-center m-t-5 m-b-15">
                                <div class="m-l-1">
                                    <span class="text-gray font-weight-semibold">Reward:
                                        <b>{{ isset($campagin_detail->reward) ? \App\Helpers\Helper::getcurrency() . $campagin_detail->reward : '' }}</b></span>
                                    <span class="m-h-5 text-gray">|</span>
                                    <span
                                        class="text-gray">{{ isset($campagin_detail->task_type) ? $campagin_detail->task_type : '' }}</span>
                                    <span class="m-h-5 text-gray">|</span>
                                    <span class="text-gray">Expire on
                                        {{ isset($campagin_detail->expiry_date) ? $campagin_detail->expiry_date : '' }}</span>
                                </div>
                            </div>
                            <p class="m-b-20">{!! isset($campagin_detail->description) ? $campagin_detail->description : '' !!}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Recent referral Users</h5>
                                @if ($user_plan != null && $user_plan->referral_link != '')
                                    <div class="text-center mt-4 ml-3">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('campaign.referral', $user_plan->referral_link) }}"
                                            target="_blank">
                                            <button class="m-r-5 btn btn-icon btn-hover btn-rounded">
                                                <i class="anticon anticon-facebook"></i>
                                            </button>
                                        </a>
                                        {{-- <a href="http://www.twitter.com/share?url={{ route('campaign.referral', $user_plan->referral_link) }}">Tweet</a> --}}
                                        <a
                                            href="https://www.twitter.com/share?u={{ route('campaign.referral', $user_plan->referral_link) }}">
                                            {{-- <a href="#" onclick="shareOnTwitter()"> --}}
                                            <button class="m-r-5 btn btn-icon btn-hover btn-rounded">
                                                <i class="anticon anticon-twitter"></i>
                                            </button>
                                        </a>
                                        <a href="https://www.instagram.com//sharer/sharer.php?u={{ route('campaign.referral', $user_plan->referral_link) }}"
                                            target="_blank">
                                            <button class="m-r-5 btn btn-icon btn-hover btn-rounded">
                                                <i class="anticon anticon-instagram"></i>
                                            </button>
                                        </a>
                                        <p id="referral_code_copy" style="display: none;">
                                            {{ route('campaign.referral', $user_plan->referral_link) }}</p>
                                        <button onclick="copyToClipboard('#referral_code_copy')"
                                            class="btn btn-primary btn-tone">
                                            <i class="anticon anticon-copy"></i>
                                            <span class="m-l-5">Copy referral link</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="m-t-30">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="referral_user_tables">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Reward</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Recent Conected Users</h5>
                            </div>
                            <div class="m-t-30">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="user_tables">
                                        <thead>
                                            <tr>
                                                {{-- <th></th> --}}
                                                <th>ID</th>
                                                <th>User</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach ($user_detail as $user_detail_get)
                                                <tr>
                                                    <td>{{ isset($i) ? $i : '' }}
                                                    </td>
                                                    <td>{{ isset($user_detail_get->getuser->first_name) ? $user_detail_get->getuser->first_name : '' }}
                                                    </td>
                                                    <td>{{ isset($user_detail_get->getuser->created_at) ? $user_detail_get->getuser->created_at : '' }}
                                                    </td>
                                                </tr>
                                                @php
                                                    $i++;
                                                @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function showSuccessAlert() {
            var ID = "{{ base64_encode($campagin_detail->id) }}";
            var url = "{{ route('user.campaign.getusercampaign', ':id') }}"
            url = url.replace(':id', ID);
            $.ajax({
                url: url,
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Joined',
                        confirmButtonColor: '#3085D6',
                        confirmButtonText: 'OK'
                    });
                }
            });
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
    <script>
        $(document).ready(function() {
            // Call the function to fetch data and render the chart
            fetchReferralUserDetail();
        });

        function fetchReferralUserDetail() {
            var campagin_id = $('input[name="campagin_id"]').val();
            var _token = $('input[name="_token"]').val();

            $('#referral_user_tables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('GetReferralUserDetail') }}',
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "campagin_id": campagin_id,
                    },
                },
                columns: [{
                        data: 'User',
                        name: 'User'
                    },
                    {
                        data: 'Reward',
                        name: 'Reward'
                    },
                    {
                        data: 'Date',
                        name: 'Date'
                    },
                ]
            });
        };
    </script>


@endsection
