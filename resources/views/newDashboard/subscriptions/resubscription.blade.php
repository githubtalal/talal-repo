@extends('newDashboard.layouts.master')
@section('content')
    <!--begin::Basic info-->
    <div class="content d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div class="toolbar">
            <!--begin::Page title-->
            <div class="container-fluid">
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                    إعادة الاشتراك
                </h1>
            </div>
        </div>
        <!--begin::Container-->
        <div class="container-xxl">
            <div class="card mb-5 mb-xl-10">
                <div class="card-body border-top p-9">
                    <form id="" action="{{ route('resubscription.store') }}" method="post"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="row d-flex align-items-center mb-6">
                            <!--begin::Services-->
                            <div class="fv-row mb-10">
                                <label class="form-label fw-bold fs-2">
                                    الخدمات المتاحة
                                </label>
                                @foreach ($products as $product)
                                    <div class="mb-3 d-flex align-items-center">
                                        <input class="form-check-input" type="checkbox" value="{{ $product->id }}"
                                               name="services[]"/>
                                        <label class="form-label mx-3" for="feature">
                                            {{ __('app.features.' . $product->additional['permission_name']) }}
                                        </label>
                                    </div>
                                @endforeach
                                @error('services')
                                <div class="col-lg-3 alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <!--end::Services-->

                            <!--begin::Payment Methods-->
                            <div class="fv-row">
                                <label class="form-label fw-bold fs-2">
                                    طرق الدفع
                                </label>
                                @foreach ($enabled_payment_methods as $paymentClass)
                                    <div class="mb-3 d-flex align-items-center">
                                        <input class="form-check-input" type="radio" value="{{ $paymentClass->getKey() }}"
                                               name="payment_method"/>
                                        <label class="form-label mx-3 ">
                                            {{ $originalMethods[$paymentClass->getKey()] }}
                                        </label>
                                    </div>
                                    <p style="margin-right: 25px;font-weight: 500;" class="form-label d-none" id="payment_method_description_{{ $paymentClass->getKey() }}">
                                        {!! $paymentClass->getDescription() !!}
                                    </p>
                                    <div class="separator my-5"></div>
                                @endforeach
                                @error('payment_method')
                                <div class="col-lg-3 alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <!--end::Payment Methods-->

                            <!-- Start User Extra Fields -->
                            <div class="fv-row">
                                <label class="form-label fw-bold fs-2">

                                </label>
                                @foreach ($enabled_payment_methods as $paymentClass)
                                    <div id="custom-input-{{$paymentClass->getKey()}}" class="d-none">
                                        @foreach($paymentClass->getExtraUserFields() as $extraField)
                                            <div class="mb-3">
                                                <x-inputs.text_input
                                                    :label="$extraField['label']"
                                                    :name="'additional_user_fields[' . $extraField['name'] . ']'"
                                                >
                                                </x-inputs.text_input>
                                            </div>
                                        @endforeach
                                    </div>

                                @endforeach
                                @error('payment_method')
                                <div class="col-lg-3 alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- End User Extra Field -->
                        </div>

                        <div class="col-lg-3">
                            <button type='submit' id="submit" class='btn btn-primary'>
                                <span class='indicator-label'>{{ __('app.button.save') }}</span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function () {
                $('input[name="payment_method"]').change(function () {
                    let selected_value = $(this);
                    let all_values = $('div[id^=custom-input]');

                    all_values.addClass('d-none');
                    all_values.find('input').removeAttr('required');

                    let all_payment_description = $('p[id^=payment_method_description_]');
                    all_payment_description.addClass('d-none');
                    let selected_payment_description = $(`#payment_method_description_${selected_value.val()}`);
                    selected_payment_description.removeClass('d-none');

                    let selected_value_div = $(`#custom-input-${selected_value.val()}`);
                    selected_value_div.removeClass('d-none');
                    selected_value_div.find('input').attr('required', 'required');
                });
            })
        </script>
    @endpush
@endsection

