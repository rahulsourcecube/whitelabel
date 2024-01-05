@extends('company.layouts.master')
@section('title', 'Buy Package')
@section('main-content')
    @php
        $ActivePackageData = App\Helpers\Helper::GetActivePackageData();
        $FreePackagePurchased = App\Helpers\Helper::FreePackagePurchased();

    @endphp
    <div class="main-content">
        <div class="page-header">
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item"><i
                            class="anticon anticon-home m-r-5"></i>Dashboard</a>
                    <span class="breadcrumb-item active">Package </span>
                </nav>
            </div>
        </div>
        <div class="row align-items-center" id="monthly-view">

            <div class="container">
                <div class="text-center m-t-30 m-b-40">
                    <h2>Purchase Package</h2>

                    <div class="btn-group">
                        <a href="{{ route('company.package.list', 'FREE') }}"type="button" id="monthly-btn"
                            class="btn btn-default {{ $type == App\Models\PackageModel::TYPE['FREE'] ? 'active' : '' }}">
                            {{-- <a href="{{ route('company.package.list', 'FREE') }}"> --}}
                            <span>Free Trial</span>
                            {{-- </a> --}}
                        </a>
                        <a href="{{ route('company.package.list', 'MONTHLY') }}" type="button" id="annual-btn"
                            class="btn btn-default {{ $type == App\Models\PackageModel::TYPE['MONTHLY'] ? 'active' : '' }}">
                            {{-- <a href="{{ route('company.package.list', 'MONTHLY') }}"> --}}
                            <span>Monthly</span>
                        </a>
                        </a>
                        <a href="{{ route('company.package.list', 'YEARLY') }}" type="button" id="annual-btn"
                            class="btn btn-default {{ $type == App\Models\PackageModel::TYPE['YEARLY'] ? 'active' : '' }}">
                            {{-- <a href="{{ route('company.package.list', 'YEARLY') }}"> --}}
                            <span>Yearly</span>
                            {{-- </a> --}}
                        </a>
                    </div>
                </div>
                <div class="row align-items-center" id="monthly-view">
                    @if (isset($packages) && count($packages) > 0)
                        @foreach ($packages as $list)
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between p-b-20 border-bottom">
                                            <div class="media align-items-center">
                                                <div class="avatar avatar-blue avatar-icon"
                                                    style="height: 55px; width: 55px;">
                                                    <i class="anticon anticon-dollar font-size-25"
                                                        style="line-height: 55px"></i>
                                                </div>
                                                <div class="m-l-15">
                                                    @if ($ActivePackageData && $ActivePackageData->id && $ActivePackageData->id == $list->id)
                                                        <span class="badge badge-primary"
                                                            style="margin-left: 180px">Active</span>
                                                    @endif
                                                    <h2 class="font-weight-bold font-size-30 m-b-0">
                                                        @if ($list->type != '1')
                                                            {{ App\Helpers\Helper::getcurrency() }}
                                                        @endif{{ $list->plan_price }}
                                                    </h2>
                                                    <h4 class="m-b-0">{{ $list->title }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <ul class="list-unstyled m-v-30">
                                            <li class="m-b-20">
                                                <div class="d-flex justify-content-between"> <span
                                                        class="text-dark font-weight-semibold">{{ $list->duration }}
                                                        @if ($list->type == '1')
                                                            Days
                                                        @elseif ($list->type == '2')
                                                            Month
                                                        @elseif ($list->type == '3')
                                                            Year
                                                        @endif
                                                        Plan
                                                    </span>
                                                    <div class="text-success font-size-16"> <i
                                                            class="anticon anticon-check"></i> </div>
                                                </div>
                                            </li>
                                            <li class="m-b-20">
                                                <div class="d-flex justify-content-between"> <span
                                                        class="text-dark font-weight-semibold">Total campaign
                                                        {{ $list->no_of_campaign }}</span>
                                                    <div class="text-success font-size-16"> <i
                                                            class="anticon anticon-check"></i> </div>
                                                </div>
                                            </li>
                                            <li class="m-b-20">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-dark font-weight-semibold">Total Employee
                                                        {{ $list->no_of_employee }}</span>
                                                    <div class="text-success font-size-16"> <i
                                                            class="anticon anticon-check"></i> </div>
                                                </div>
                                            </li>
                                            <li class="m-b-20">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-dark font-weight-semibold">Total User
                                                        {{ $list->no_of_user }}</span>
                                                    <div class="text-success font-size-16"> <i
                                                            class="anticon anticon-check"></i> </div>
                                                </div>
                                            </li>
                                        </ul>
                                        {!! $list->description !!}
                                        @can('package-create')
                                            @if ($list->type == '1')
                                                <form action="{{ route('company.package.buy') }}" method="POST"
                                                    id="package-payment-form">
                                                    @csrf
                                                    <input type="hidden" name="package_id" value="{{ $list->id }}">
                                                    <div class="text-center">
                                                        <button type="submit" class="btn btn-success {{ $list->user_bought }}"
                                                            {{ $list->type == '1' && !empty($FreePackagePurchased) && $FreePackagePurchased->id != null ? 'disabled' : '' }}>{{ $list->user_bought == true ? 'Purchased' : 'Buy                                                                                                                                                                                                                                                                                                                                                                                                  Package' }}</button>
                                                    </div>
                                                </form>
                                            @else
                                                <div class="text-center">
                                                    <button class="btn btn-success {{ $list->user_bought }}"
                                                        {{ $list->type == '1' && !empty($FreePackagePurchased) && $FreePackagePurchased->id != null ? 'disabled' : '' }}
                                                        onclick="openPaymentModal('{{ $list->id }}')">{{ $list->user_bought == true
                                                            ? 'Purchased'
                                                            : 'Buy
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        Package' }}</button>
                                                </div>
                                            @endif
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <h4>No packages found</h4>
                    @endif
                </div>
            </div>
        </div>
        <div class="modal fade bd-example-modal-lg" id="payment-modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title h4">Pay</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <i class="anticon anticon-close"></i>
                        </button>
                    </div>
                    <form action="{{ route('company.package.buy') }}" method="POST" id="package-payment-form">
                        @csrf
                        <input type="hidden" name="package_id" value="">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="round-form-group">
                                        <label class="paymenttab-label content-para">Card Number</label>
                                        <input type="text" class="form-control round-input remove-arrow card-number"
                                            placeholder="1234 5678 0123 4567" name="card_number" maxlength="19"
                                            minlength="19" size="20"
                                            onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                            onkeyup="formatCardNumber(event);">
                                        <label class="error" id="card_number-error"></label>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="round-form-group">
                                        <label class="paymenttab-label content-para">Exp
                                            Month</label>
                                        <input type="text"
                                            class="form-control round-input remove-arrow card-expiry-month"
                                            placeholder="MM" name="card_expiry_month"
                                            onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                            maxlength="2">
                                        <label class="error" id="card_expiry_month-error"></label>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="round-form-group">
                                        <label class="paymenttab-label content-para">Exp
                                            Year</label>
                                        <input type="text"
                                            class="form-control round-input remove-arrow card-expiry-year"
                                            placeholder="YYYY" name="card_expiry_year"
                                            onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                            maxlength="4">
                                        <label class="error" id="card_expiry_year-error"></label>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="round-form-group">
                                        <label class="paymenttab-label content-para">CVV
                                            Code</label>
                                        <input type="text" class="form-control round-input remove-arrow card-cvv"
                                            placeholder="123" name="cvv_code" size="3" maxlength="3"
                                            onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                        <label class="error" id="cvv_code-error"></label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="round-form-group">
                                        <label class="paymenttab-label content-para">Name on
                                            Card</label>
                                        <input type="text" class="form-control round-input remove-arrow"
                                            placeholder="Name On Card" name="name_on_card"
                                            onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)">
                                        <label class="error" id="name_on_card-error"></label>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="round-form-group">
                                        <label class="paymenttab-label content-para">Zip
                                            Code</label>
                                        <input type="text" class="form-control round-input remove-arrow"
                                            placeholder="123456" name="zipcode"
                                            onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                            maxlength="6">
                                        <label class="error" id="zipcode-error"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default m-r-10" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Pay</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- <div class="container">
        <div class='row'>
            <div class='col-md-4'></div>
            <div class='col-md-4'>
                <form accept-charset="UTF-8" action="{{ route('company.package.stripe.post') }}"
                    class="require-validation" data-cc-on-file="false"
                    data-stripe-publishable-key="SET_YOUR_PUBLISHED_KEY_HERE" id="payment-form" method="post">
                    @csrf
                    <div class='form-row'>
                        <div class='col-xs-12 form-group required'>
                            <label class='control-label'>Card Holder Name</label> <input class='form-control'
                                size='4' type='text' placeholder="Enter Card Holder Name">
                        </div>
                    </div>
                    <div class='form-row'>
                        <div class='col-xs-12 form-group card required'>
                            <label class='control-label'>Card Number</label> <input autocomplete='off'
                                class='form-control card-number' size='20' type='text'
                                placeholder="Enter Card number">
                        </div>
                    </div>
                    <div class='form-row'>
                        <div class='col-xs-4 form-group cvc required'>
                            <label class='control-label'>CVC</label> <input autocomplete='off'
                                class='form-control card-cvc' placeholder='CVV' size='4' type='text'>
                        </div>
                        <div class='col-xs-4 form-group expiration required'>
                            <label class='control-label'>Expiration</label> <input class='form-control card-expiry-month'
                                placeholder='MM' size='2' type='text'>
                        </div>
                        <div class='col-xs-4 form-group expiration required'>
                            <label class='control-label'>YEAR</label> <input class='form-control card-expiry-year'
                                placeholder='YYYY' size='4' type='text'>
                        </div>
                    </div>
                    <!-- <div class='form-row'>
                            <div class='col-md-12'>
                              <div class='form-control total btn btn-info'>
                                Total: <span class='amount'>$300</span>
                              </div>
                            </div>
                          </div> -->
                    <div class='form-row'>
                        <div class='col-md-12 form-group'>
                            <button class='form-control btn btn-primary submit-button' type='submit'
                                style="margin-top: 10px;">Confirm</button>
                        </div>
                    </div>
                    <div class='form-row'>
                        <div class='col-md-12 error form-group hide'>
                            <div class='alert-danger alert'>Please correct the errors and try
                                again.</div>
                        </div>
                    </div>
                </form>
                @if (Session::has('success-message'))
                    <div class="alert alert-success col-md-12">{{ Session::get('success-message') }}</div>
                    @endif @if (Session::has('fail-message'))
                        <div class="alert alert-danger col-md-12">{{ Session::get('fail-message') }}</div>
                    @endif
            </div>
            <div class='col-md-4'></div>
        </div>
    </div> --}}

    @endsection
    @section('js')
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            Stripe.setPublishableKey(
                "{{ env('STRIPE_KEY') }}"
            );
        </script>
        <script>
            $(function() {
                $('form.require-validation').bind('submit', function(e) {
                    var $form = $(e.target).closest('form'),
                        inputSelector = ['input[type=email]', 'input[type=password]',
                            'input[type=text]', 'input[type=file]',
                            'textarea'
                        ].join(', '),
                        $inputs = $form.find('.required').find(inputSelector),
                        $errorMessage = $form.find('div.error'),
                        valid = true;

                    $errorMessage.addClass('hide');
                    $('.has-error').removeClass('has-error');
                    $inputs.each(function(i, el) {
                        var $input = $(el);
                        if ($input.val() === '') {
                            $input.parent().addClass('has-error');
                            $errorMessage.removeClass('hide');
                            e.preventDefault(); // cancel on first error
                        }
                    });
                });
            });

            // $(function() {
            //     Stripe.setPublishableKey(
            //         "{{ env('STRIPE_KEY') }}"
            //         );

            //     var $form = $("#payment-form");

            //     $form.on('submit', function(e) {
            //         if (!$form.data('cc-on-file')) {
            //             e.preventDefault();
            //             Stripe.setPublishableKey($form.data(
            //                 "{{ env('STRIPE_KEY') }}"
            //             ));
            //             Stripe.createToken({
            //                 number: $('.card-number').val(),
            //                 cvc: $('.card-cvc').val(),
            //                 exp_month: $('.card-expiry-month').val(),
            //                 exp_year: $('.card-expiry-year').val()
            //             }, stripeResponseHandler);
            //         }
            //     });

            //     function stripeResponseHandler(status, response) {
            //         if (response.error) {
            //             $('.error')
            //                 .removeClass('hide')
            //                 .find('.alert')
            //                 .text(response.error.message);
            //         } else {
            //             // token contains id, last4, and card type
            //             var token = response['id'];
            //             // insert the token into the form so it gets submitted to the server
            //             $form.find('input[type=text]').empty();
            //             $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            //             console.log(token);
            //             $form.get(0).submit();
            //         }
            //     }
            // })
        </script>

        <script>
            function openPaymentModal(packageId) {
                $('input[name="package_id"]').val(packageId);
                $('#payment-modal').modal('show');
            }

            function showSuccessAlert() {
                // Trigger a success sweet alert
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Package is activated successfully.',
                    confirmButtonColor: '#3085D6',
                    confirmButtonText: 'OK'
                }).then(function() {
                    location.reload();
                });
            }

            function formatString(e) {
                var inputChar = String.fromCharCode(event.keyCode);
                var code = event.keyCode;
                var allowedKeys = [8];
                if (allowedKeys.indexOf(code) !== -1) {
                    return;
                }
                event.target.value = event.target.value.replace(
                    /^([1-9]\/|[2-9])$/g, '0$1/'
                ).replace(
                    /^(0[1-9]|1[0-2])$/g, '$1/'
                ).replace(
                    /^([0-1])([3-9])$/g, '0$1/$2'
                ).replace(
                    /^(0?[1-9]|1[0-2])([0-9]{2})$/g, '$1/$2'
                ).replace(
                    /^([0]+)\/|[0]+$/g, '0'
                ).replace(
                    /[^\d\/]|^[\/]*$/g, ''
                ).replace(
                    /\/\//g, '/'
                );
            }

            function formatCardNumber(e) {
                event.target.value = event.target.value.replace(/\D/g, '').replace(/(\d{4})/, '$1 ').replace(/(\d{4}) (\d{4})/,
                    '$1 $2 ').replace(/(\d{4}) (\d{4}) (\d{4})/, '$1 $2 $3 ');
            }
            $('#package-payment-form').validate({
                ignore: [],
                rules: {
                    card_number: {
                        required: true,
                        minlength: 19,
                        maxlength: 19,
                    },
                    expiry_date: {
                        required: true
                    },
                    cvv_code: {
                        required: true,
                    },
                    name_on_card: {
                        required: true,
                    },
                    zipcode: {
                        required: true
                    },
                },
                messages: {
                    card_number: {
                        required: "Please enter card number",
                        minlength: "Card number should be 16 digits long",
                        maxlength: "Card number should be 16 digits long",
                    },
                    expiry_date: {
                        required: "Please enter expiry"
                    },
                    cvv_code: {
                        required: "Please enter cvv code",
                    },
                    name_on_card: {
                        required: "Please enter name on card",
                    },
                    zipcode: {
                        required: "Please enter zip code"
                    },
                },
                submitHandler: function(form, e) {
                    Stripe.setPublishableKey(
                        "{{ env('STRIPE_KEY') }}"
                    );

                    $form.on('submit', function(e) {
                        if (!$form.data('cc-on-file')) {
                            e.preventDefault();
                            Stripe.setPublishableKey($form.data(
                                "{{ env('STRIPE_KEY') }}"
                            ));
                            Stripe.createToken({
                                number: $('.card-number').val(),
                                cvc: $('.card-cvc').val(),
                                exp_month: $('.card-expiry-month').val(),
                                exp_year: $('.card-expiry-year').val()
                            }, stripeResponseHandler);
                        }
                    });

                    function stripeResponseHandler(status, response) {
                        if (response.error) {
                            $('.error')
                                .removeClass('hide')
                                .find('.alert')
                                .text(response.error.message);
                        } else {
                            // token contains id, last4, and card type
                            var token = response['id'];
                            // insert the token into the form so it gets submitted to the server
                            $form.find('input[type=text]').empty();
                            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                            console.log(token);
                            $form.get(0).submit();
                        }
                    }
                    e.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: $(form).attr('action'),
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.success == true) {
                                showSuccessAlert();
                            } else {
                                swal({
                                    text: response.message,
                                    icon: "error",
                                    button: "Ok",
                                });
                            }
                        }
                    });
                }
            });
        </script>
    @endsection
