@extends('newDashboard.layouts.master')

@section('content')
    @php
        $notes = __('app.order.customer_info.Notes');
        $power_button = false;
        $governorate = false;
        $address = false;
        $notes_step = false;
        $hour = false;
        $additional_message = '';
        $enabled_message = false;
        $ifQ1Exist = false;
        $ifQ2Exist = false;
        $q1Value = __('app.steps.question1');
        $q2Value = __('app.steps.question2');
        
        if ($bot_settings) {
            if (array_key_exists('notes', $bot_settings) && $bot_settings['notes']) {
                $notes = $bot_settings['notes'];
            }
        
            if (array_key_exists('question1', $bot_settings) && $bot_settings['question1']) {
                $q1Value = $bot_settings['question1'];
            }
        
            if (array_key_exists('question2', $bot_settings) && $bot_settings['question2']) {
                $q2Value = $bot_settings['question2'];
            }
        
            if (array_key_exists('power_button', $bot_settings)) {
                $power_button = true;
            }
        
            // get steps info
            if (array_key_exists('steps', $bot_settings)) {
                $steps = $bot_settings['steps'];
                if (array_key_exists('governorate', $steps)) {
                    $governorate = true;
                }
                if (array_key_exists('address', $steps)) {
                    $address = true;
                }
                if (array_key_exists('notes', $steps)) {
                    $notes_step = true;
                }
                if (array_key_exists('question1', $steps)) {
                    $ifQ1Exist = true;
                }
                if (array_key_exists('question2', $steps)) {
                    $ifQ2Exist = true;
                }
            }
        
            if (array_key_exists('hour', $bot_settings)) {
                $hour = true;
            }
            if (array_key_exists('additional_message', $bot_settings)) {
                $additional_message = $bot_settings['additional_message']['text'];
        
                if (array_key_exists('status', $bot_settings['additional_message'])) {
                    $enabled_message = true;
                }
            }
        }
        
    @endphp
    <div class="content d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div class="toolbar">
            <!--begin::Page title-->
            <div class="container-fluid">
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                    {{ __('app.store_setting.store_flow') }}
                </h1>
            </div>
        </div>
        <div class="container-xxl">
            <div class="card mb-5 mb-xl-10">
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    <form action="{{ route('add_bot_settings') }}" method="POST" id="form" class="form"
                        enctype="multipart/form-data">
                        @csrf

                        <label class="form-label fw-bolder text-dark">
                            إعدادات عامة:
                        </label>


                        <!-- Governorate -->
                        <div class="col-lg-12 mb-10 d-flex align-items-center justify-content-between">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-bold fs-3 me-4" id="">
                                المحافظة
                            </label>
                            <!--end::Label-->
                            <!--begin::Switch-->
                            <div class="col-lg-6">
                                <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid mt-2">
                                    <input class="form-check-input" type="checkbox" value="enabled"
                                        name="bot_settings[steps][governorate]" {{ $governorate ? 'checked' : 'unchecked' }}
                                        style="width: 40px; height:24px;" />
                                </div>
                            </div>
                            <!--end::Switch-->
                        </div>

                        <!-- Address -->
                        <div class="col-lg-12 mb-10 d-flex align-items-center justify-content-between">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-bold fs-3 me-4" id="">
                                العنوان
                            </label>
                            <!--end::Label-->
                            <!--begin::Switch-->
                            <div class="col-lg-6">
                                <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid mt-2">
                                    <input class="form-check-input" type="checkbox" value="enabled"
                                        name="bot_settings[steps][address]" {{ $address ? 'checked' : 'unchecked' }}
                                        style="width: 40px; height:24px;" />
                                </div>
                            </div>
                            <!--end::Switch-->
                        </div>

                        <!-- Hour -->
                        <div class="col-lg-12 mb-10 d-flex align-items-center justify-content-between">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-bold fs-3 me-4" id="">
                                تحديد الساعة عند الحجز
                            </label>
                            <!--end::Label-->
                            <!--begin::Switch-->
                            <div class="col-lg-6">
                                <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid mt-2">
                                    <input class="form-check-input" type="checkbox" value="enabled"
                                        name="bot_settings[hour]" {{ $hour ? 'checked' : 'unchecked' }}
                                        style="width: 40px; height:24px;" />
                                </div>
                            </div>
                            <!--end::Switch-->
                        </div>

                        <label class="form-label fw-bolder text-dark">الأسئلة المخصصة:</label>

                        <!-- Notes -->
                        <div class="col-lg-12 mb-10 d-flex align-items-center justify-content-between">

                            <div class="col-lg-4 d-flex align-items-center fs-3 me-4">
                                <!--begin::Label-->
                                <label class="col-form-label fw-bold" id="">
                                    {{ $notes }}
                                </label>
                                <!--end::Label-->

                                <!--edit icon-->
                                <a href="#" class="btn btn-icon btn-active-light-primary w-30px h-30px m-2"
                                    data-bs-toggle="modal" data-bs-target="#notes">
                                    <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Edit">
                                        <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.3"
                                                    d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                                    fill="currentColor" />
                                                <path
                                                    d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                                    fill="currentColor" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                    </span>
                                </a>
                                <!--edit icon-->
                            </div>

                            <!--begin::Switch-->
                            <div class="col-lg-6">
                                <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid mt-2">
                                    <input class="form-check-input" type="checkbox" value="enabled"
                                        name="bot_settings[steps][notes]" {{ $notes_step ? 'checked' : 'unchecked' }}
                                        style="width: 40px; height:24px;" />
                                </div>
                            </div>
                            <!--end::Switch-->
                        </div>

                        <!-- Question 1 -->
                        <div class="col-lg-12 mb-10 d-flex align-items-center justify-content-between">

                            <div class="col-lg-4 d-flex align-items-center fs-3 me-4">
                                <!--begin::Label-->
                                <label class="col-form-label fw-bold" id="">
                                    {{ $q1Value }}
                                </label>
                                <!--end::Label-->

                                <!--edit icon-->
                                <a href="#" class="btn btn-icon btn-active-light-primary w-30px h-30px m-2"
                                    data-bs-toggle="modal" data-bs-target="#question1">
                                    <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Edit">
                                        <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.3"
                                                    d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                                    fill="currentColor" />
                                                <path
                                                    d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                                    fill="currentColor" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                    </span>
                                </a>
                                <!--edit icon-->
                            </div>

                            <!--begin::Switch-->
                            <div class="col-lg-6">
                                <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid mt-2">
                                    <input class="form-check-input" type="checkbox" value="enabled"
                                        {{ $ifQ1Exist ? 'checked' : 'unchecked' }} name="bot_settings[steps][question1]"
                                        style="width: 40px; height:24px;" />
                                </div>
                            </div>
                            <!--end::Switch-->
                        </div>

                        <!-- Question 2 -->
                        <div class="col-lg-12 mb-10 d-flex align-items-center justify-content-between">

                            <div class="col-lg-4 d-flex align-items-center fs-3 me-4">
                                <!--begin::Label-->
                                <label class="col-form-label fw-bold" id="">
                                    {{ $q2Value }}
                                </label>
                                <!--end::Label-->

                                <!--edit icon-->
                                <a href="#" class="btn btn-icon btn-active-light-primary w-30px h-30px m-2"
                                    data-bs-toggle="modal" data-bs-target="#question2">
                                    <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Edit">
                                        <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.3"
                                                    d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                                    fill="currentColor" />
                                                <path
                                                    d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                                    fill="currentColor" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                    </span>
                                </a>
                                <!--edit icon-->
                            </div>

                            <!--begin::Switch-->
                            <div class="col-lg-6">
                                <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid mt-2">
                                    <input class="form-check-input" type="checkbox" value="enabled"
                                        {{ $ifQ2Exist ? 'checked' : 'unchecked' }} name="bot_settings[steps][question2]"
                                        style="width: 40px; height:24px;" />
                                </div>
                            </div>
                            <!--end::Switch-->
                        </div>

                        <label class="form-label fw-bolder text-dark">رسالة مابعد إنشاء الطلب:</label>

                        <!-- Additional Messages -->
                        <div class="col-lg-12 mb-10 d-flex align-items-center justify-content-between">

                            <div class="col-lg-4 d-flex align-items-center fs-3 me-4">
                                <!--begin::Label-->
                                <label class="col-form-label fw-bold additional_message_label" id="">
                                    {{ $additional_message ? $additional_message : __('app.additional_message') }}
                                </label>
                                <!--end::Label-->

                                <!--edit icon-->
                                <a href="#" class="btn btn-icon btn-active-light-primary w-30px h-30px m-2"
                                    data-bs-toggle="modal" data-bs-target="#additional_message">
                                    <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Edit">
                                        <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.3"
                                                    d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                                    fill="currentColor" />
                                                <path
                                                    d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                                    fill="currentColor" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                    </span>
                                </a>
                                <!--edit icon-->
                            </div>

                            <!--begin::Switch-->
                            <div class="col-lg-6">
                                <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid mt-2">
                                    <input class="form-check-input additional_message_checkbox" type="checkbox"
                                        value="enabled" {{ $enabled_message ? 'checked' : 'unchecked' }}
                                        name="bot_settings[additional_message][status]"
                                        style="width: 40px; height:24px;" />
                                </div>
                            </div>
                            <!--end::Switch-->
                        </div>

                        <!--additional message modal-->
                        <div class="modal fade" id="additional_message" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">
                                            {{ __('app.additional_message') }}
                                        </h5>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" name="bot_settings[additional_message][text]"
                                            class="form-control messageInputModal" value='{{ $additional_message }}' />
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit"
                                            class="btn btn-primary additional_message_save_modal">حفظ</button>
                                        <button type="button" class="btn btn-secondary additional_message_cancel_modal"
                                            data-dismiss="modal"
                                            onclick="$('#additional_message').modal('hide');">إلغاء</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--notes modal-->
                        <div class="modal fade" id="notes" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">ادخل اسم الحقل</h5>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" name="bot_settings[notes]" class="form-control"
                                            value='{{ $notes }}' />
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">حفظ</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                            onclick="$('#notes').modal('hide');">إلغاء</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--question1 modal-->
                        <div class="modal fade" id="question1" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">ادخل السؤال</h5>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" name="bot_settings[question1]" class="form-control"
                                            value='{{ $q1Value }}' />
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">حفظ</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                            onclick="$('#question1').modal('hide');">إلغاء</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--question2 modal-->
                        <div class="modal fade" id="question2" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">ادخل السؤال</h5>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" name="bot_settings[question2]" class="form-control"
                                            value='{{ $q2Value }}' />
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">حفظ</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                            onclick="$('#question2').modal('hide');">إلغاء</button>
                                    </div>
                                </div>
                            </div>
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

@push('scripts')
    <script>
        // if user clears value of additional message then press save,
        // the checkbox button should be turned off
        $(".additional_message_save_modal").click(function() {
            if (!$('.messageInputModal').val())
                $('.additional_message_checkbox').prop('checked', false);
        });

        $(".additional_message_cancel_modal").click(function() {
            // get old value in case press cancel after enter new value
            $('.messageInputModal').val('{{ $additional_message }}');

            // if user checked the button, wrote the message then cleared it
            //  then pressed cancel, the checkbox button should be turned off
            if (!$('.messageInputModal').val())
                $('.additional_message_checkbox').prop('checked', false);
        });

        $('.formSubmit').click(function() {
            if (!$('.messageInputModal').val()) {
                $('.additional_message_checkbox').prop('checked', false);
            }
        });
    </script>
@endpush
