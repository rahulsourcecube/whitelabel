@extends('admin.layouts.master')

@section('main-content')
    <div class="main-content">
        <div class="page-header">
            <h4 class="header-title">Package View</h4>

        </div>
        <div class="row">
            <div class="col-lg-11 mx-auto">
                <div class="row">
                    <div class="col-md-4">

                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <img class="card-img-top" src="{{ asset('assets/images/others/img-2.jpg') }}" alt="">
                            <div class="card-body">
                                <h4 class="m-t-10">Package Title</h4>
                                <p class="m-b-20">Package Descrtiption
                                    Jelly-o sesame snaps halvah croissant oat cake cookie. Cheesecake bear claw topping.
                                    Chupa...</p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="m-b-0 text-dark font-weight-semibold font-size-15">$999</p>
                                    <span>Month</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
