@php
    $url = '';
    $reservation_date_checked = old('require_end_date') == 'on' ? 'checked' : '';
    $name_value = old('name');
    $price_value = old('price');
    $selected_category = old('category_id');
    $selected_type = old('type');
    $selected_unit = old('unit');
    $selected_currency = old('currency', 'SYP');
    $description = old('description');
    $active = 'checked';
    $hasOrders = false;
    $feesCheckbox = '';
    $feesNumberRadio = '';
    $feesPercentageRadio = '';
    $numberFeesAmount = '';
    $percentageFeesAmount = '';
    $imageUrl = null;
    $taxCheckbox = '';
    $taxNumberRadio = '';
    $taxPercentageRadio = '';
    $numberTaxAmount = '';
    $percentageTaxAmount = '';

    $labels = [__('app.products.info.Name'), __('app.products.info.Price'), __('app.products.info.Category'), __('app.products.info.Type'), __('app.products.info.Unit'), __('app.currency')];

    $units = [
        'quantity' => __('app.products.unit.quantity'),
        'period-of-time' => __('app.products.unit.period-of-time'),
    ];

    $types = [
        'product' => __('app.products.type.product'),
        'reservation' => __('app.products.type.reservation'),
    ];

    $currency_types = [
        'SYP' => __('app.currency_types.SYP'),
        'USD' => __('app.currency_types.USD'),
    ];

    if ($product) {
        $url = Storage::url($product->image_url);
        $imageUrl = $product->image_url;
        $name_value = old('name', $product->name);
        $selected_category = old('category_id', $product->category->id);
        $selected_type = old('type', $product->type);
        $selected_unit = old('unit', $product->unit);
        $price_value = old('price', $product->price);
        $active = old('active', $product->active ? 'on' : '') == 'on' ? 'checked' : '';
        $selected_currency = old('currency', $product->currency);
        $description = old('description', preg_replace('/\r\n\r\n/', '<span><br></span>', $product->description));

        $reservation_date_checked = old('require_end_date', $product->require_end_date ? 'on' : '') == 'on' ? 'checked' : '';

        $hasOrders = $product->hasOrders;

        if ($product->has_special_fees) {
            $feesCheckbox = 'on';
            if ($product->fees_type == 'percentage') {
                $feesPercentageRadio = 'percentage';
                $percentageFeesAmount = $product->fees_amount;
                $feesNumberRadio = '';
            } else {
                $feesNumberRadio = 'number';
                $numberFeesAmount = $product->fees_amount;
                $feesPercentageRadio = '';
            }
        }

        if ($product->has_special_tax) {
            $taxCheckbox = 'on';
            if ($product->tax_type == 'percentage') {
                $taxPercentageRadio = 'percentage';
                $percentageTaxAmount = $product->tax_amount;
            } else {
                $taxNumberRadio = 'number';
                $numberTaxAmount = $product->tax_amount;
            }
        }
    }

    $feesCheckbox = old('has_special_fees', $feesCheckbox) == 'on' ? 'checked' : '';
    $feesNumberRadio = old('fees_type', $feesNumberRadio) == 'number' ? 'checked' : '';
    $feesPercentageRadio = old('fees_type', $feesPercentageRadio) == 'percentage' ? 'checked' : '';
    $numberFeesAmount = old('number_fees_amount', $numberFeesAmount);
    $percentageFeesAmount = old('percentage_fees_amount', $percentageFeesAmount);

    $taxCheckbox = old('has_special_tax', $taxCheckbox) == 'on' ? 'checked' : '';
    $taxNumberRadio = old('tax_type', $taxNumberRadio) == 'number' ? 'checked' : '';
    $taxPercentageRadio = old('tax_type', $taxPercentageRadio) == 'percentage' ? 'checked' : '';
    $numberTaxAmount = old('number_tax_amount', $numberTaxAmount);
    $percentageTaxAmount = old('percentage_tax_amount', $percentageTaxAmount);

    $current_route = Route::currentRouteName();
    if ($current_route === 'products.create') {
        $route = route('products.store');
    } else {
        $route = route('products.update', [$product]);
    }

@endphp

<!--begin::Form-->
<form class="form d-flex flex-column flex-lg-row" action="{{ $route }}" method="post" enctype="multipart/form-data">
    @csrf

    <!--begin::image-->
    <div class=" {{ $current_route }} d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
        <x-inputs.image :name="'image_url'" :url="$url" />
        <input type="hidden" name="previous_image" value="{{ $imageUrl ?? '' }}" />
    </div>
    <!--end::image-->

    <!--begin::Main column-->
    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
        <!--begin::Tab content-->
        <div class="tab-content">
            <!--begin::Tab pane-->
            <div class="tab-pane fade show active" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <!--begin::General options-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{ __('app.products.product_info') }}</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">

                            <!--begin::Name-->
                            <div class="mb-10 fv-row">
                                <x-inputs.text_input :label="$labels[0]" :name="'name'" :value="$name_value">
                                </x-inputs.text_input>
                            </div>
                            <!--end::Name-->

                            <!--begin::Price-->
                            <div class="mb-10 fv-row">
                                <x-inputs.number_input :label="$labels[1]" :name="'price'" :price="$price_value">
                                </x-inputs.number_input>
                            </div>
                            <!--end::Price-->

                            @if (auth()->user()->store->currency_status)
                                <!--begin::Currency-->
                                <div class="mb-10 fv-row">
                                    <x-inputs.select_input :label="$labels[5]" :name="'currency'" :items="$currency_types"
                                        :selected="$selected_currency">
                                    </x-inputs.select_input>
                                </div>
                                <!--end::Currency-->
                            @endif

                            <!--begin::Fees checkbox-->
                            <div class="mb-10 fv-row">
                                <input class="form-check-input" type="checkbox" value="on" name="has_special_fees"
                                    id="feesCheckbox" {{ $feesCheckbox }}>
                                <label class="form-label checkbox_label" for="feesCheckbox">
                                    أجور خاصة بالمنتج
                                </label>
                                (عند تفعيل هذا الخيار لا يتم احتساب الأجور المحددة على مستوى المتجر)
                            </div>
                            <!--end::Fees checkbox-->

                            <!--begin::Fees div-->
                            <div
                                class="pt-0 feesDiv {{ $feesPercentageRadio || $feesNumberRadio == 'checked' ? '' : 'd-none' }}">
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
                                                    <input
                                                        class="form-check-input @error('fees_type') is-invalid @enderror"
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
                                            <input type="number" step="any"
                                                class="form-control w-100px fw-bolder @error('percentage_fees_amount') is-invalid @enderror"
                                                id="percentageValue" name="percentage_fees_amount"
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

                            <!--begin::Tax checkbox-->
                            <div class="mb-10 fv-row">
                                <input class="form-check-input" type="checkbox" value="on" name="has_special_tax"
                                    id="taxCheckbox" {{ $taxCheckbox }}>
                                <label class="form-label checkbox_label" for="taxCheckbox">
                                    ضريبة خاصة بالمنتج
                                </label>
                                (عند تفعيل هذا الخيار لا يتم احتساب الضريبة المحددة على مستوى المتجر)
                            </div>
                            <!--end::Tax checkbox-->

                            <!--begin::Tax div-->
                            <div
                                class="pt-0 taxDiv {{ $taxPercentageRadio || $taxNumberRadio == 'checked' ? '' : 'd-none' }}">
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
                                                    <input
                                                        class="form-check-input @error('tax_type') is-invalid @enderror"
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
                                    <input type="number" step="any" name="number_tax_amount" value="{{ $numberTaxAmount }}"
                                        class="taxNumberAmount  form-control mb-2 @error('number_tax_amount') is-invalid @enderror"
                                        placeholder="" />
                                    @error('number_tax_amount')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Tax div-->

                            <!--begin::Category-->
                            <div class="mb-10 fv-row">
                                <x-inputs.select_input :label="$labels[2]" :name="'category_id'" :items="$categories"
                                    :selected="$selected_category">
                                </x-inputs.select_input>
                            </div>
                            <!--end::Category-->

                            <!--begin::Type-->
                            <div class="mb-10 fv-row">
                                <x-inputs.select_input :label="$labels[3]" :name="'type'" :items="$types"
                                    :selected="$selected_type">
                                </x-inputs.select_input>
                            </div>
                            @if ($hasOrders)
                                <input type="hidden" name="type" value="{{ $selected_type }}" />
                            @endif
                            <!--end::Type-->

                            <!-- begin::Checkbox For Reservation Type-->
                            <div
                                class="mb-10 fv-row checkboxDiv {{ $selected_type === 'reservation' ? '' : 'd-none' }}">
                                <input class="form-check-input endDate @error('endDate') is-invalid @enderror"
                                    type="checkbox" value="on" name="require_end_date" id="flexCheckChecked"
                                    {{ $reservation_date_checked }} @disabled($hasOrders)>
                                <label class="form-label checkbox_label" for="flexCheckChecked">
                                    {{ __('app.products.required_end_date') }}
                                </label>
                                @error('endDate')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            @if ($hasOrders)
                                <input type="hidden" name="require_end_date"
                                    value="{{ $reservation_date_checked ? 'on' : '' }}" />
                            @endif
                            <!-- end::Checkbox For Reservation Type-->

                            <!--begin::Active-->
                            <div class="col-lg-12 d-flex align-items-center mb-10">
                                <!--begin::Label-->
                                <label class="col-lg-4 form-label">
                                    حالة المنتج
                                </label>
                                <!--end::Label-->
                                <!--begin::Switch-->
                                <div
                                    class="form-check form-switch form-switch-sm form-check-custom form-check-solid m-1">
                                    <input class="form-check-input" type="checkbox" value="on" name="active"
                                        style="width: 40px; height:24px;" {{ $active ? 'checked' : 'unchecked' }} />
                                </div>
                                <!--end::Switch-->
                            </div>
                            <!--end::Active-->

                            <!--begin::Describition-->
                            <div class="mb-10 fv-row">
                                <label class="col-lg-4 form-label">
                                    الوصف
                                </label>
                                <!-- Create the editor container -->
                                <div id="editor" class="min-h-200px"></div>
                                <textarea name='description' id="description" class="d-none"></textarea>
                            </div>
                            <!--end::Describition-->

                            <!--Buttons-->
                            <div class="d-flex justify-content-end">
                                <!--Save button-->
                                <x-inputs.save_button></x-inputs.save_button>
                                <!--cancel button-->
                                <a href="{{ route('products.index') }}"
                                    class="btn btn-light ms-3">{{ __('app.button.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                    <!--end::General options-->
                </div>
            </div>
            <!--end::Tab pane-->
        </div>
        <!--end::Tab content-->
    </div>
    <!--end::Main column-->
</form>
<!--end::Form-->

@push('script')
    <style>
        .form-check.form-check-solid .form-check-input {
            background-color: var(--bs-gray-200)
        }
    </style>
    <script>
        $(document).ready(function() {
            quill = new Quill('#editor', {
                modules: {
                    toolbar: [
                        [{
                            header: [1, 2, false]
                        }],
                        ['bold', 'italic', 'underline', 'link'],
                        ['align', {
                            align: 'center'
                        }],
                        ['align', {
                            align: 'right'
                        }],
                    ]
                },
                theme: 'snow',
            });

            realHTML = `{!! $description !!}`;
            quill.clipboard.dangerouslyPasteHTML(realHTML);

            quill.on('text-change', function(delta, oldDelta, source) {
                $("#description").html(quill.root.innerHTML);
            });

            $('select[name="type"]').change(function() {
                if ($(this).val() === 'reservation') {
                    $('.checkboxDiv').removeClass('d-none');
                    $('option[value="quantity"]').attr('disabled', 'disabled');
                    $('select[name="unit"]').val('nights').trigger('change');
                } else {
                    $('.checkboxDiv').addClass('d-none');
                    $('option[value="quantity"]').removeAttr('disabled');
                    $('select[name="unit"]').val('quantity').trigger('change');
                }
            });

            $("input[name=name]").focus();

            $('select[name=type]').prop('disabled', '{{ $hasOrders }}');


            // handle fees section
            $('#feesCheckbox').click(function() {
                if ($(this).prop("checked") == true) {
                    $('.feesDiv').removeClass('d-none');
                } else {
                    $('.feesDiv').addClass('d-none');
                }
            });

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

            // fees percentage slider
            var slider = document.querySelector("#percentageSlider");
            var value = document.querySelector("#percentageValue");

            noUiSlider.create(slider, {
                start: "{{ $percentageFeesAmount }}" ?? 0,
                range: {
                    "min": 0,
                    "max": 100,
                },
            });


            slider.noUiSlider.on("update", function(values, handle) {
                value.value = (values[handle]);
            });


            $('#percentageValue').on('change', function() {
                slider.noUiSlider.set($(this).val());
            });

            // handle tax section
            $('#taxCheckbox').click(function() {
                if ($(this).prop("checked") == true) {
                    $('.taxDiv').removeClass('d-none');
                } else {
                    $('.taxDiv').addClass('d-none');
                }
            });

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

            var taxSlider = document.querySelector("#taxPercentageSlider");
            var taxValue = document.querySelector("#taxPercentageValue");

            noUiSlider.create(taxSlider, {
                start: "{{ $percentageTaxAmount }}" ?? 0,
                range: {
                    "min": 0,
                    "max": 100,
                },
            });

            taxSlider.noUiSlider.on("update", function(values, handle) {
                taxValue.value = (values[handle]);
            });

            $('#taxPercentageValue').on('change', function() {
                taxSlider.noUiSlider.set($(this).val());
            });

            // validation
            /* $(".form").validate({
                 ignore: "",
                 rules: {
                     fees_type: {
                         required: {
                             depends: function() {
                                 return $("#feesCheckbox:checked");
                             }
                         }
                     },
                     percentage_fees_amount: {
                         required: {
                             depends: function() {
                                 return $("#percentageInput:checked");
                             }
                         },
                         min: 1
                     },
                     number_fees_amount: {
                         required: {
                             depends: function() {
                                 return $("#fixedNumberInput:checked");
                             }
                         }
                     },
                     tax_type: {
                         required: {
                             depends: function() {
                                 return $("#taxCheckbox:checked");
                             }
                         }
                     },
                     percentage_tax_amount: {
                         required: {
                             depends: function() {
                                 return $("#taxPercentageInput:checked");
                             }
                         },
                         min: 1
                     },
                     number_tax_amount: {
                         required: {
                             depends: function() {
                                 return $("#taxFixedNumberInput:checked");
                             }
                         }
                     }
                 },
                 messages: {
                     fees_type: "يرجى اختيار النوع الخاص بالأجور",
                     percentage_fees_amount: "يرجى اختيار القيمة",
                     number_fees_amount: "يرجى اختيار القيمة",
                     tax_type: "يرجى اختيار النوع الخاص بالضريبة",
                     percentage_tax_amount: "يرجى اختيار القيمة",
                     number_tax_amount: "يرجى اختيار القيمة",
                 },
                 errorPlacement: function(error, element) {
                     if (element.attr("name") === "fees_type") {
                         error.insertAfter(element.parent().parent());
                     } else if (element.attr("name") === "percentage_fees_amount") {
                         error.insertAfter(element);
                     } else if (element.attr("name") === "number_fees_amount") {
                         error.insertAfter(element);
                     } else if (element.attr("name") === "tax_type") {
                         error.insertAfter(element.parent().parent());
                     } else if (element.attr("name") === "percentage_tax_amount") {
                         error.insertAfter(element);
                     } else if (element.attr("name") === "number_tax_amount") {
                         error.insertAfter(element);
                     }
                 }
             });*/
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
