@extends('newDashboard.layouts.master')

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div class="container-xxl">
            <div class="card mb-5 mb-xl-10">

                <!--begin::Card header-->
                <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                     data-bs-target="#kt_account_profile_details" aria-expanded="true"
                     aria-controls="kt_account_profile_details">
                    <!--begin::Card title-->
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">{{ __('app.store_setting.store_setting') }}</h3>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Content-->
                <div id="kt_account_settings_profile_details" class="collapse show">
                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">

                        <div id="accordion">
                            <form action="{{ route('addStoreSettings') }}" method="POST" id="form" class="form"
                                  enctype="multipart/form-data">
                                @csrf

                                @foreach ($all_payment_methods as $type => $typeClass)
                                    <div class="col-lg-12 mb-6 heading d-flex justify-content-between"
                                         id="heading{{ $type }}">
                                        <div class="col-lg-6 d-flex align-items-center">
                                            <!--begin::Label-->
                                            <label class="col-form-label fw-bold fs-3 collapsed collapse{{ $type }}"
                                                   data-toggle="collapse" data-target="#collapse{{ $type }}"
                                                   aria-expanded="true" aria-controls="collapse{{ $type }}"
                                                   style="cursor:pointer">
                                                <i class="fa fa-plus m-2"></i>
                                                {{ $payment_methods[$type]['label'] ?? __('payment_methods.payment_methods.' . $typeClass) }}
                                            </label>
                                            <!--end::Label-->
                                            <!--edit icon-->
                                            <a href="#"
                                               class="btn btn-icon btn-active-light-primary w-30px h-30px m-2 {{ $type == 'cod' ? '' : 'd-none' }}"
                                               data-bs-toggle="modal" data-bs-target="#newLabelfor{{ $type }}">
                                                <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Edit">
                                                    <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                             height="24" viewBox="0 0 24 24" fill="none">
                                                            <path opacity="0.3"
                                                                  d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                                                  fill="currentColor"/>
                                                            <path
                                                                d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                                                fill="currentColor"/>
                                                        </svg>
                                                    </span>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </a>
                                        </div>
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            @php
                                                $checked = '';
                                                if ($payment_methods and array_key_exists($type, $payment_methods) and array_key_exists('enabled', $payment_methods[$type])) {
                                                    $checked = 'checked';
                                                }
                                            @endphp
                                            <div
                                                class="form-check form-switch form-switch-sm form-check-custom form-check-solid mt-2">
                                                <input class="form-check-input check" type="checkbox" value="true"
                                                       id="{{ $type }}"
                                                       name="payment_method[{{ $type }}][enabled]"
                                                       {{ $checked }} style="width: 40px; height:24px;"/>
                                            </div>
                                        </div>
                                        <!--end::Col-->
                                    </div>

                                    <!-- fields -->
                                    @inject('object', $typeClass)
                                    <div class="row col-lg-4 element collapse" id="collapse{{ $type }}"
                                         aria-labelledby="heading{{ $type }}" data-parent="#accordion">

                                        @php $fields = auth()->user()->isStoreAdmin() ? $object->getBasicInfo() : $object->getBaseBasicInfo() ; @endphp
                                        @if ($object->hasTestEnv())
                                                <?php
                                                $is_test = $payment_methods[$type]['is_test'] ?? false
                                                ?>
                                            <div class="col-lg-12 d-flex align-items-center mb-5">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 form-label">
                                                    بيئة تجيريبية
                                                </label>
                                                <!--end::Label-->
                                                <!--begin::Switch-->
                                                <div
                                                    class="form-check form-switch form-switch-sm form-check-custom form-check-solid m-1">
                                                    <input class="form-check-input" type="checkbox" value="1"
                                                           name="payment_method[{{ $type }}][is_test]"
                                                           style="width: 40px; height:24px;" {{ $is_test ? 'checked' : '' }} >
                                                </div>
                                                <!--end::Switch-->
                                            </div>
                                        @endif

                                        @foreach ($fields as $field)
                                            <!--begin::Label-->
                                            <div class="mb-5 fv-row">
                                                <label
                                                    class="form-label">{{ __('payment_methods.fields.' . $field['name']) }}</label>
                                                <!--end::Label-->

                                                @php
                                                    $value = '';
                                                    $pattern = '';
                                                    if ($payment_methods and array_key_exists($type, $payment_methods)) {
                                                        $value = $payment_methods[$type][$field['name']] ?? '';
                                                        if (array_key_exists('pattern', $field)) {
                                                            $pattern = $field['pattern'] ?? '';
                                                        }
                                                    }
                                                @endphp

                                                    <!--begin::Input-->
                                                <input type="{{ $field['type'] }}"
                                                       name="payment_method[{{ $type }}][{{ $field['name'] }}]"
                                                       class="form-control mb-2 data" id={{ $type }}
                                                placeholder="{{ __('payment_methods.fields.' . $field['name']) }}"
                                                       value="{{ $value }}" autocomplete={{ $field['autocomplete'] }}
                                                    {{ $pattern ? "pattern=$pattern" : '' }} />
                                                <!--end::Input-->
                                            </div>

                                        @endforeach

                                    </div>

                                    <!-- edit label Modal -->
                                    <div class="modal fade" id="newLabelfor{{ $type }}" tabindex="-1"
                                         role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">ادخل اسم الحقل</h5>
                                                </div>
                                                @php
                                                    $value = '';
                                                    if ($payment_methods and array_key_exists($type, $payment_methods)) {
                                                        $value = $payment_methods[$type]['label'] ?? '';
                                                    }
                                                @endphp
                                                <div class="modal-body">
                                                    <input type="text" name="payment_method[{{ $type }}][label]"
                                                           class="form-control" value="{{ $value }}"/>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">حفظ</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                                            onclick="$('#newLabelfor{{ $type }}').modal('hide');">إلغاء
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <!--Save Button-->
                                <button type='submit' id="submit" class='btn btn-primary'>
                                    <span class='indicator-label'>{{ __('app.button.save') }}</span>
                                </button>
                            </form>
                            <!--end::Form-->
                        </div>
                    </div>
                    <!--end::Card body-->

                </div>
                <!--end::Content-->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Add minus icon for collapse element which
            // is open by default
            $(".collapse.show").each(function () {
                $(this).prev(".heading").find(".fa")
                    .addClass("fa-minus").removeClass("fa-plus");
            });
            // Toggle plus minus icon on show hide
            // of collapse element
            $(".collapse").on('show.bs.collapse', function () {
                $(this).prev(".heading").find(".fa")
                    .removeClass("fa-plus").addClass("fa-minus");
            }).on('hide.bs.collapse', function () {
                $(this).prev(".heading").find(".fa")
                    .removeClass("fa-minus").addClass("fa-plus");
            });

            // process validation with accrodion
            $("#submit").click(function () {
                $(".data").each(function () {

                    var id = $(this).attr('id');

                    if ($(".check" + "#" + id).is(':checked')) {

                        // add required property to input
                        $(this).prop('required', true);

                        // check if valid (has value) to handle accrodion (open/close)
                        if (!$(this).val()) {
                            $('label.collapse' + id).removeClass('collapsed')
                                .attr('aria-expanded', 'true')
                                .find(".fa").removeClass("fa-plus").addClass("fa-minus");
                            $('div#collapse' + id).addClass('show');
                        }

                    } else {
                        $(this).removeAttr('required');
                    }

                    // check if this input has pattern (phone number) to handle accrodion (open/close)
                    if ($(this).prop('pattern') && $(this).val() && !$(this).val().match(
                        /[09]{2}[0-9]{8}/)) {
                        $('label.collapse' + id).removeClass('collapsed')
                            .attr('aria-expanded', 'true')
                            .find(".fa").removeClass("fa-plus").addClass("fa-minus");
                        $('div#collapse' + id).addClass('show');
                    }

                });
            });
        });
    </script>
@endpush
