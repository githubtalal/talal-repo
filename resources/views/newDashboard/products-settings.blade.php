@extends('newDashboard.layouts.master')
@section('content')
    @php
        // fees section
        $numberFeesAmount = '';
        $percentageFeesAmount = '';
        $noFees = '';

        $feesNumberRadio = old('fees_type', $customSettingsValue['fees_type'] ?? '') == 'number' ? 'checked' : '';
        $feesPercentageRadio = old('fees_type', $customSettingsValue['fees_type'] ?? '') == 'percentage' ? 'checked' : '';

        if ($feesNumberRadio) {
            $numberFeesAmount = old('number_fees_amount', $customSettingsValue['fees_amount'] ?? '');
        }

        if ($feesPercentageRadio) {
            $percentageFeesAmount = old('percentage_fees_amount', $customSettingsValue['fees_amount'] ?? '');
        }

        if (!$feesNumberRadio && !$feesPercentageRadio) {
            $noFees = 'checked';
        }

        // tax section
        $numberTaxAmount = '';
        $percentageTaxAmount = '';
        $noTax = '';

        $taxNumberRadio = old('tax_type', $customSettingsValue['tax_type'] ?? '') == 'number' ? 'checked' : '';
        $taxPercentageRadio = old('tax_type', $customSettingsValue['tax_type'] ?? '') == 'percentage' ? 'checked' : '';

        if ($taxNumberRadio) {
            $numberTaxAmount = old('number_tax_amount', $customSettingsValue['tax_amount'] ?? '');
        }
        if ($taxPercentageRadio) {
            $percentageTaxAmount = old('percentage_tax_amount', $customSettingsValue['tax_amount'] ?? '');
        }

        if (!$taxNumberRadio && !$taxPercentageRadio) {
            $noTax = 'checked';
        }

    @endphp

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div class="container-xxl">
            <div class="card mb-5 mb-xl-10">
                <!--begin::Card header-->
                <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target=""
                    aria-expanded="true" aria-controls="">
                    <!--begin::Card title-->
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">
                            أجور إضافية
                        </h3>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    <form action="{{ route('custom-settings.store') }}" method="POST" id="form" class="form"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="col-lg-8">

                            <label class="form-label fw-bolder text-dark">
                                أجور الخدمة:
                            </label>
                            <!--begin::Fees div-->
                            <div class="pt-0 feesDiv">
                                <!--begin::Input group-->
                                <div class="fv-row mb-10">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold mb-2">النوع
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                            title="Select a discount type that will be applied to this product"></i></label>
                                    <!--End::Label-->
                                    <!--begin::Row-->
                                    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-1 row-cols-xl-3 g-9"
                                        data-kt-buttons="true" data-kt-buttons-target="[data-kt-button='true']">
                                        <!--begin::Col-->
                                        <div class="col">
                                            <!--begin::Option-->
                                            <label
                                                class="btn btn-outline btn-outline-dashed btn-outline-default d-flex text-start p-6 feesTypeLabel"
                                                data-kt-button="true">
                                                <!--begin::Radio-->
                                                <span
                                                    class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                    <input class="form-check-input @error('fees_type') is-invalid @enderror"
                                                        type="radio" id="percentageInput" name="fees_type"
                                                        value="percentage" {{ $feesPercentageRadio }} />
                                                </span>
                                                <!--end::Radio-->
                                                <!--begin::Info-->
                                                <span class="ms-5">
                                                    <span class="fs-4 fw-bolder text-gray-800 d-block">نسبة
                                                        %</span>
                                                </span>
                                                <!--end::Info-->
                                            </label>
                                            <!--end::Option-->
                                        </div>
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col">
                                            <!--begin::Option-->
                                            <label
                                                class="btn btn-outline btn-outline-dashed btn-outline-default d-flex text-start p-6"
                                                data-kt-button="true">
                                                <!--begin::Radio-->
                                                <span
                                                    class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                    <input class="form-check-input" type="radio" id="fixedNumberInput"
                                                        name="fees_type" value="number" {{ $feesNumberRadio }} />
                                                </span>
                                                <!--end::Radio-->
                                                <!--begin::Info-->
                                                <span class="ms-5">
                                                    <span class="fs-4 fw-bolder text-gray-800 d-block">رقم ثابت</span>
                                                </span>
                                                <!--end::Info-->
                                            </label>
                                            <!--end::Option-->
                                        </div>
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col">
                                            <!--begin::Option-->
                                            <label
                                                class="btn btn-outline btn-outline-dashed btn-outline-default d-flex text-start p-6"
                                                data-kt-button="true">
                                                <!--begin::Radio-->
                                                <span
                                                    class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                    <input class="form-check-input" type="radio" id="noFees"
                                                        name="fees_type" value="noFees" {{ $noFees }} />
                                                </span>
                                                <!--end::Radio-->
                                                <!--begin::Info-->
                                                <span class="ms-5">
                                                    <span class="fs-4 fw-bolder text-gray-800 d-block">لايوجد</span>
                                                </span>
                                                <!--end::Info-->
                                            </label>
                                            <!--end::Option-->
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    @error('fees_type')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    <!--end::Row-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row {{ $feesPercentageRadio == 'checked' ? '' : 'd-none' }}"
                                    id="percentageDiv">
                                    <!--begin::Label-->
                                    <label class="form-label">القيمة</label>
                                    <!--end::Label-->
                                    <!--begin::Slider-->
                                    <div class="d-flex flex-column text-center mb-5">
                                        <div class="d-flex align-items-center justify-content-center mb-7">
                                            <input type="number"
                                                class="form-control w-100px fw-bolder @error('percentage_fees_amount') is-invalid @enderror"
                                                id="percentageValue" step="any" name="percentage_fees_amount"
                                                value="{{ $percentageFeesAmount }}" />
                                            <span class="fw-bolder fs-4 mt-1 ms-2">%</span>
                                        </div>
                                        <div id="percentageSlider"></div>
                                        @error('percentage_fees_amount')
                                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!--end::Slider-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row {{ $feesNumberRadio == 'checked' ? '' : 'd-none' }}"
                                    id="fixedNumberDiv">
                                    <!--begin::Label-->
                                    <label class="form-label">القيمة</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="number" name="number_fees_amount" value="{{ $numberFeesAmount }}"
                                        class="numberAmount form-control mb-2 @error('number_fees_amount') is-invalid @enderror"
                                        placeholder="" />
                                    @error('number_fees_amount')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Fees div-->

                            <label class="form-label fw-bolder text-dark">
                                الضريبة:
                            </label>
                            <!--begin::Tax div-->
                            <div class="pt-0 taxDiv">
                                <!--begin::Input group-->
                                <div class="fv-row mb-10">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold mb-2">النوع
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                            title="Select a discount type that will be applied to this product"></i></label>
                                    <!--End::Label-->
                                    <!--begin::Row-->
                                    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-1 row-cols-xl-3 g-9"
                                        data-kt-buttons="true" data-kt-buttons-target="[data-kt-button='true']">
                                        <!--begin::Col-->
                                        <div class="col">
                                            <!--begin::Option-->
                                            <label
                                                class="btn btn-outline btn-outline-dashed btn-outline-default d-flex text-start p-6"
                                                data-kt-button="true">
                                                <!--begin::Radio-->
                                                <span
                                                    class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                    <input class="form-check-input @error('tax_type') is-invalid @enderror"
                                                        type="radio" id="taxPercentageInput" name="tax_type"
                                                        value="percentage" {{ $taxPercentageRadio }} />
                                                </span>
                                                <!--end::Radio-->
                                                <!--begin::Info-->
                                                <span class="ms-5">
                                                    <span class="fs-4 fw-bolder text-gray-800 d-block">نسبة
                                                        %</span>
                                                </span>
                                                <!--end::Info-->
                                            </label>
                                            <!--end::Option-->
                                        </div>
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col">
                                            <!--begin::Option-->
                                            <label
                                                class="btn btn-outline btn-outline-dashed btn-outline-default d-flex text-start p-6"
                                                data-kt-button="true">
                                                <!--begin::Radio-->
                                                <span
                                                    class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                    <input class="form-check-input" type="radio"
                                                        id="taxFixedNumberInput" name="tax_type" value="number"
                                                        {{ $taxNumberRadio }} />
                                                </span>
                                                <!--end::Radio-->
                                                <!--begin::Info-->
                                                <span class="ms-5">
                                                    <span class="fs-4 fw-bolder text-gray-800 d-block">رقم ثابت</span>
                                                </span>
                                                <!--end::Info-->
                                            </label>
                                            <!--end::Option-->
                                        </div>
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col">
                                            <!--begin::Option-->
                                            <label
                                                class="btn btn-outline btn-outline-dashed btn-outline-default d-flex text-start p-6"
                                                data-kt-button="true">
                                                <!--begin::Radio-->
                                                <span
                                                    class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                    <input class="form-check-input" type="radio" id="noTax"
                                                        name="tax_type" value="noTax" {{ $noTax }} />
                                                </span>
                                                <!--end::Radio-->
                                                <!--begin::Info-->
                                                <span class="ms-5">
                                                    <span class="fs-4 fw-bolder text-gray-800 d-block">لايوجد</span>
                                                </span>
                                                <!--end::Info-->
                                            </label>
                                            <!--end::Option-->
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    @error('tax_type')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    <!--end::Row-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row {{ $taxPercentageRadio == 'checked' ? '' : 'd-none' }}"
                                    id="taxPercentageDiv">
                                    <!--begin::Label-->
                                    <label class="form-label">القيمة</label>
                                    <!--end::Label-->
                                    <!--begin::Slider-->
                                    <div class="d-flex flex-column text-center mb-5">
                                        <div class="d-flex align-items-center justify-content-center mb-7">
                                            <input type="number"
                                                class="form-control w-100px fw-bolder @error('percentage_tax_amount') is-invalid @enderror"
                                                id="taxPercentageValue" step="any" name="percentage_tax_amount"
                                                value="{{ $percentageTaxAmount }}" />
                                            <span class="fw-bolder fs-4 mt-1 ms-2">%</span>
                                        </div>
                                        <div id="taxPercentageSlider"></div>
                                        @error('percentage_tax_amount')
                                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!--end::Slider-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row {{ $taxNumberRadio == 'checked' ? '' : 'd-none' }}"
                                    id="taxFixedNumberDiv">
                                    <!--begin::Label-->
                                    <label class="form-label">القيمة</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="number" name="number_tax_amount" value="{{ $numberTaxAmount }}"
                                        class="taxNumberAmount form-control mb-2 @error('number_tax_amount') is-invalid @enderror"
                                        placeholder="" />
                                    @error('number_tax_amount')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Tax div-->
                        </div>
                        <!--Save Button-->
                        <button type='submit' id="submit" class='btn btn-primary formSubmit'>
                            <span class='indicator-label'>{{ __('app.button.save') }}</span>
                        </button>
                    </form>
                </div>
                <!--end::Card body-->
            </div>
        </div>
    </div>
@endsection


@push('script')
    <style>
        .form-check.form-check-solid .form-check-input {
            background-color: var(--bs-gray-200)
        }
    </style>
    <script>
        $(document).ready(function() {
            // handle fees section

            $('#percentageInput').click(function() {
                if ($(this).prop("checked") == true) {
                    $('#percentageDiv').removeClass('d-none');
                    $('#fixedNumberDiv').addClass('d-none');
                }
            });

            $('#fixedNumberInput').click(function() {
                if ($(this).prop("checked") == true) {
                    $('#fixedNumberDiv').removeClass('d-none');
                    $('#percentageDiv').addClass('d-none');
                }
            });

            $('#noFees').click(function() {
                if ($(this).prop("checked") == true) {
                    $('#fixedNumberDiv').addClass('d-none');
                    $('#percentageDiv').addClass('d-none');
                }
            });

            // fees percentage slider
            var slider = document.querySelector("#percentageSlider");
            var value = document.querySelector("#percentageValue");

            noUiSlider.create(slider, {
                start: "{{ $percentageFeesAmount }}" ?? 0,
                range: {
                    "min": 0,
                    "max": 100,
                },
                // step: 1
            });


            slider.noUiSlider.on("update", function(values, handle) {
                value.value = (values[handle]);
            });


            $('#percentageValue').on('change', function() {
                slider.noUiSlider.set($(this).val());
            });

            // handle tax section
            $('#taxPercentageInput').click(function() {
                if ($(this).prop("checked") == true) {
                    $('#taxPercentageDiv').removeClass('d-none');
                    $('#taxFixedNumberDiv').addClass('d-none');
                }
            });

            $('#taxFixedNumberInput').click(function() {
                if ($(this).prop("checked") == true) {
                    $('#taxFixedNumberDiv').removeClass('d-none');
                    $('#taxPercentageDiv').addClass('d-none');
                }
            });

            $('#noTax').click(function() {
                if ($(this).prop("checked") == true) {
                    $('#taxFixedNumberDiv').addClass('d-none');
                    $('#taxPercentageDiv').addClass('d-none');
                }
            });

            var taxSlider = document.querySelector("#taxPercentageSlider");
            var taxValue = document.querySelector("#taxPercentageValue");

            noUiSlider.create(taxSlider, {
                start: "{{ $percentageTaxAmount }}" ?? 0,
                range: {
                    "min": 0,
                    "max": 100,
                },
                // step: 0.01
            });

            taxSlider.noUiSlider.on("update", function(values, handle) {
                taxValue.value = (values[handle]);
            });

            $('#taxPercentageValue').on('change', function() {
                taxSlider.noUiSlider.set($(this).val());
            });

        });
    </script>
    @if ($errors->has('fees_type'))
        <script>
            $('.feesDiv').removeClass('d-none');
        </script>
    @endif
    @if ($errors->has('tax_type'))
        <script>
            $('.taxDiv').removeClass('d-none');
        </script>
    @endif
@endpush
