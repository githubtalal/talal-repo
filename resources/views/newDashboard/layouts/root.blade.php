<!--begin::Root-->
<div class="d-flex flex-column flex-root">

    <!--begin::Page-->
    <div class="page d-flex flex-row flex-column-fluid">

        @if (auth()->user()->hasRole('super-admin'))
            @include('newDashboard.layouts.super-admin-sidebar')
        @else
            @include('newDashboard.layouts.sidebar')
        @endif

        <!--begin::Wrapper-->
        <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">

            <!--begin::Header-->
            <div id="kt_header" class="header align-items-stretch">

                <div class="progress">
                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                        style="max-width:100%;"></div>
                </div>

                <!--begin::Container-->
                <div class="container-fluid d-flex align-items-stretch justify-content-between">
                    <!--begin::Aside mobile toggle-->
                    <div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show aside menu">
                        <div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px"
                            id="kt_aside_mobile_toggle">
                            <!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
                            <span class="svg-icon svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z"
                                        fill="currentColor" />
                                    <path opacity="0.3"
                                        d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </div>
                    </div>
                    <!--end::Aside mobile toggle-->
                    <!--begin::Mobile logo-->
                    <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
                        <a href="{{ route('dashboard') }}" class="d-lg-none">
                            <img alt="Logo" src="{{ asset('Baseet/images/EcartLogo.svg') }}" class="h-30px" />
                        </a>
                    </div>
                    <!--end::Mobile logo-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-stretch justify-content-between">

                        <!--begin:: Tools bar-->
                        <div class="d-flex align-items-stretch flex-shrink-0">

                        <x-notification-tool-bar></x-notification-tool-bar>
                        </div>
                        <!--end:: Tools bar-->


                        <!--begin::Navbar-->
                        <div class="d-flex align-items-stretch" id="kt_header_nav">
                            <div class="d-flex align-items-center">
                                <div class="nav-item user">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbardrop"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <strong style="font-size:large">{{ auth()->user()->name }}</strong>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbardrop">
                                        <a class="dropdown-item" href="{{ route('dashboard') }}">لوحة التحكم</a>
                                        <a class="dropdown-item" href="{{ route('accountSettings.view') }}">{{ __('app.sidebar.account_settings') }}</a>
                                        <a class="dropdown-item" href="{{ route('logout') }}">{{ __('app.logout') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Navbar-->


                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Container-->

            </div>
            <!--end::Header-->

            @php
                $hideAlert = false;
                $info = get_subscription_info();
                if (!$info) {
                    $hideAlert = true;
                } else {
                    $isSimilar = array_unique($info);

                    $alertMessage = 'عزيزي ' . auth()->user()->name . '،';

                    if (count($info) > 1 && count($isSimilar) === 1) {
                        $alertMessage .= ' باقي ' . array_values($info)[0] . ' أيام على انتهاء اشتراكك في خدمات إي كارت، يرجى تجديد اشتراكك الآن!';
                    } else {
                        if (array_key_exists('telegram_bot', $info)) {
                            $alertMessage .= ' باقي ' . $info['telegram_bot'] . ' أيام على انتهاء اشتراك بوت التلغرام، ';
                        }
                        if (array_key_exists('messenger_bot', $info)) {
                            $alertMessage .= ' باقي ' . $info['messenger_bot'] . ' أيام على انتهاء اشتراك بوت المسنجر، ';
                        }
                        if (array_key_exists('website_widget', $info)) {
                            $alertMessage .= ' باقي ' . $info['website_widget'] . ' أيام على انتهاء اشتراك عربة التسوق الالكترونية، ';
                        }
                        $alertMessage .= '<a href="' .  route('resubscription.create') .'"> يرجى تجديد اشتراكك الآن!</a>';
                    }
                }

            @endphp
            <!--begin::Alert-->
            <div class="alert alert-danger d-flex p-5 alertMessage mb-0 d-none">
                <!--begin::Icon-->
                <i class="fa fa-exclamation-circle mt-2 me-3" style="color:#b16b7c"></i>
                <!--end::Icon-->

                <!--begin::Wrapper-->
                <div class="d-flex flex-column">
                    <!--begin::Title-->
                    <h4 class="mb-1" style="color:#912741">تجديد الاشتراك</h4>
                    <!--end::Title-->
                    <!--begin::Content-->
                    <span>
                        {!!   $alertMessage ?? false !!}
                    </span>
                    <!--end::Content-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Alert-->

            @yield('content')

            <!--begin::Footer-->
            <div class="footer py-4 d-flex flex-lg-column app-footer" id="kt_footer" style="position: fixed;bottom: 0;">
                <!--begin::Container-->
                <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <!--begin::Copyright-->
                    <div class="text-dark order-2 order-md-1">
                        <span class="text-muted fw-bold me-1">{{ date('Y') }}</span>
                        <a href="{{ url('/') }}" target="_blank" class="text-gray-800 text-hover-primary">eCart</a>
                    </div>
                    <!--end::Copyright-->
                    <!--begin::Menu-->
                    {{-- <ul class="menu menu-gray-600 menu-hover-primary fw-bold order-1"> --}}
                    {{-- <li class="menu-item"> --}}
                    {{-- <a href="https://keenthemes.com" target="_blank" class="menu-link px-2">About</a> --}}
                    {{-- </li> --}}
                    {{-- <li class="menu-item"> --}}
                    {{-- <a href="https://devs.keenthemes.com" target="_blank" class="menu-link px-2">Support</a> --}}
                    {{-- </li> --}}
                    {{-- <li class="menu-item"> --}}
                    {{-- <a href="https://1.envato.market/EA4JP" target="_blank" --}}
                    {{-- class="menu-link px-2">Purchase</a> --}}
                    {{-- </li> --}}
                    {{-- </ul> --}}
                    <!--end::Menu-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::Footer-->

        </div>
        <!--end::Wrapper-->

    </div>
    <!--end::Page-->

</div>
<!--end::Root-->

@push('script')
    <script>
        $(document).ready(function() {
            if (!'{{ $hideAlert }}')
                $('.alertMessage').removeClass('d-none');
        });
    </script>
@endpush
