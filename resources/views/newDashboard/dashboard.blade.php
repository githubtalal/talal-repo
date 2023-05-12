@extends('newDashboard.layouts.master')
@section('content')
    @php
        $date = request('date');
        $status = request('status', \App\Models\Order::COMPLETED);
    @endphp
    <div class="content d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div class="toolbar">
            <!--begin::Page title-->
            <div class="container-fluid">
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                    الإحصائيات العامة
                </h1>
            </div>
        </div>
        <!--begin::Container-->
        <div class="container-xxl">
            <div class="row">
                <form id="searchForm" action="{{ route('dashboard') }}" method="get" enctype="multipart/form-data">
                    <div class="d-flex mb-5">
                        <div class="col-lg-5 mx-4">
                            <label class="form-label">حالة الطلبات:</label>
                            <select class="form-select" data-control="select2" data-hide-search="true"
                                data-placeholder="اختر حالة الطلب" name="status" id="">
                                <option disabled selected>اختر حالة الطلب</option>
                                <option value="all" {{ $status == 'all' ? 'selected' : '' }}>جميع الحالات</option>
                                @foreach (\App\Models\Order::getStatuses() as $statusCode => $statusLabel)
                                    <option value="{{ $statusCode }}" {{ $status == $statusCode ? 'selected' : '' }}>
                                        {{ $statusLabel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-5 mx-4">
                            <label class="form-label">المدة الزمنية:</label>
                            <input class="form-control" placeholder="Pick date" name="date" id="kt_daterangepicker_1" />
                        </div>
                        <div class="d-flex align-items-end p-2">
                            <button type="submit" class="btn btn-sm btn-primary me-2">تطبيق</button>
                        </div>
                    </div>
                </form>
            </div>
            <!--Widgets-->
            <div class="row g-5 g-xl-4">

                <!--begin::Total Customers Count Widget-->
                <div class="col-xl-4 ">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title fs-2hx fw-bolder text-dark me-2 lh-1 ls-n2">
                                {{ $statistics['customers']['total'] }}</h5>
                            <h6 class="card-subtitle text-gray-400 pt-1 fw-bolder fs-6">عدد الزبائن الكلي</h6>
                        </div>
                    </div>
                </div>
                <!--end::Total Customers Count Widget-->

                <!--begin::Current Month Customers Widget-->
                <div class="col-xl-4 ">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title fs-2hx fw-bolder text-dark me-2 lh-1 ls-n2">
                                {{ $statistics['customers']['current_month'] }}</h5>
                            <h6 class="card-subtitle text-gray-400 pt-1 fw-bolder fs-6">زبائن هذا الشهر</h6>
                        </div>
                    </div>
                </div>
                <!--end::Current Month Customers Widget-->

                <!--begin::Total Sales Widget-->
                <div class="col-xl-4 ">

                    <div class="card">
                        <div class="card-body ">
                            <div class="d-flex justify-content-center">
                                <h5 class="card-title fs-2hx fw-bolder text-dark me-2 lh-1 ls-n2">
                                    {{ explode(' ليرة سورية', price_format($statistics['orders']['total']))[0] }}
                                </h5>
                                <span class="fs-4 fw-bolder text-gray-400 me-1 align-self-start">ليرة سورية</span>
                            </div>
                            <h6 class="text-center card-subtitle text-gray-400 pt-1 fw-bolder fs-6">إجمالي المبيعات</h6>
                        </div>
                    </div>
                </div>
                <!--end::Total Sales Widget-->

                <!--begin::Orders Count Widget-->
                <div class="col-xl-4">
                    <div class="card">
                        <a class="btn btn-sm btn-icon btn-active-color-primary m-2" style="position: absolute;left:0"
                            href="{{ route('orders.index') }}">
                            <i class="bi bi-arrow-90deg-left"></i>
                        </a>
                        <div class="card-body text-center">
                            <h5 class="card-title fs-2hx fw-bolder text-dark me-2 lh-1 ls-n2">
                                {{ $statistics['orders']['count'] }}</h5>
                            <h6 class="card-subtitle text-gray-400 pt-1 fw-bolder fs-6">عدد الطلبات الكلي</h6>
                        </div>
                    </div>
                </div>
                <!--end::Orders Count Widget-->

                <!--begin::Paid Orders Count Widget-->
                <div class="col-xl-4">
                    <form id="paidForm" action="{{ route('orders.index') }}" method="get" enctype="multipart/form-data">
                        <input type="hidden" name="status" value="paid" />
                    </form>
                    <div class="card">
                        <a class="paidButton btn btn-sm btn-icon btn-active-color-primary m-2"
                            style="position: absolute;left:0">
                            <i class="bi bi-arrow-90deg-left"></i>
                        </a>
                        <div class="card-body text-center">
                            <h5 class="card-title fs-2hx fw-bolder text-dark me-2 lh-1 ls-n2">
                                {{ $statistics['orders']['paid_count'] }}</h5>
                            <h6 class="card-subtitle text-gray-400 pt-1 fw-bolder fs-6">عدد الطلبات المدفوعة</h6>
                        </div>
                    </div>
                </div>
                <!--end::Paid Orders Count Widget-->

                <!--begin::Pending Orders Count Widget-->
                <div class="col-xl-4 mb-5">
                    <form id="pendingForm" action="{{ route('orders.index') }}" method="get"
                        enctype="multipart/form-data">
                        <input type="hidden" name="status" value="pending" />
                    </form>
                    <div class="card">
                        <a class="pendingButton btn btn-sm btn-icon btn-active-color-primary m-2"
                            style="position: absolute;left:0">
                            <i class="bi bi-arrow-90deg-left"></i>
                        </a>
                        <div class="card-body text-center">
                            <h5 class="card-title fs-2hx fw-bolder text-dark me-2 lh-1 ls-n2">
                                {{ $statistics['orders']['pending_count'] }}</h5>
                            <h6 class="card-subtitle text-gray-400 pt-1 fw-bolder fs-6">عدد الطلبات بانتظار الدفع</h6>
                        </div>
                    </div>
                </div>
                <!--end::Pending Orders Count Widget-->

            </div>

            <!--Line Chart-->
            <div class="row g-5 g-xl-6">
                <!--begin::Line Chart-->
                <div class="col-xl-12 mb-5 mb-xl-6">
                    <div class="card card-flush overflow-hidden h-md-100">
                        <!--begin::Header-->
                        <div class="card-header py-5">
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-dark">عدد الطلبات خلال الفترة المحددة</span>
                            </h3>
                            <!--end::Title-->
                        </div>
                        <!--end::Header-->
                        <!--begin::Card body-->
                        <div class="card-body d-flex justify-content-between flex-column pb-1 px-0">
                            <div class="px-9 mb-5">
                                <!--begin::Chart-->
                                <div>
                                    <canvas id="lineChart"></canvas>
                                </div>
                                <!--end::Chart-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                </div>
                <!--end::Line Chart-->
            </div>

            <!--Pie Charts-->
            <div class="row g-5 g-xl-6">
                <!--begin::Categories Pie Chart-->
                <div class="col-xl-6 mb-xl-6">
                    <div class="card card-flush overflow-hidden h-md-100">
                        <!--begin::Header-->
                        <div class="card-header py-5">
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-dark">المبيعات حسب الفئات</span>
                            </h3>
                            <!--end::Title-->
                        </div>
                        <!--end::Header-->
                        <!--begin::Card body-->
                        <div class="card-body">
                            <div class="px-16 mb-5">
                                <!--begin::Chart-->
                                <div>
                                    <canvas id="categoriesPieChart"></canvas>
                                </div>
                                <!--end::Chart-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                </div>
                <!--end::Categories Pie Chart-->

                <!--begin::Payment Methods Pie Chart-->
                <div class="col-xl-6 mb-xl-6 mb-5">
                    <div class="card card-flush overflow-hidden h-md-100">
                        <!--begin::Header-->
                        <div class="card-header py-5">
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-dark">طرق الدفع الأكثر استخداماً</span>
                            </h3>
                            <!--end::Title-->
                        </div>
                        <!--end::Header-->
                        <!--begin::Card body-->
                        <div class="card-body">
                            <div class="px-16 mb-5">
                                <!--begin::Chart-->
                                <div>
                                    <canvas id="methodsPieChart"></canvas>
                                </div>
                                <!--end::Chart-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                </div>
                <!--end::Payment Methods Pie Chart-->
            </div>

            <!--Tables-->
            <div class="row gy-5 g-xl-6">

                <!--begin::Top Products Table-->
                <div class="col-xl-6 mb-xl-2">
                    <div class="card card-xl-stretch mb-xl-8">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5 d-flex align-items-center">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3">المنتجات الأكثر مبيعاً</span>
                            </h3>

                            <form id="topProductsForm" action="{{ route('report', ['ProductsReport', 0]) }}"
                                method="get" enctype="multipart/form-data">
                                <input type="hidden" name="order" value="orders_count" />
                                <a type="submit" class="topProductsButton pb-2">
                                    <i class="fas fa-external-link-alt" style="transform: rotate(270deg);"></i>
                                </a>
                            </form>

                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body py-3">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr class="fw-bolder text-muted">
                                            <th class="min-w-200px">{{ __('app.products.info.Name') }}</th>
                                            <th class="min-w-150px">{{ __('app.products.info.Price') }}</th>
                                            <th class="min-w-150px">{{ __('app.products.info.orders_count') }}</th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody>
                                        @foreach ($topProducts as $product)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-45px me-5">
                                                            <img src="{{ Storage::url($product->image_url) }}"
                                                                alt="" />
                                                        </div>
                                                        <div class="d-flex justify-content-start flex-column">
                                                            <span class="text-dark fw-bolder fs-6">
                                                                {{ $product->name }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="fw-bolder">
                                                        {{ price_format($product->price) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fw-bolder">
                                                        {{ $product->orders_count }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Table container-->
                        </div>
                        <!--begin::Body-->
                    </div>
                </div>
                <!--end::Top Products Table-->

                <!--begin::Top Customers Table-->
                <div class="col-xl-6 mb-5 mb-xl-2">
                    <div class="card card-xl-stretch mb-xl-8">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5 d-flex align-items-center">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">الزبائن الأكثر طلباً</span>
                            </h3>
                            <form id="topCustomersForm" action="{{ route('report', ['CustomersReport', 0]) }}"
                                method="get" enctype="multipart/form-data">
                                <input type="hidden" name="order" value="orders_count" />
                                <a type="submit" class="topCustomersButton pb-2">
                                    <i class="fas fa-external-link-alt" style="transform: rotate(270deg);"></i>
                                </a>
                            </form>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body py-3">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr class="fw-bolder text-muted">
                                            <th class="min-w-200px">{{ __('app.order.customer_info.Name') }}</th>
                                            <th class="min-w-150px">{{ __('app.reports.orders_values') }}</th>
                                            <th class="min-w-150px">{{ __('app.reports.orders_count') }}</th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody>
                                        @foreach ($topCustomers as $customer)
                                            <tr>
                                                <td>
                                                    <span class="text-dark fw-bolder fs-6">
                                                        {{ $customer->name }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fw-bolder">
                                                        {{ price_format($customer->orders_total) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fw-bolder">
                                                        {{ $customer->orders_count }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Table container-->
                        </div>
                        <!--begin::Body-->
                    </div>
                </div>
                <!--end::Top Customers Table-->

            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script type="text/javascript">
        // Begin Line Chart
        const totalOrdersLabels = {{ Js::from($ordersTotal['labels']) }};
        const totalOrdersValues = {{ Js::from($ordersTotal['values']) }};
        const max = {{ Js::from($ordersTotal['max']) }};

        if (max <= 10) {
            stepSize = 1;
            maxStep = 10;
        } else {
            stepSize = Math.round(max / 10);
            maxStep = (Math.round(max / stepSize) * stepSize) + stepSize;
        }

        const totalOrdersData = {
            labels: totalOrdersLabels,
            datasets: [{
                label: 'عدد الطلبات',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: totalOrdersValues,
            }]
        };

        const totalOrdersConfig = {
            type: 'line',
            data: totalOrdersData,
            options: {
                scales: {
                    y: {
                        max: maxStep,
                        min: 0,
                        ticks: {
                            stepSize: stepSize
                        }
                    }
                }
            }
        };

        const lineChart = new Chart(
            document.getElementById('lineChart'),
            totalOrdersConfig
        );
        // End Line Chart

        // Begin Categories Pie Chart
        const salesByCategoriesLabels = {{ Js::from($salesByCategories['labels']) }};
        const salesByCategoriesValues = {{ Js::from($salesByCategories['values']) }};

        const data = {
            labels: salesByCategoriesLabels,
            datasets: [{
                label: 'Sales',
                data: salesByCategoriesValues,
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(252,15, 86)'
                ],
                hoverOffset: 4
            }]
        };

        const config = {
            type: 'doughnut',
            data: data,
            options: {}
        };

        const pieChart = new Chart(
            document.getElementById('categoriesPieChart'),
            config
        );
        // End Categories Pie Chart

        // Begin Methods Pie Chart
        const topPaymentMethodsLabels = {{ Js::from($topPaymentMethods['labels']) }};
        const topPaymentMethodsValues = {{ Js::from($topPaymentMethods['values']) }};

        const methodsData = {
            labels: topPaymentMethodsLabels,
            datasets: [{
                label: 'Payment Methods',
                data: topPaymentMethodsValues,
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(252,15, 86)'
                ],
                hoverOffset: 4
            }]
        };

        const methodsConfig = {
            type: 'doughnut',
            data: methodsData,
            options: {}
        };

        const methodsPieChart = new Chart(
            document.getElementById('methodsPieChart'),
            methodsConfig
        );
        // End Methods Pie Chart

        $(function() {
            $('.pendingButton').on('click', function() {
                $('#pendingForm').submit();
            });
            $('.paidButton').on('click', function() {
                $('#paidForm').submit();
            });
            $('.topProductsButton').on('click', function() {
                $('#topProductsForm').submit();
            });
            $('.topCustomersButton').on('click', function() {
                $('#topCustomersForm').submit();
            });
        });

        // range date picker
        var start = moment().startOf("month");
        var end = moment();

        function cb(start, end) {
            $("#kt_daterangepicker_1").html(start.format("YYYY-MM-DD") + " - " + end.format("YYYY-MM-DD"));
        }

        $("#kt_daterangepicker_1").daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                "اليوم": [moment(), moment()],
                "البارحة": [moment().subtract(1, "days"), moment().subtract(1, "days")],
                "آخر سبعة أيام": [moment().subtract(6, "days"), moment()],
                "آخر 30 يوم": [moment().subtract(29, "days"), moment()],
                "الشهر الحالي": [moment().startOf("month"), moment()],
                "الشهر الماضي": [moment().subtract(1, "month").startOf("month"), moment().subtract(1,
                    "month").endOf("month")]
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
            cb(start, end);
    </script>
@endpush
