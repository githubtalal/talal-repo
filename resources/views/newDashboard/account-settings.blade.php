@extends('newDashboard.layouts.master')
@section('content')
    <!--begin::Basic info-->
    <div class="content d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div class="toolbar">
            <!--begin::Page title-->
            <div class="container-fluid">
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                    {{ __('app.sidebar.account_settings') }}
                </h1>
            </div>
        </div>
        <!--begin::Container-->
        <div class="container-xxl">
            <div class="card mb-5 mb-xl-10">
                <div class="card-body border-top p-9">
                    <form id="validatedForm" action="{{ route('accountSettings.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="mb-10">
                            <label class="form-label fw-bolder text-dark">
                                المعلومات الشخصية:
                            </label>

                            <!--Store Name-->
                            <div class="row d-flex align-items-center mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-3 col-form-label fw-bold fs-4" id="">
                                    {{ __('app.general_settings.store_name') }}
                                </label>
                                <!--end::Label-->

                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="text" name="store_name"
                                        class="form-control form-control-lg @error('store_name') is-invalid @enderror"
                                        placeholder="" value="{{ $user->store->name }}" />
                                    @error('store_name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Col-->
                            </div>

                            <!--Store Type-->
                            <div class="row d-flex align-items-center mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-3 col-form-label fw-bold fs-4" id="">
                                    {{ __('app.general_settings.store_type') }}
                                </label>
                                <!--end::Label-->

                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="text" name="store_type"
                                        class="form-control form-control-lg @error('store_type') is-invalid @enderror"
                                        placeholder="" value="{{ $user->store->type }}" />
                                    @error('store_type')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Col-->
                            </div>

                            <!--Store Manager-->
                            <div class="row d-flex align-items-center mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-3 col-form-label fw-bold fs-4" id="">
                                    {{ __('app.general_settings.store_manager') }}
                                </label>
                                <!--end::Label-->

                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="text" name="store_manager"
                                        class="form-control form-control-lg @error('store_manager') is-invalid @enderror"
                                        placeholder="" value="{{ $user->name }}" />
                                    @error('store_manager')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Col-->
                            </div>

                            <!--Phone Number-->
                            <div class="row d-flex align-items-center mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-3 col-form-label fw-bold fs-4" id="">
                                    {{ __('app.general_settings.phone_number') }}
                                </label>
                                <!--end::Label-->

                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="text" name="phone_number"
                                        class="form-control form-control-lg @error('phone_number') is-invalid @enderror"
                                        placeholder="" value="{{ $user->phone_number }}" />
                                    @error('phone_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Col-->
                            </div>
                        </div>

                        <div class="mb-10">
                            <label class="form-label fw-bolder text-dark">
                                تغيير معلومات تسجيل الدخول:
                            </label>

                            <!--Email-->
                            <div class="row d-flex align-items-center mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-3 col-form-label fw-bold fs-4" id="">
                                    {{ __('app.general_settings.email') }}
                                </label>
                                <!--end::Label-->

                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="text" name="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        placeholder="" value="{{ $user->email }}" />
                                    @error('email')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Col-->
                            </div>

                            <!--Password-->
                            <div class="row d-flex align-items-center mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-3 col-form-label fw-bold fs-4" id="">
                                    {{ __('app.general_settings.new_password') }}
                                </label>
                                <!--end::Label-->

                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="password" name="password" autocomplete="new-password" id="first"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        placeholder="" />
                                    @error('password')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Col-->
                            </div>

                            <!--Re-Password-->
                            <div class="row d-flex align-items-center mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-3 col-form-label fw-bold fs-4">
                                    {{ __('app.general_settings.re_new_password') }}
                                </label>
                                <!--end::Label-->

                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="password" name="re_password" id="second"
                                        class="form-control form-control-lg @error('re_password') is-invalid @enderror"
                                        placeholder="" value="" />
                                    @error('re_password')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Col-->
                            </div>
                        </div>

                        <div class="mb-10">
                            <label class="form-label fw-bolder text-dark">
                                إعدادات إضافية:
                            </label>

                            <!--Powered By Ecart Button-->
                            <div class="row d-flex align-items-center mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-4" id="">
                                    Powered By Ecart Button
                                </label>
                                <!--end::Label-->
                                <div class="col-lg-6">
                                    <!--begin::Switch-->
                                    <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="enabled" name="power_button"
                                            {{ $ifEnabled ? 'checked' : '' }} style="width: 40px; height:24px;" />
                                    </div>
                                    <!--end::Switch-->
                                </div>
                            </div>
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            jQuery('#validatedForm').validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        minlength: 6,
                        required: {
                            depends: function(element) {
                                return $("#second").is(":filled");
                            }
                        }
                    },
                    re_password: {
                        minlength: 6,
                        equalTo: "#first",
                        required: {
                            depends: function(element) {
                                return $("#first").is(":filled");
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
