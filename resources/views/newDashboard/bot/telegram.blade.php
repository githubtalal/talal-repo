@extends('newDashboard.layouts.master')

@section('content')
    <!--begin::Basic info-->
    <div class="card mb-5 mb-xl-10">

        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
            data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0">{{ __('app.telegram_bot.settings') }}</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--end::Card header-->

        <!--begin::Content-->
        <div id="" class="collapse show">

            <!--begin::Card body-->
            <div class="card-body border-top p-9">

                <form action="{{ route('bot.telegram.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!--begin::token input-->
                    <div class="row col-lg-6 mb-6">
                        <x-inputs.text_input :label="__('app.telegram_bot.telegram_token')" :name="'token'" :value="$bot->token ?? ''">
                        </x-inputs.text_input>
                    </div>
                    <!--end::token input-->

                    <!--Save button-->
                    <div class="col-lg-6 card-footer d-flex justify-content-end py-6 px-9">
                        <x-inputs.save_button></x-inputs.save_button>
                    </div>
                </form>



            </div>
            <!--end::Card body-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->
@endsection

@push('scripts')
    <script>
        $('#message_check').change(function() {
            if ($(this).is(':checked')) {
                $('#special_message').removeClass('d-none');
            } else {
                $('#special_message').addClass('d-none');
            }
        })
    </script>
@endpush
