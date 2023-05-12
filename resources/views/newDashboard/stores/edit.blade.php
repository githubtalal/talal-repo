 @extends('newDashboard.layouts.master')
 @section('content')
     @php
         $message = $payment_method_message ? $payment_method_message : __('app.payment_method_message');
     @endphp

     <x-add_edit_item :name="'تعديل إعدادات المتجر'">
         <!--begin::Form-->
         <form class="form d-flex flex-column flex-lg-row"
             action="{{ route('stores.update_subscribtion_setting', [$store]) }}" method="post" enctype="multipart/form-data">
             @csrf
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
                                         <h2>معلومات المتجر</h2>
                                     </div>
                                 </div>
                                 <!--end::Card header-->
                                 <!--begin::Card body-->
                                 <div class="card-body pt-0">

                                     <!--begin::Manager Name-->
                                     <div class="mb-10 fv-row">
                                         <label class="form-label col-lg-4">اسم مدير المتجر :</label>
                                         <label class="form-label">{{ $store->managerName }}</label>
                                     </div>
                                     <!--end::Manager Name-->

                                     <!--begin::Phone Number-->
                                     <div class="mb-10 fv-row">
                                         <label class="form-label col-lg-4">رقم الهاتف :</label>
                                         <label class="form-label">{{ $store->phoneNumber }}</label>
                                     </div>
                                     <!--end::Phone Number-->

                                     <!--begin::Payment Method-->
                                     <div class="mb-10 fv-row">
                                         <label class="form-label col-lg-4">طريقة الدفع :</label>
                                         <label class="form-label">{{ $store->paymentMethod }}</label>
                                     </div>
                                     <!--end::Payment Method-->

                                     <!--begin::created_at-->
                                     <div class="mb-10 fv-row">
                                         <label class="form-label col-lg-4">تاريخ إنشاء المتجر :</label>
                                         <label class="form-label">{{ $store->created_at->format('Y-m-d') }}</label>
                                     </div>
                                     <!--end::created_at-->

                                     <!--begin::email-->
                                     <div class="mb-10 fv-row">
                                         <label class="form-label col-lg-4">البريد الالكتروني :</label>
                                         <label class="form-label">{{ $userInfo->email }}</label>
                                     </div>
                                     <!--end::email-->

                                     <!--begin::password-->
                                     <div class="mb-10 fv-row col-lg-6">
                                         <label class="form-label" for="password">كلمة المرور</label>
                                         <input type="password" id="password" class="form-control" name="password" />
                                     </div>
                                     <!--begin::password-->

                                     <!--begin::Store Name-->
                                     <div class="mb-10 fv-row col-lg-6">
                                         <label class="form-label" for="storeName">اسم المتجر</label>
                                         <input type="text" id="storeName" class="form-control" name="storeName"
                                             value="{{ $store->name }}" />
                                     </div>
                                     <!--end::Store Name-->

                                     <!--begin::Expires_at-->
                                     <div class="mb-10 fv-row col-lg-6">
                                         <label class="col-lg-4 form-label">
                                             تاريخ الانتهاء
                                         </label>
                                         <input type="date" class="form-control dateInput" name="expires_at"
                                             value="{{ $store->expires_at }}" min="1990-01-01" max=""
                                             id="date" />
                                     </div>
                                     <!--end::Expires_at-->

                                     <!--begin::Status-->
                                     <div class="col-lg-12 d-flex align-items-center mb-10">
                                         <!--begin::Label-->
                                         <label class="col-lg-4 form-label">
                                             حالة المتجر
                                         </label>
                                         <!--end::Label-->
                                         <!--begin::Switch-->
                                         <div
                                             class="form-check form-switch form-switch-sm form-check-custom form-check-solid m-1">
                                             <input class="form-check-input" type="checkbox" value="1" name="status"
                                                 style="width: 40px; height:24px;"
                                                 {{ $store->is_active ? 'checked' : 'unchecked' }} />
                                         </div>
                                         <!--end::Switch-->

                                     </div>
                                     <!--end::Status-->

                                     <!--begin::Currency Status-->
                                     <div class="col-lg-12 d-flex align-items-center mb-10">
                                         <!--begin::Label-->
                                         <label class="col-lg-4 form-label">
                                             تفعيل العملة لدى المتجر
                                         </label>
                                         <!--end::Label-->
                                         <!--begin::Switch-->
                                         <div
                                             class="form-check form-switch form-switch-sm form-check-custom form-check-solid m-1">
                                             <input class="form-check-input" type="checkbox" value="1"
                                                 name="currency_status" style="width: 40px; height:24px;"
                                                 {{ $store->currency_status == 1 ? 'checked' : 'unchecked' }} />
                                         </div>
                                         <!--end::Switch-->

                                     </div>
                                     <!--end::Currency Status-->

                                     <!--begin::Services-->
                                     <div class="mb-10 fv-row">
                                         <label class="form-label mb-6">
                                             الخدمات المتاحة
                                         </label>
                                         @foreach ($main_features as $feature)
                                         <?php
                                             $expiryDate = $store->getFeatureExpiryDate($feature->name);
                                             $expiryDate = $expiryDate ? $expiryDate->format('Y-m-d') : null;
                                             $checked = in_array($feature->name, $services);
                                         ?>
                                             <div class="mb-3 d-flex align-items-center">
                                                 <input class="form-check-input" type="checkbox"
                                                     value="{{ $feature->name }}" name="features[]" id="feature"
                                                     {{ $checked ? 'checked' : 'unchecked' }} />
                                                 <label class="form-label mx-3" for="feature">
                                                     {{ __('app.features.' . $feature->name) }}
                                                 </label>
                                             </div>

                                             <div class="mb-10 fv-row col-lg-6 feature-expiry-date {{ $checked ? '' : 'd-none' }}"
                                                  id="custom-feature-{{ $feature->name }}-date"
                                                  data-feature="{{ $feature->name }}"
                                             >
                                                 <label class="col-lg-4 form-label">
                                                     تاريخ الانتهاء
                                                 </label>
                                                 <input type="date"
                                                        class="form-control dateInput"
                                                        name="feature_expires_at[{{ $feature->name }}]"
                                                        value="{{ $expiryDate  }}"
                                                        placeholder="تاريخ الانتهاء"
                                                        id="custom-feature-{{ $feature->name }}-date-input" />
                                             </div>
                                         @endforeach
                                     </div>
                                     <!--end::Services-->

                                     <!--begin::choose payment method-->
                                     <div class="mb-10 fv-row">
                                         <!--begin::Label-->
                                         <label class="form-label payment_method_label">
                                             {{ $message }}
                                         </label>
                                         <!--end::Label-->

                                         <!--edit icon-->
                                         <a href="#" class="btn btn-icon btn-active-light-primary w-30px h-30px"
                                             data-bs-toggle="modal" data-bs-target="#paymentMethod"
                                             style="margin-bottom:0.5rem;">
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
                                     <!--end::choose payment method-->

                                     <!--begin::modal-->
                                     <div class="modal fade" id="paymentMethod" tabindex="-1" role="dialog"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                         <div class="modal-dialog" role="document">
                                             <div class="modal-content">
                                                 <div class="modal-header">
                                                     <h5 class="modal-title" id="exampleModalLabel">ادخل اسم الحقل</h5>
                                                 </div>
                                                 <div class="modal-body">
                                                     <input type="text" name="payment_method_message"
                                                         class="form-control inputModal" value='{{ $message }}' />
                                                 </div>
                                                 <div class="modal-footer">
                                                     <button type="button"
                                                         class="btn btn-primary saveModalButton">حفظ</button>
                                                     <button type="button" class="btn btn-secondary"
                                                         data-dismiss="modal"
                                                         onclick="$('#paymentMethod').modal('hide');">إلغاء</button>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>

                                     <!--begin::Reset Store modal-->
                                     <div class="modal fade" id="reset" tabindex="-1" role="dialog"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                         <div class="modal-dialog" role="document">
                                             <div class="modal-content">
                                                 <div class="modal-header">
                                                     <h5 class="modal-title" id="exampleModalLabel">تنبيه !</h5>
                                                 </div>
                                                 <div class="modal-body">
                                                     <label class="form-label mb-6">
                                                         سيؤدي هذا إلى حذف الفئات والمنتجات الخاصة بالمتجر
                                                     </label>
                                                 </div>
                                                 <div class="modal-footer">
                                                     <a href="{{ route('stores.reset', [$store]) }}"
                                                         class="btn btn-primary">موافق</a>
                                                     <button type="button" class="btn btn-secondary"
                                                         data-dismiss="modal"
                                                         onclick="$('#reset').modal('hide');">إلغاء</button>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>

                                     <!--Buttons-->
                                     <div class="d-flex justify-content-end">
                                         <!--Save button-->
                                         <x-inputs.save_button></x-inputs.save_button>
                                         <!--cancel button-->
                                         <a href="{{ route('stores.index') }}" class="btn btn-light ms-3">إلغاء</a>
                                         <a class="btn btn-light ms-3" data-bs-toggle="modal"
                                             data-bs-target="#reset">تهيئة المتجر</a>
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
     </x-add_edit_item>
 @endsection

 @push('scripts')
     <script>
         $(".saveModalButton").click(function() {
             default_message = "{{ __('app.payment_method_message') }}";
             value = $('.inputModal').val();

             if (!value) {
                 $('.inputModal').val(default_message);
                 $('.payment_method_label').text(default_message);
             } else
                 $('.payment_method_label').text(value);

             $('#paymentMethod').modal('hide');
         });

         @foreach ($main_features as $feature)
                $('#custom-feature-{{ $feature->name }}-date-input').flatpickr({
                        altInput: true
                })
         @endforeach

         $('input[name="features[]"]').change(function () {
             let feature = $(this).val();
             console.log('here');
            if ($(this).is(':checked')) {
                console.log($(this))
                $(`#custom-feature-${feature}-date`)
                    .removeClass('d-none');
                $(this).prop('required', true)
            }
            else {
                $(`#custom-feature-${feature}-date`)
                    .addClass('d-none');
                $(this).prop('required', false);

            }
         })

     </script>
 @endpush
