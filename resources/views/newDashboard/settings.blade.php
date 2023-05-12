@extends('newDashboard.layouts.master')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div class="toolbar">
            <!--begin::Page title-->
            <div class="container-fluid">
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                    {{ __('app.general_settings.general_settings') }}
                </h1>
            </div>
        </div>
        <!--begin::Basic info-->
        <div class="card mb-5 mb-xl-10">

            <!--begin::Content-->
            <div id="kt_account_settings_profile_details" class="collapse show">
                <!--begin::Form-->
                <form action="{{ route('settings.update') }}" method="POST" id="kt_account_profile_details_form"
                      class="form" enctype="multipart/form-data">

                    @csrf

                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label
                                class="col-lg-4 col-form-label required fw-bold fs-4">{{ __('app.general_settings.store_name') }}</label>
                            <!--end::Label-->


                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <input type="text" name="name"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('name') is-invalid @enderror"
                                       placeholder="{{ __('app.general_settings.store_name') }}" required
                                       value="{{ $store->name }}"/>
                                @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!--end::Col-->

                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label
                                class="col-lg-4 col-form-label fw-bold fs-4">{{ __('app.general_settings.store_subname') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <input type="text" name="subname"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('subname') is-invalid @enderror"
                                       placeholder="{{ __('app.general_settings.store_subname') }}"
                                       value="{{ $store->subname }}"/>
                                @error('subname')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!--end::Col-->

                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">

                            <!--begin::Label-->
                            <label
                                class="col-lg-4 col-form-label fw-bold fs-4">{{ __('app.general_settings.store_slug') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <div class="input-group mb-5">
                                    <span onclick="copyStoreUrl(this)" class="cursor-pointer input-group-text">نسخ</span>
                                    <input type="text"
                                           aria-describedby="store_slug"
                                           name="slug"
                                           style="text-align: left;"
                                           class="form-control form-control-lg  @error('slug') is-invalid @enderror"
                                           placeholder="{{ __('app.general_settings.store_slug') }}"
                                           value="{{ $store->slug }}"/>
                                    @error('slug')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror

                                    <span class="input-group-text" id="store_slug">
                                    /{{ url('/') }}
                                </span>
                                </div>
                                <div class="alert alert-success d-none" id="store-slug-alert"></div>
                            </div>
                            <!--end::Col-->

                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-6">

                            <!--begin::Label-->
                            <label
                                class="col-lg-4 col-form-label fw-bold fs-4">{{ __('app.general_settings.button_text') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <input type="text" name="btn_text"
                                       class="form-control form-control-lg form-control-solid @error('btn_text') is-invalid @enderror"
                                       placeholder="{{ __('app.general_settings.button_text') }}"
                                       value="{{ $store->button_text }}"/>
                                @error('btn_text')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <!--end::Col-->

                        </div>
                        <!--end::Input group-->


                        <div class="row mb-6 align-items-center">

                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-bold fs-4">شكل الزر</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <div class="d-flex ">
                                    <div class='me-6'>
                                        <input type="radio" name="btn_style" value="BtnIcon1.svg"
                                            {{ $store->button_style == 'BtnIcon1.svg' ? 'checked' : '' }} />
                                        <img style="height:100px;width:100px;margin-right:1em;"
                                             src="{{ asset('Baseet/images/BtnIcon1.svg') }}"/>
                                    </div>

                                    <div class='me-6'>
                                        <input type="radio" name="btn_style" value="BtnIcon2.svg"
                                            {{ $store->button_style == 'BtnIcon2.svg' ? 'checked' : '' }} />
                                        <img style="height:100px;width:100px;margin-right:1em;"
                                             src="{{ asset('Baseet/images/BtnIcon2.svg') }}"/>
                                    </div>
                                </div>
                            </div>
                            <!--end::Col-->
                        </div>

                        <!--begin::Input group-->
                        <div class="row mb-6">

                            <!--begin::Label-->
                            <label for="Image"
                                   class="col-lg-4 col-form-label fw-bold fs-4">{{ __('app.general_settings.store_logo') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <div class="mb-5">
                                    <input class="form-control" accept="image/*" type="file" name="logo" id="formFile"
                                           onchange="document.getElementById('frame').src = window.URL.createObjectURL(this.files[0])">
                                    <img id="frame"
                                         src="{{ $store->logo ? \Illuminate\Support\Facades\Storage::url($store->logo) : '' }}"
                                         class="img-fluid"/>
                                </div>
                            </div>

                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label
                                class="col-lg-4 col-form-label fw-bold fs-4">{{ __('app.general_settings.token') }}</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <input type="text" disabled value="{{ $store->token ?? '' }}"
                                       class="disabled login-input form-control" id="floatingInputGrid"
                                       placeholder="domain">
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label
                                class="col-lg-4 col-form-label fw-bold fs-4">{{ __('app.general_settings.copy_code') }}</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">

                                <code style="text-align: left;white-space: pre-line;" class="form-control"
                                      id="floatingInputGrid" placeholder="btn name">
                                    import('{{ asset('js/store.js') }}').then(() => {
                                    Sallaty.init({
                                    token: "{{ $store->token }}"
                                    });
                                    }).catch(err => console.error('Error'));
                                </code>

                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Card body-->

                    <!--Buttons-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <!--Save button-->
                        <x-inputs.save_button></x-inputs.save_button>
                        <!--cancel button-->
                        <a href="{{ route('settings') }}" class="btn btn-light ms-3">{{ __('app.button.cancel') }}</a>
                    </div>

                </form>
                <!--end::Form-->
            </div>
            <!--end::Content-->

        </div>
        <!--end::Basic info-->
    </div>
@endsection

@push('scripts')
    <script>
        let timer = null;


        $('input[name="slug"]').keyup(function () {
            clearTimeout(timer);
            let submit_button = $('button[type="submit"]');
            let val = $(this).val();
            submit_button.attr('disabled', true);

            timer = setTimeout(function () {
                let alert = $('#store-slug-alert');
                alert.removeClass('d-none')
                alert.html('جاري التحقق ...')
                $.get(`{{ route('store.check_slug') }}`, {
                    'slug': val,
                }, function (response) {

                    alert.removeClass(['alert-danger', 'alert-success']);
                    alert.html(response.message);
                    alert.addClass(response.valid ? 'alert-success' : 'alert-danger');

                    submit_button.attr('disabled', !response.valid);
                });
            }, 300)
        });

        function copyStoreUrl(e) {
            let val = $('input[name="slug"]').val();
            let url = `{{ url('/:PLACEHOLDER') }}`.replace(':PLACEHOLDER', val);
            const type = 'text/plain';
            const blob = new Blob([url], { type });
            const data = [new ClipboardItem({ [type]: blob })];

            navigator.clipboard.write(data);

            $(e).text('تم النسخ !');
            setTimeout(function() {
                $(e).text('نسخ');
            }, 2000);

        }
    </script>
@endpush
