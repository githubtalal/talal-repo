@extends('newDashboard.layouts.master')
@section('content')
    @php
        $mobileItems = [__('app.order.table.Date'), __('app.order.table.Status'), __('app.order.table.Total')];
        $items = [__('app.order.table.order_id'), __('app.order.table.Name'), __('app.order.table.Date'), __('app.order.table.Phone_number'), __('app.order.table.Status'), __('app.order.table.Total'), __('app.actions.actions')];
        
        $ifRequestHasData = false;
        
        if (request('governorate') || request('status') || request('start-date') || request('end-date') || request()->filled('total')) {
            $ifRequestHasData = true;
        }
        
        $order_status = request('status', 'all');
        
        $date = request('date');
        
        $currentRoute = \Illuminate\Support\Facades\Route::currentRouteName();
        
        if (str_contains($currentRoute, 'subscriptions')) {
            $viewRoute = 'orders.subscriptions.view';
            $editRoute = 'orders.subscriptions.edit';
        } else {
            $viewRoute = 'orders.view';
            $editRoute = 'orders.edit';
        }

        function is_standard($number)
        {
            if (  (strlen(substr($number, 2)) == 8 && substr($number, 0, 2) == '09') || (strlen(substr($number, 1)) == 8  && substr($number, 0, 1) == '9') )
                return true;
            else
                return false;
        }

    @endphp

    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div class="toolbar">
            <!--begin::Page title-->
            <div class="container-fluid">
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                    قائمة الطلبات
                </h1>
            </div>
            <!--end::Page title-->
            <div class='container-fluid d-flex justify-content-end'>
                <form id="searchForm" action="{{ route($currentRoute) }}" method="get" enctype="multipart/form-data">
                    <!--begin::filters-->
                    <div class="d-flex align-items-center gap-2 gap-lg-3 mx-4">
                        <!--begin::Filter menu-->
                        <div class="m-0">
                            <!--begin::Menu toggle-->
                            <a href="#" class="btn btn-sm btn-flex btn-light btn-active-primary fw-bolder"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                                <span class="svg-icon svg-icon-5 svg-icon-gray-500 me-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <path
                                            d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                            fill="currentColor" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->الفلترة
                            </a>
                            <!--end::Menu toggle-->
                            <!--begin::Menu 1-->
                            <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true"
                                id="kt_menu_6244760a9baba">
                                <!--begin::Header-->
                                <div class="px-7 py-5">
                                    <div class="fs-5 text-dark fw-bolder">خيارات الفلترة</div>
                                </div>
                                <!--end::Header-->
                                <!--begin::Menu separator-->
                                <div class="separator border-gray-200"></div>
                                <!--end::Menu separator-->
                                <!--begin::Form-->
                                <div class="px-7 py-5 overflow-auto">
                                    <!--begin::Input group-->
                                    {{--                                            <div class="mb-10"> --}}
                                    {{--                                                <!--begin::Label--> --}}
                                    {{--                                                <label class="form-label fw-bold">اختر المحافظة</label> --}}
                                    {{--                                                <!--end::Label--> --}}
                                    {{--                                                <!--begin::Input--> --}}
                                    {{--                                                <div> --}}
                                    {{--                                                    <select class="form-select" data-control="select2" --}}
                                    {{--                                                        data-hide-search="true" data-placeholder="اختر المحافظة" --}}
                                    {{--                                                        name="governorate" id=""> --}}
                                    {{--                                                        <option disabled selected>المحافظة</option> --}}
                                    {{--                                                        @foreach ($cities as $city) --}}
                                    {{--                                                            <option --}}
                                    {{--                                                                value="{{ $city }}"{{ request('governorate') == $city ? 'selected' : '' }}> --}}
                                    {{--                                                                {{ $city }}</option> --}}
                                    {{--                                                        @endforeach --}}
                                    {{--                                                    </select> --}}
                                    {{--                                                </div> --}}
                                    {{--                                                <!--end::Input--> --}}
                                    {{--                                            </div> --}}
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="mb-10">
                                        <!--begin::Label-->
                                        <label class="form-label fw-bold">حالة الطلب</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <div>
                                            <select class="form-select" data-control="select2" data-hide-search="true"
                                                data-placeholder="اختر حالة الطلب" name="status" id="">
                                                <option disabled selected>اختر حالة الطلب</option>
                                                <option value="all" {{ $order_status == 'all' ? 'selected' : '' }}>
                                                    جميع الحالات
                                                </option>
                                                @foreach ($statuses as $status)
                                                    <option value="{{ $status }}"
                                                        {{ $order_status == $status ? 'selected' : '' }}>
                                                        {{ __('app.order.statuses.' . $status) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="mb-10">
                                        <!--begin::Label-->
                                        <label class="form-label fw-bold">{{ __('app.order.table.Total') }}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <div>
                                            <div class="d-flex">
                                                <select class="form-select" data-control="select2" data-hide-search="true"
                                                    data-placeholder="Select an option" name="op" id="">
                                                    <option value=">" {{ request('op') == '>' ? 'selected' : '' }}>
                                                        ></option>
                                                    <option value="<"{{ request('op') == '<' ? 'selected' : '' }}>
                                                        < </option>
                                                    <option value="="{{ request('op') == '=' ? 'selected' : '' }}>
                                                        =</option>
                                                </select>
                                                <input type="number" class="form-control mx-2" name="total"
                                                    value="{{ request('total') }}">
                                            </div>
                                        </div>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->

                                    <input type="hidden" name="search" id="inside_search" />
                                    <input type="hidden" name="date" id="inside_date" />

                                    <!--begin::Actions-->
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-sm btn-primary me-2 apply"
                                            data-kt-menu-dismiss="true">تطبيق</button>
                                        <a href="{{ route($currentRoute) }}" class="btn btn-sm btn-light">حذف</a>
                                    </div>
                                    <!--end::Actions-->
                                </div>
                                <!--end::Form-->
                            </div>
                            <!--end::Menu 1-->
                        </div>
                        <!--end::Filter menu-->
                    </div>
                    <!--end::filters-->
                </form>

            </div>
        </div>
        <!--end::Toolbar-->

        <!--Tables-->
        <div class="app-content flex-column-fluid ">
            <div class="app-container container-xxl">
                <div class="card card-flush">
                    <!--begin::Card header-->

                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">

                        <!--begin::Card title-->
                        <div class="card-title">
                            <!--begin::Search-->
                            <div class="d-flex align-items-center position-relative my-1">
                                <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                            rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                        <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                            fill="currentColor" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                <input type="text" name="search" class="form-control w-225px ps-14"
                                    value="{{ request('search') }}" placeholder="بحث" id="outside_search" />
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--end::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                            <div class="input-group w-100 align-items-center">
                                <!--begin::Flatpickr-->
                                <input name="date" class="form-control rounded input mw-225px outside_date"
                                    placeholder="اختر تاريخ" id="kt_daterangepicker_1" />
                                <!--end::Flatpickr-->
                                <a class="btn btn-primary btn-sm mx-6 apply rounded-1">بحث</a>
                            </div>
                        </div>
                        <!--end::Card toolbar-->

                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                            <a href="{{ route('orders.create') }}" class="btn btn-primary">{{ __('app.order.create') }}</a>
                            <a href="{{ route('orders.export') }}" class="btn btn-primary">Export</a>
                        </div>
    

                    </div>

                    <!--end::Card header-->
                    <!--begin::Body-->
                    <div class="card-body py-3">
                        <!--begin::Table container-->
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="fw-bolder text-muted">
                                        @foreach ($items as $item)
                                            <th class="min-w-150px">{{ $item }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody>
                                    @foreach ($orders as $order)
                                        <?php
                                        $orderView = route($viewRoute, [$order->id]);
                                        ?>

                                        <tr class='clickable-row' data-href='{{ $orderView }}'>
                                            <div>
                                                <!--begin::order ID-->
                                                <td>
                                                    <span class="text-gray-800 fw-bolder">{{ $order->id }}</span>
                                                </td>
                                                <!--end::order ID=-->
                                                <!--begin::order date-->
                                                <td>
                                                    <span
                                                        class="text-gray-800 fw-bolder">{{ $order->first_name . ' ' . $order->last_name }}</span>
                                                </td>
                                                <!--end::order date-->
                                                <!--begin::customer Name-->
                                                <td>
                                                    <span
                                                        class="text-gray-800 fw-bolder">{{ $order->created_at->format('Y-m-d g:i a') }}</span>
                                                </td>
                                                <!--end::customer Name-->
                                                <!--begin::phone_number=-->
                                                <td class="pe-0">
                                                    @php 
                                                        $phoneNumber = translateNumber($order->phone_number);
                                                        $agreed = is_standard($phoneNumber);
                                                    @endphp
                                                    <span class="fw-bolder">{{ $order->phone_number }}</span>
                                                    @if ($agreed == true) 
                                                        <a href = "https://wa.me/+963{{$phoneNumber}}" target="_blank"><i class="lab fs-3 la-whatsapp" ></i></a>
                                                    @endif

                                                </td>
                                                <!--end::phone_number=-->
                                                <!--begin::governorate=-->
                                                <!--end::governorate=-->
                                                <!--begin::status=-->
                                                <td class="pe-0">
                                                    <div class="badge badge-light-info">
                                                        {{ __('app.order.statuses.' . $order->status) }}</div>
                                                </td>
                                                <!--end::status=-->

                                                <!--begin::total=-->
                                                <td class="pe-0">
                                                    <div class="text-gray-800 fw-bolder">
                                                        {{ price_format($order->total, __('app.currency_types.' . $order->currency)) }}
                                                    </div>
                                                </td>

                                                <!--end::total=-->
                                                <!--begin::Action=-->
                                                <td class="action">

                                                    <a href="#"
                                                        class="btn btn-sm btn-light btn-active-light-primary action"
                                                        data-kt-menu-trigger="click"
                                                        data-kt-menu-placement="bottom-end">{{ __('app.actions.actions') }}
                                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                                        <span class="svg-icon svg-icon-5 m-0">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <path
                                                                    d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                                    fill="currentColor" />
                                                            </svg>
                                                        </span>
                                                        <!--end::Svg Icon-->
                                                    </a>
                                                    <!--begin::Menu-->
                                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"
                                                        data-kt-menu="true">
                                                        <!--begin::Menu item-->
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="{{ route($editRoute, [$order]) }}"
                                                                class="menu-link px-3">{{ __('app.actions.update') }}</a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="{{ route($viewRoute, [$order]) }}"
                                                                class="menu-link px-3">{{ __('app.actions.view') }}</a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                    </div>
                                                    <!--end::Menu-->
                                                </td>
                                                <!--end::Action=-->
                                        </tr>

                                        <!--begin::Table row-->
                                        <!--end::Table row-->
                                    @endforeach
                                </tbody>
                                <!--end::Table body-->
                            </table>
                            <!--end::Table-->
                        </div>
                        <!--end::Table container-->
                        <div>
                            {!! $orders->links() !!}
                        </div>
                    </div>
                    <!--begin::Body-->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $(function() {
                $('.apply').on('click', function() {
                    $('#inside_search').val($('#outside_search').val());
                    $('#inside_date').val($('.outside_date').val());
                    $('#searchForm').submit();
                });
            });

            $(".clickable-row").click(function() {
                if (!$(event.target).hasClass('action')) {
                    window.location = $(this).data("href");
                }
            });

            // range date picker

            function cb(start, end) {
                $("#kt_daterangepicker_1").html(start.format("YYYY-MM-DD") + " - " + end.format("YYYY-MM-DD"));
            }

            $("#kt_daterangepicker_1").daterangepicker({
                ranges: {
                    "اليوم": [moment(), moment()],
                    "البارحة": [moment().subtract(1, "days"), moment().subtract(1, "days")],
                    "آخر سبعة أيام": [moment().subtract(6, "days"), moment()],
                    "آخر 30 يوم": [moment().subtract(29, "days"), moment()],
                    "الشهر الحالي": [moment().startOf("month"), moment()],
                    "الشهر الماضي": [moment().subtract(1, "month").startOf("month"), moment().subtract(1,
                        "month").endOf("month")],
                },
                locale: {
                    "customRangeLabel": "مجال محدد",
                    "applyLabel": "تطبيق",
                    "cancelLabel": "إلغاء",
                }
            }, cb);

            dateInRequest = '{{ $date }}';
            if (dateInRequest)
                $("#kt_daterangepicker_1").val(dateInRequest);
            else
                $("#kt_daterangepicker_1").val('');
        });
    </script>
@endpush
