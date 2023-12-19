@extends('user.layouts.master')
@section('title', 'Campaign List')
@section('main-content')
<style>
    .social-icons a {
        font-size: 50px;/ margin-right: 25px;
    }
</style>
<!-- Content Wrapper START -->
<div class="main-content">
    <div class="page-header">
        <h2 class="header-title">Campaign View</h2>
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a href="#" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Home</a>
                <a class="breadcrumb-item" href="#">Pages</a>
                <span class="breadcrumb-item active">Campaign View</span>
            </nav>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    @if (isset($campagin_detail) && $campagin_detail->image == '')
                    <img src="{{ asset('assets/images/others/No_image_available.png') }}">
                    @else
                    <img class="card-img-top" src="{{asset('uploads/company/campaign/'.$campagin_detail->image)}}">
                    @endif
                    <div class="card-footer">
                        <div class="text-center m-t-15">
                            <button class="m-r-5 btn btn-icon btn-hover btn-rounded">
                                <i class="anticon anticon-facebook"></i>
                            </button>
                            <button class="m-r-5 btn btn-icon btn-hover btn-rounded">
                                <i class="anticon anticon-twitter"></i>
                            </button>
                            <button class="m-r-5 btn btn-icon btn-hover btn-rounded">
                                <i class="anticon anticon-instagram"></i>
                            </button>
                        </div>
                        <div class="text-center m-t-30">
                            {{-- @php $url = route('user.campaign.getusercampaign',$campagin_detail->id) @endphp --}}
                            <a onclick="showSuccessAlert()" href="#" data-id="{{$campagin_detail->id}}"
                                class="btn btn-primary btn-tone">
                                <span class="m-l-5">Join</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="m-b-10">{{isset($campagin_detail->title) ? $campagin_detail->title:''}}</h4>
                        <div class="d-flex align-items-center m-t-5 m-b-15">
                            <div class="m-l-1">
                                <span class="text-gray font-weight-semibold">Reward:
                                    <b>{{isset($campagin_detail->reward) ? $campagin_detail->reward:''}}</b></span>
                                <span class="m-h-5 text-gray">|</span>
                                <span class="text-gray">{{isset($campagin_detail->task_type) ?
                                    $campagin_detail->task_type:''}}</span>
                                <span class="m-h-5 text-gray">|</span>
                                <span class="text-gray">Expire on
                                    {{isset($campagin_detail->expiry_date) ? $campagin_detail->expiry_date:''}}</span>
                            </div>
                        </div>
                        <p class="m-b-20">{!! isset($campagin_detail->description) ? $campagin_detail->description:''
                            !!}
                        </p>
                        <div class="text-right">
                            {{-- <a class="btn btn-hover font-weight-semibold" href="blog-post.html">
                                <span>Read More</span>
                            </a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>Recent Conected Uers</h5>
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
                                    @foreach ($user_detail as $key => $user_detail_get)
                                    @if($user_detail_get->gettasktype->task_type)
                                    {{-- @dd($user_detail_get->gettasktype->task_type) --}}
                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-image"
                                                        style="height: 30px; min-width: 30px; max-width:30px">
                                                        @if(isset($user_detail_get->getuser->profile_image) &&
                                                        $user_detail_get->getuser->profile_image == '')
                                                        <img src="{{asset('assets/images/avatars/thumb-1.jpg')}}"
                                                            alt="">
                                                        @else
                                                        <img src="{{asset('uploads/company/user-profile/'.$user_detail_get->getuser->profile_image)}}"
                                                            alt="">
                                                        @endif
                                                    </div>
                                                    <h6 class="m-l-10 m-b-0">{{$user_detail_get->getuser->first_name}}
                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>8 May 2019</td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function showSuccessAlert(url) {
            var ID = $(this).data("id");
            alert(ID);
            $.ajax({
                url:url,
                method:"POST",
                data:{
                    "_token":"{{csrf_token()}}",
                },
                success:function(data){
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
    @endsection