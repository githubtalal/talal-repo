@extends('newDashboard.layouts.master')

@section('content')
    <!--begin::Basic info-->
    <div class="card mb-5 mb-xl-10">

        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
            data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0">{{ __('app.notification.send_notifications') }}</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--end::Card header-->

        <!--begin::Content-->
        <div id="" class="collapse show">

            <!--begin::Card body-->
            <div class="card-body border-top p-9">

                <div class="row col-lg-6 mb-2">

                    @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger">{{ $error }}</div> 
                    @endforeach
                    @endif

                    @if (Session::has('success'))
                        <div class="alert alert-success">{{ Session::get('success') }}</div> 
                    @endif
        
                </div>
                
               <div class="row col-lg-6 mb-6">
                    
                        <form action="{{ route('notifications.send_notification') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- begin::stores div-->
                            <div class="">
                                <!-- begin::select channel-->
                                <div class="mb-10 fv-row">
                                   <!--begin::Label-->
                                    <label class="form-label">{{ __('app.notification.store') }}</label>
                                    <!--end::Label-->

                                    <!--begin::Select-->
                                    <select class="form-select" name="store_id[]" multiple="multiple" id="stores_select" data-control="select2" >
                                 
                                    </select>

                                    <!--end::Select-->
                                </div>
                                <!-- end::select channel-->
                            </div>
                            <!-- end::stores div-->


                            <!-- begin::notification message div-->
                            <div class="">
                                <!-- begin::text message-->
                                <div class="mb-10 fv-row">
                                    
                                <!--begin::Label-->
                                <label class="form-label">{{ __('app.notification.url') }} ({{ __('app.notification.optional') }})</label>
                                <!--end::Label-->
                                <input class="form-control" type="text" name="url" placeholder="URL">    
                                </div>
                                <!-- end::text message-->
    
                            </div>
                            <!-- end::notification message div-->


                            <!-- begin::notification message div-->
                            <div class="">
                                <!-- begin::text message-->
                                <div class="mb-10 fv-row">
                                    <x-inputs.textarea_input :label="__('app.notification.text_message')" :name="'message'" :value="''">
                                    </x-inputs.textarea_input>
                                </div>
                                <!-- end::text message-->

                            </div>
                            <!-- end::notification message div-->

                        
                            <!--Save button-->
                            <div class="card-footer d-flex justify-content-end py-6 px-9">
                                <button type='submit' id="submit" class='btn btn-primary'>
                                    <span class='indicator-label'>
                                        {{ __('app.notification.send') }}
                                    </span>
                                </button>

                            </div>
                        </form>
                    </div>

                  

            </div>
            <!--end::Card body-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->
@endsection

@push('scripts')

<script>
$(document).ready(function() {
    $("#stores_select").select2({

        placeholder: "اختر ...",
        allowClear: true,
        ajax: {
            url: '{!! route("notifications.get_notifiable_stores") !!}',
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1
                }
            },
         
            cache: true
            
        }
    });
});
   
</script>
@endpush
