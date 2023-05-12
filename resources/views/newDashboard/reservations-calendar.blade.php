@extends('newDashboard.layouts.master')
@section('content')
    @php
        $monthActive = 'active';
        $weekActive = '';
        
        if (request('calendar_type')) {
            if (request('calendar_type') == 'week') {
                $monthActive = '';
                $weekActive = 'active';
            }
        }
        
        $status = request('status') ?? 'all';

    @endphp

    <div class="content d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div class="toolbar">
            <!--begin::Page title-->
            <div class="container-fluid">
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                    قائمة الحجوزات
                </h1>
            </div>
        </div>
        <div class="container-xxl">
            <div class="card card-flush">
                <div class="card-header border-0 pt-5 myContent d-none">

                    <div class="flex-column">
                        <!--begin::filters-->
                        <form action="{{ route('reservations.index') }}" method="get">
                            @csrf
                            <div class="d-flex align-items-center gap-2 gap-lg-3 mb-6">
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
                                            <div class="mb-10">
                                                <label class="form-label fw-bold">حالة الحجوزات</label>
                                                <select class="form-select" data-control="select2" data-hide-search="true"
                                                    name="status">
                                                    <option value="" selected disabled>اختر حالة الحجوزات</option>
                                                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>
                                                        جميع الحالات</option>
                                                    @foreach (\App\Models\Order::getStatuses() as $key => $value)
                                                        <option value="{{ $key }}"
                                                            {{ $status == $key ? 'selected' : '' }}>
                                                            {{ $value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type='hidden' name='calendar_type' />
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Actions-->
                                            <div class="d-flex justify-content-end">
                                                <button type="submit" class="btn btn-sm btn-primary me-2"
                                                    data-kt-menu-dismiss="true">بحث</button>
                                                <a href="{{ route('reservations.index') }}"
                                                    class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-kt-menu-dismiss="true">حذف</a>
                                            </div>
                                            <!--end::Actions-->
                                        </div>
                                        <!--end::Form-->
                                    </div>
                                    <!--end::Menu 1-->
                                </div>
                                <!--end::Filter menu-->
                            </div>
                        </form>
                        <!--end::filters-->

                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-secondary fw-bolder px-4 me-1 monthly {{ $monthActive }}"
                                    data-bs-toggle="tab" href="#" onclick="renderMonth()">شهرياً</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-secondary fw-bolder px-4 me-1 weekly {{ $weekActive }}"
                                    data-bs-toggle="tab" href="#" onclick="renderWeek()">أسبوعياً</a>
                            </li>
                        </ul>
                    </div>


                    <div class="card-toolbar">
                        <a href="{{ route('reservations.create') }}" class="btn btn-primary">إنشاء حجز جديد</a>
                    </div>
                </div>

                <div class="bodyContent">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <script>
        var events = {{ Js::from($events) }};

        $(document).ready(function() {


            if ($('.monthly').hasClass('active')) {
                renderMonth();
            }

            if ($('.weekly').hasClass('active')) {
                renderWeek();
            }

            $(".container").css({
                'padding': '30px',
                'background-color': 'white',
            });

            $(".myContent").removeClass('d-none');
            $(".bodyContent").addClass('card-body');
        });

        function renderWeek() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                scrollTime: '00:00:00',
                events
            });
            calendar.render();

            $("input[name=calendar_type]").val('week');
        }

        function renderMonth() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events
            });
            calendar.render();

            $("input[name=calendar_type]").val('month');
        }
    </script>
    <script src='fullcalendar/main.js'></script>
    <link href='fullcalendar/main.css' rel='stylesheet' />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>  
@endpush
