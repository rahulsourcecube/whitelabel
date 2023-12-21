@extends('user.layouts.master')
@section('title', 'Campaign List')
@section('main-content')

<style>
.social-icons a {
    font-size: 50px; /
    margin-right: 25px; 
    
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
                    <img class="card-img-top" src="{{asset('assets/images/others/img-2.jpg')}}" alt="">
                    
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
                            <a onclick="showSuccessAlert()" href="#" class="btn btn-primary btn-tone">                               
                                <span class="m-l-5">Join</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="col-md-12 col-lg-8">
                <div class="card">
                    <div class="card-body">                  
                            <h4 class="m-b-10">Jelly-o sesame snaps halvah croissant oat cake cookie.</h4>                          
                          
                            <h4 class="m-b-10"></h4>  
                          
                        
                        <div class="d-flex align-items-center m-t-5 m-b-15">
                            <div class="m-l-1">
                                <span class="text-gray font-weight-semibold">Reward: <b>$500</b></span>  
                                <span class="m-h-5 text-gray">|</span>
                                <span class="text-gray">Custom Task</span>
                                <span class="m-h-5 text-gray">|</span>
                                <span class="text-gray">Expire on Jan 2, 2024</span>
                            </div>
                        </div>
                        <p class="m-b-20">Jelly-o sesame snaps halvah croissant oat cake cookie. Cheesecake bear claw
                            topping. Chupa chups apple pie carrot cake chocolate cake caramels
                            Jelly-o sesame snaps halvah croissant oat cake cookie. Cheesecake bear claw
                            topping. Chupa chups apple pie carrot cake chocolate cake caramels
                            Jelly-o sesame snaps halvah croissant oat cake cookie. Cheesecake bear claw
                            topping. Chupa chups apple pie carrot cake chocolate cake caramels</p>
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
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#5331</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-image" style="height: 30px; min-width: 30px; max-width:30px">
                                                        <img src="{{asset('assets/images/avatars/thumb-1.jpg')}}" alt="">
                                                    </div>
                                                    <h6 class="m-l-10 m-b-0">Erin Gonzales</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>8 May 2019</td>
                                       
                                    </tr>
                                    <tr>
                                        <td>#5375</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-image" style="height: 30px; min-width: 30px; max-width:30px">
                                                        <img src="{{asset('assets/images/avatars/thumb-2.jpg')}}" alt="">
                                                    </div>
                                                    <h6 class="m-l-10 m-b-0">Darryl Day</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>6 May 2019</td>
                                        
                                    </tr>
                                    <tr>
                                        <td>#5762</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-image" style="height: 30px; min-width: 30px; max-width:30px">
                                                        <img src="{{asset('assets/images/avatars/thumb-3.jpg')}}" alt="">
                                                    </div>
                                                    <h6 class="m-l-10 m-b-0">Marshall Nichols</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>1 May 2019</td>
                                       
                                    </tr>
                                    <tr>
                                        <td>#5865</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-image" style="height: 30px; min-width: 30px; max-width:30px">
                                                        <img src="{{asset('assets/images/avatars/thumb-4.jpg')}}" alt="">
                                                    </div>
                                                    <h6 class="m-l-10 m-b-0">Virgil Gonzales</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>28 April 2019</td>
                                        
                                       
                                    </tr>
                                  
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
        function showSuccessAlert() {
            // Trigger a success sweet alert
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Campaign joined',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        }
    </script>
    @endsection