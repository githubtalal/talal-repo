    <!--begin::Notifications-->
    <div class="d-flex align-items-center ms-1 ms-lg-3">
        <!--begin::Menu- wrapper-->
        <div class="btn btn-icon btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px  menu-dropdown" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
            <div class="btn btn-icon btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px position-relative" id="kt_drawer_chat_toggle">
        
                <i class="las fs-1 la-bell"></i>
                
                <span id="bullet" class=" @if(!($unread_notifications_count > 0)) d-none @endif bullet bullet-dot bg-success h-6px w-6px position-absolute translate-middle top-0 start-50 animation-blink"></span>
                
            </div>
            <!--end::Menu wrapper-->
        </div>
        
        <!--begin::Menu-->
        <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true">
            <!--begin::Heading-->
            <div class="d-flex flex-column bgi-no-repeat rounded-top" style="background-image:url({{url('assets/media/misc/pattern-1.jpg')}})">
                <!--begin::Title-->
                <h3 class="text-white fw-bold px-9 mt-10 mb-6">{{ __('app.notification.notifications') }}
                <!--end::Title-->
               
            </div>
            <!--end::Heading-->
            <!--begin::Tab content-->
            <div class="tab-content">
                <!--begin::Tab panel-->
                <div class="tab-pane fade active show" id="kt_topbar_notifications_1" role="tabpanel">
                    <!--begin::Items-->
                    <div id="notification_list" class="scroll-y mh-325px my-5 px-8">
                     
                        @foreach ($notifications as $notification)
                        <!--begin::Item-->
                        <a href="{{ route('notifications.show_notification',$notification->id) }}">
                            <div class="d-flex flex-stack py-4">
                                <!--begin::Section-->
                                      
                                <div class="d-flex align-items-center">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-35px me-4">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="las la-bell fs-1 text-primary"></i>
                                        </span>
                                    </div>
                                    <!--end::Symbol-->
                                    <!--begin::Title-->
                                    <div class="mb-0 me-2">
                                        <div class="text-gray-600 fs-5">{{ json_decode($notification->data)->text }}</div>
                                    </div>
                                    <!--end::Title-->
                                </div>
                                <!--end::Section-->
                                <!--begin::Label-->
                                <span class="badge badge-light fs-8">{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans()}}   </span>
                                <!--end::Label-->
                            </div>
                            </a>
                            <!--end::Item-->
                        @endforeach                        
                     

                    </div>
                    <!--end::Items-->
                    <!--begin::View more-->
                    <div class="py-3 text-center border-top">
                        <a href="{{ route('notifications.view_all_notifications') }}" class="btn btn-color-gray-600 btn-active-color-primary">
                            {{ __('app.notification.view_all') }}
                       </a>
                    </div>
                    <!--end::View more-->
                </div>
                <!--end::Tab panel-->
              
            </div>
            <!--end::Tab content-->
        </div>
        <!--end::Menu-->

        <!--end::Menu wrapper-->
    </div>
    <!--end::Notifications-->