@extends('newDashboard.layouts.master')

@section('content')
    <!--begin::Basic info-->
    <div class="card mb-5 mb-xl-10">

        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
            data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0">{{ __('app.telegram_bot.boadcast_messages') }}</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--end::Card header-->

        <!--begin::Content-->
        <div id="" class="collapse show">

            <!--begin::Card body-->
            <div class="card-body border-top p-9">

           
                 @if ($bot->token ?? null)
                    <div class="row col-lg-6 mb-6">
                    
                        <form action="{{ route('bot.telegram.broadcast') }}" method="POST" enctype="multipart/form-data">
                            @csrf


                            <!-- begin::previous_channels div-->
                            <div class="" id="previous_channels">
                                <!-- begin::select channel-->
                                <div class="mb-10 fv-row">
                                   <!--begin::Label-->
                                    <label class="form-label">{{ __('app.telegram_bot.channel') }}</label>
                                    <!--end::Label-->

                                    <!--begin::Select-->
                                    <select onchange="add_new_channel()" class="form-select mb-2 @error('previous_channel_id') is-invalid @enderror" data-control="select2" data-hide-search="true"
                                        data-placeholder="Select an option" name="channel_id" id="previous_channel_select">
                                       
                                        <option>{{ __('app.telegram_bot.select') }}</option>
                                        @foreach ($previous_channels as $key => $channel)
                                            <option value="{{ $channel->channel_id }}" >
                                                {{ $channel->channel_id }}
                                            </option>
                                        @endforeach
                                        <option value="add_new_channel"> {{ __('app.telegram_bot.add_channel') }}</option>
                                    </select>

                                    @error('previous_channel_id')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    <!--end::Select-->
                                </div>
                                <!-- end::select channel-->
                            </div>
                            <!-- end::previous_channels div-->


                            <!--begin::products select-->
                            <div class="mb-10 fv-row mt-10">
                                <x-inputs.select_input :label="trans_choice('app.products.products', 2)" :name="'product_id'" :items="$products"
                                    :selected="''">
                                </x-inputs.select_input>
                            </div>
                            <!--end::products select-->

                            <!-- begin::special message checkbox-->
                            <div class="mb-10 fv-row checkboxDiv">
                                <input class="form-check-input @error('use_custom_message') is-invalid @enderror"
                                    type="checkbox" value="on" name="use_custom_message" id="message_check">
                                <label class="form-label checkbox_label" for="message_check">
                                    {{ __('app.telegram_bot.create_special_message') }}
                                </label>
                                @error('use_custom_message')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- end::special message checkbox-->

                            <!-- begin::special message div-->
                            <div class="d-none" id="special_message">
                                <!-- begin::text message-->
                                <div class="mb-10 fv-row">
                                    <x-inputs.textarea_input :label="__('app.telegram_bot.text_message')" :name="'message'" :value="''">
                                    </x-inputs.textarea_input>
                                </div>
                                <!-- end::text message-->

                                <!--begin::image-->
                                <div class="mb-10 fv-row">
                                    <label for="Image"
                                        class="col-lg-4 col-form-label fw-bold fs-4">{{ __('app.image.image') }}</label>
                                    <input class="form-control" type="file" name="image">
                                </div>
                                <!--end::image-->
                            </div>
                            <!-- end::special message div-->


                            <!-- start:add new channel modal -->             
                            <div class="modal fade" id="add_channel_Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">{{ __('app.telegram_bot.channel') }}</h5>
                                    </div>

                                        <div class="modal-body">
                                            <input class="form-control" placeholder="channel username" type="text"
                                                id="channel_id_feild"/>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" onclick="save_channel_name()" class="btn btn-primary">تأكيد</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                                onclick="$('#add_channel_Modal').modal('hide');">إلغاء</button>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <!-- end:add new channel modal -->
                        
                            <!--Save button-->
                            <div class="card-footer d-flex justify-content-end py-6 px-9">
                                <button type='submit' id="submit" class='btn btn-primary'>
                                    <span class='indicator-label'>
                                        إرسال
                                    </span>
                                </button>

                            </div>
                        </form>
                    </div>

                    @else
                    <h5 class="fw-bolder text-warning m-0">{{ __('app.telegram_bot.do_not_has_bot') }}</h5>
                  
                @endif

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

    <script>
        var add_channel_Modal = new bootstrap.Modal(document.getElementById('add_channel_Modal'), {
            keyboard: false
            });

        function add_new_channel(){

            var selectBox = document.getElementById("previous_channel_select");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
       

            if(selectedValue == 'add_new_channel'){

                add_channel_Modal.show();
            }
         
        }


        function save_channel_name(){

            var channel_id_feild = document.getElementById('channel_id_feild').value;
            
            if(channel_id_feild != ''){

                $('#previous_channel_select').append('<option value='+ channel_id_feild +' selected="selected">'+channel_id_feild+'</option>');

                add_channel_Modal.hide();
            }else{

                add_channel_Modal.hide();

                $("#previous_channel_select option:first").prop("selected", "selected");
           
            }
       
        }
    </script>
@endpush
