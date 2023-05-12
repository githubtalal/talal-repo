@extends('newDashboard.layouts.master')
@section('content')
    @php
        $count = 1;
        $startDate = '';
        $endDate = '';
        $order_info = [
            __('app.order.order_info.Date') => $order->created_at->format('Y-m-d g:i a'),
            __('app.order.order_info.Platform') => $order->platform ?? '---',
        ];
        
        $notesLabel = __('app.order.customer_info.Notes');
        
        $bot_settings = \App\Models\StoreSettings::where([['store_id', auth()->user()->store->id], ['key', 'bot_settings']])->first();
        
        if ($bot_settings) {
            $bot_settings = $bot_settings->value;
        
            if (array_key_exists('notes', $bot_settings) && $bot_settings['notes']) {
                $notesLabel = $bot_settings['notes'];
            }
        
            if (array_key_exists('question1', $bot_settings) && $bot_settings['question1']) {
                $q1Value = $bot_settings['question1'];
            }
        
            if (array_key_exists('question2', $bot_settings) && $bot_settings['question2']) {
                $q2Value = $bot_settings['question2'];
            }
        }
        $steps = getStoreSteps();
        
        if (
            auth()
                ->user()
                ->hasRole('super-admin')
        ) {
            $store = \App\Models\Store::query()
                ->where('order_id', $order->id)
                ->first();
        
            $customer_info = [];
        
            if ($store) {
                $customer_info = array_merge($customer_info, [
                    'Store name' => $store->name,
                    'Store type' => $store->type,
                    'Store email' => $store->user()->first()->email,
                ]);
            } else {
                $customer_info = array_merge($customer_info, [
                    'Store name' => $order->store->name,
                    'Store type' => $order->store->type,
                    'Store email' => $order->owner->email,
                ]);
            }
        }
        
        $products_info = [__('app.order.products_info.product_name'), __('app.order.products_info.product_price'), __('app.order.products_info.fees'), __('app.order.products_info.tax'), __('app.order.products_info.quantity'), __('app.order.products_info.subtotal'), __('app.order.products_info.fees_total'), __('app.order.products_info.tax_total'), __('app.order.products_info.total')];
        $reservations_info = [__('app.order.products_info.reservation_name'), __('app.order.products_info.reservation_price'), __('app.order.products_info.start_date'), __('app.order.products_info.end_date'), __('app.order.products_info.fees'), __('app.order.products_info.tax'), __('app.order.products_info.subtotal'), __('app.order.products_info.fees_total'), __('app.order.products_info.tax_total'), __('app.order.products_info.total')];
        
        if ($order->payment_method != 'fatora') {
            $products_info[] = __('app.actions.actions');
            $reservations_info[] = __('app.actions.actions');
        }
        
        $currentRoute = \Illuminate\Support\Facades\Route::currentRouteName();
        
        if (str_contains($currentRoute, 'subscriptions')) {
            $viewRoute = 'orders.subscriptions.view';
        } else {
            $viewRoute = 'orders.view';
        }
        
    @endphp

    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid">

        <!--begin::Toolbar-->
        <div class="toolbar">
            <div class="container-fluid">
                <h4> {{ __('app.order.order_info.order_info') }} :</h4>
            </div>
            <!--begin::Container-->
            <div class="container-fluid d-flex justify-content-end">
                <button class="btn btn-sm btn-primary mx-3" type="submit" onclick="$('#updateForm').submit();">حفظ
                </button>
                <a class="btn btn-sm btn-light" href="{{ route($viewRoute, [$order]) }}">إلغاء</a>
            </div>
            <!--end::Container-->
        </div>
        <!--end::Toolbar-->

        <!--begin::Post-->
        <div class="container-xxl">
            <form action="{{ route('orders.update_order', [$order]) }}" id="updateForm" method="post">
                @csrf

                <div class="row gy-4 g-xl-5">
                    <!--begin::Order Info-->
                    <div class="col-xl-6 mb-xl-1">
                        <div class="card card-xl-stretch">
                            <!--begin::Card header-->
                            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                <div class="order_info_div">
                                    <h2> {{ '#' . $order->id }} :</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::items info-->
                            <div class="card-body pt-0">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-dashed fs-6 gy-5">
                                    <!--begin::Table head-->
                                    <thead>
                                        <!--begin::Table row-->
                                        @foreach ($order_info as $label => $item)
                                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0 d-flex">
                                                <th class="min-w-100px col-lg-6">{{ $label }}</th>
                                                <td class="text-gray-800 fw-bolder"> {{ $item }}</td>
                                            </tr>
                                        @endforeach

                                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0 d-flex">
                                            <th class="min-w-100px col-lg-6">{{ __('app.order.order_info.Subtotal') }}</th>
                                            <td class="text-gray-800 fw-bolder orderSubtotal">
                                                {{ price_format($order->subtotal, __('app.currency_types.' . $order->currency)) }}
                                            </td>
                                        </tr>

                                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0 d-flex">
                                            <th class="min-w-100px col-lg-6">{{ __('app.order.order_info.TotalFees') }}
                                            </th>
                                            <td class="text-gray-800 fw-bolder orderFeesAmount">
                                                {{ price_format($order->fees_amount, __('app.currency_types.' . $order->currency)) }}
                                            </td>
                                        </tr>

                                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0 d-flex">
                                            <th class="min-w-100px col-lg-6">{{ __('app.order.order_info.TotalTax') }}</th>
                                            <td class="text-gray-800 fw-bolder orderTaxAmount">
                                                {{ price_format($order->tax_amount, __('app.currency_types.' . $order->currency)) }}
                                            </td>
                                        </tr>

                                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0 d-flex">
                                            <th class="min-w-100px col-lg-6">{{ __('app.order.order_info.Total') }}</th>
                                            <td class="text-gray-800 fw-bolder orderTotal">
                                                {{ price_format($order->total, __('app.currency_types.' . $order->currency)) }}
                                            </td>
                                        </tr>

                                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0 d-flex">
                                            <th class="min-w-100px col-lg-6">{{ __('app.order.order_info.Status') }}</th>
                                            <td class="text-gray-800 fw-bolder">
                                                {{ __('app.order.statuses.' . $order->status) }}</td>
                                        </tr>

                                        <tr
                                            class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0 d-flex align-items-center">
                                            <th class="col-lg-6">{{ __('app.order.order_info.Change_status') }}</th>
                                            <td>
                                                <select class="form-select mb-2" data-control="select2"
                                                    data-hide-search="true" name="status">
                                                    <option value="" disabled>أختر</option>
                                                    @foreach (\App\Models\Order::getStatuses() as $statusCode => $status)
                                                        <option value="{{ $statusCode }}"
                                                            {{ $order->status == $statusCode ? 'selected' : '' }}>
                                                            {{ $status }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>

                                        <tr
                                            class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0 d-flex align-items-center">
                                            <th class="col-lg-6">{{ trans_choice('app.payment_methods', 1) }}</th>

                                            @if ($order->payment_method != 'fatora')
                                                <td>
                                                    <select class="form-select mb-2" data-control="select2"
                                                        data-hide-search="true" name="payment_method">
                                                        <option value="" disabled>اختر</option>
                                                        @foreach ($payment_methods as $key => $value)
                                                            <option value="{{ $key }}"
                                                                {{ $order->payment_method == $key ? 'selected' : '' }}>
                                                                {{ $value }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            @else
                                                <td class="text-gray-800 fw-bolder"> {{ $order->payment_method }}</td>
                                            @endif
                                        </tr>
                                        <!--end::Table row-->
                                    </thead>
                                    <!--end::Table head-->
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::items info-->
                        </div>
                    </div>

                    <!--begin::Customer Info-->
                    <div class="col-xl-6 mb-xl-1 mb-4">
                        <div class="card card-xl-stretch">
                            <!--begin::Card header-->
                            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                <div class="order_info_div">
                                    <h2> {{ __('app.order.customer_info.customer_info') }}</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::items info-->
                            <div class="card-body pt-0">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-dashed fs-6 gy-5">
                                    <!--begin::Table head-->
                                    <thead>

                                        <!-- Name -->
                                        <tr
                                            class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0 d-flex align-items-center">
                                            <th class="col-lg-3 min-w-100px">{{ __('app.order.customer_info.Name') }}
                                            </th>
                                            <td class="text-gray-800 fw-bolder col-lg-8">
                                                <input type="text" name="name"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    value="{{ $order->first_name . ' ' . $order->last_name }}" />
                                            </td>
                                        </tr>

                                        <!-- Phone_number -->
                                        <tr
                                            class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0 d-flex align-items-center">
                                            <th class="col-lg-3 min-w-100px">
                                                {{ __('app.order.customer_info.Phone_number') }}
                                            </th>
                                            <td class="text-gray-800 fw-bolder col-lg-8">
                                                <input type="text" name="phone_number"
                                                    class="form-control @error('phone_number') is-invalid @enderror"
                                                    value="{{ $order->phone_number ?? '---' }}" />
                                            </td>
                                        </tr>

                                        @if ($steps)
                                            @if (array_key_exists('governorate', $steps))
                                                <!-- Governorate -->
                                                <tr
                                                    class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0 d-flex align-items-center">
                                                    <th class="col-lg-3 min-w-100px">
                                                        {{ __('app.order.customer_info.Governorate') }}</th>
                                                    <td class="text-gray-800 fw-bolder col-lg-8">
                                                        <select class="form-select" data-control="select2"
                                                            data-hide-search="true" data-placeholder="اختر المحافظة"
                                                            name="governorate" id="">
                                                            <option disabled selected>اختر المحافظة</option>
                                                            @foreach (get_states() as $stateCode => $stateName)
                                                                <option
                                                                    value="{{ $stateCode }}"{{ $order->governorate == $stateCode ? 'selected' : '' }}>
                                                                    {{ $stateName }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if (array_key_exists('address', $steps))
                                                <!-- Address -->
                                                <tr
                                                    class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0 d-flex align-items-center">
                                                    <th class="col-lg-3 min-w-100px">
                                                        {{ __('app.order.customer_info.Address') }}</th>
                                                    <td class="text-gray-800 fw-bolder col-lg-8">
                                                        <input type="text" name="address" class="form-control"
                                                            value="{{ $order->address ?? '' }}" />
                                                    </td>
                                                </tr>
                                            @endif
                                            @if (array_key_exists('notes', $steps))
                                                <!-- Notes -->
                                                <tr
                                                    class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0 d-flex align-items-center">
                                                    <th class="col-lg-3 min-w-100px">
                                                        {{ $notesLabel }}</th>
                                                    <td class="text-gray-800 fw-bolder col-lg-8">
                                                        <input type="text" name="notes" class="form-control"
                                                            value="{{ $order->notes ?? '' }}" />
                                                    </td>
                                                </tr>
                                            @endif
                                            @if (array_key_exists('question1', $steps))
                                                <!-- Question1 -->
                                                <tr
                                                    class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0 d-flex align-items-center">
                                                    <th class="col-lg-3 min-w-100px">
                                                        {{ $q1Value }}</th>
                                                    <td class="text-gray-800 fw-bolder col-lg-8">
                                                        <input type="text" name="additional_question1"
                                                            class="form-control"
                                                            value="{{ $order->additional_question1 ?? '' }}" />
                                                    </td>
                                                </tr>
                                            @endif
                                            @if (array_key_exists('question2', $steps))
                                                <!-- Question2 -->
                                                <tr
                                                    class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0 d-flex align-items-center">
                                                    <th class="col-lg-3 min-w-100px">
                                                        {{ $q2Value }}</th>
                                                    <td class="text-gray-800 fw-bolder col-lg-8">
                                                        <input type="text" name="additional_question2"
                                                            class="form-control"
                                                            value="{{ $order->additional_question2 ?? '' }}" />
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif

                                    </thead>
                                    <!--end::Table head-->
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::items info-->
                        </div>
                    </div>
                </div>

                <!--begin::Products Info-->
                <div class="row gy-5 g-xl-6">
                    <div class="col-xl-12 mb-4 mb-xl-6">
                        <div class="card card-flush">
                            <!--begin::Card header-->
                            <div class="card-header py-5 gap-2 gap-md-5">
                                <div class="order_info_label">
                                    <h2>{{ __('app.order.products_info.products_info') }}</h2>
                                </div>

                                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                    @if ($order->payment_method != 'fatora')
                                        <a id="openProductsModal" data-bs-toggle="modal" data-bs-target="#addProduct"
                                            class="btn btn-primary mx-2">إضافة
                                            منتج</a>
                                    @endif
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::items info-->
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                                        <!--begin::Table head-->
                                        <thead>
                                            <!--begin::Table row-->
                                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                                @foreach ($products_info as $item)
                                                    <th class="min-w-100px">{{ $item }}</th>
                                                @endforeach
                                            </tr>
                                            <!--end::Table row-->
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody class="fw-bold text-gray-600 products_table">
                                            @foreach ($productsItems as $item)
                                                @php
                                                    $product = App\Models\Product::where('id', $item->product_id)
                                                        ->withTrashed()
                                                        ->first();
                                                @endphp
                                                <tr class="static">
                                                    <!-- Product Name -->
                                                    <td>
                                                        <input type="hidden" class="product_id"
                                                            id="{{ $count }}"
                                                            name="products[{{ $count }}][product_id]"
                                                            value='{{ $product->id }}' />
                                                        <input type="hidden" class="orderItem_id"
                                                            id="{{ $count }}"
                                                            name="products[{{ $count }}][orderItem_id]"
                                                            value='{{ $item->id }}' />
                                                        <div class="text-gray-800 fw-bolder">
                                                            {{ $product->name }}
                                                        </div>
                                                    </td>
                                                    <!-- Product Price -->
                                                    <td>
                                                        <div class="text-gray-800 fw-bolder price"
                                                            id="{{ $count }}">
                                                            {{ price_format($item->price, __('app.currency_types.' . $product->currency)) }}
                                                        </div>
                                                        <input type="hidden" class="updatedPrice" value="old"
                                                            id="{{ $count }}"
                                                            name="products[{{ $count }}][price]" />
                                                        @if ($product->price != $item->price)
                                                            <div class="modal fade"
                                                                id="differentPriceModal{{ $count }}"
                                                                tabindex="-1" role="dialog"
                                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="exampleModalLabel">
                                                                                تنبيه!
                                                                            </h5>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="me-10">
                                                                                <label class="form-label">هذا المنتج قد تم
                                                                                    تعديل سعره .. يرجى اختيار السعر الذي تود
                                                                                    إتمام الطلب به</label>
                                                                                <form>
                                                                                    <div class="">
                                                                                        <input type="radio"
                                                                                            name="new_price{{ $count }}"
                                                                                            class="old"
                                                                                            formatted="{{ price_format($item->price, __('app.currency_types.' . $product->currency)) }}"
                                                                                            id="{{ $count }}"
                                                                                            value="{{ $item->price }}"
                                                                                            checked="checked" />
                                                                                        <label class="form-label">
                                                                                            {{ 'السعر القديم:  ' . price_format($item->price, __('app.currency_types.' . $product->currency)) }}
                                                                                        </label>
                                                                                    </div>
                                                                                    <div>
                                                                                        <input type="radio"
                                                                                            name="new_price{{ $count }}"
                                                                                            class="new"
                                                                                            formatted="{{ price_format($product->price, __('app.currency_types.' . $product->currency)) }}"
                                                                                            value="{{ $product->price }}"
                                                                                            id="{{ $count }}" />
                                                                                        <label class="form-label">
                                                                                            {{ 'السعرالجديد:  ' . price_format($product->price, __('app.currency_types.' . $product->currency)) }}
                                                                                        </label>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-primary change_product_price"
                                                                                id="{{ $count }}">حفظ
                                                                            </button>
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-dismiss="modal"
                                                                                onclick="$('#differentPriceModal{{ $count }}').modal('hide');">
                                                                                إلغاء
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <!-- Fees -->
                                                    <td>
                                                        <div class="text-gray-800 fw-bolder" id="">
                                                            {{ price_format($item->fees_amount, __('app.currency_types.' . $product->currency)) }}
                                                        </div>
                                                    </td>
                                                    <!-- Tax -->
                                                    <td>
                                                        <div class="text-gray-800 fw-bolder" id="">
                                                            {{ price_format($item->tax_amount, __('app.currency_types.' . $product->currency)) }}
                                                        </div>
                                                    </td>
                                                    <!-- Quantity -->
                                                    <td>
                                                        @if ($order->payment_method != 'fatora')
                                                            <input type="number"
                                                                class="form-control w-100px quantityOldProduct"
                                                                id="{{ $count }}"
                                                                name="products[{{ $count }}][quantity]"
                                                                value="{{ $item->quantity }}" min="1" />
                                                        @else
                                                            <div class="text-gray-800 fw-bolder total">
                                                                {{ $item->quantity }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <!-- Subtotal -->
                                                    <td>
                                                        <div class="text-gray-800 fw-bolder subtotal"
                                                            id="{{ $count }}">
                                                            {{ price_format($item->subtotal, __('app.currency_types.' . $product->currency)) }}
                                                        </div>
                                                    </td>
                                                    <!-- Fees Total -->
                                                    <td>
                                                        <div class="text-gray-800 fw-bolder fees_total"
                                                            id="{{ $count }}">
                                                            {{ price_format($item->fees_amount_subtotal, __('app.currency_types.' . $product->currency)) }}
                                                        </div>
                                                    </td>
                                                    <!-- Tax Total -->
                                                    <td>
                                                        <div class="text-gray-800 fw-bolder tax_total"
                                                            id="{{ $count }}">
                                                            {{ price_format($item->tax_amount_subtotal, __('app.currency_types.' . $product->currency)) }}
                                                        </div>
                                                    </td>
                                                    <!-- Total -->
                                                    <td>
                                                        <div class="text-gray-800 fw-bolder total"
                                                            id="{{ $count }}">
                                                            {{ price_format($item->total, __('app.currency_types.' . $product->currency)) }}
                                                        </div>
                                                    </td>
                                                    @if ($order->payment_method != 'fatora')
                                                        <td>
                                                            <button type="button" class="btn btn-light btn-sm delete"
                                                                onclick=" $(this).parents('tr').remove();">حذف
                                                            </button>
                                                        </td>
                                                    @endif
                                                </tr>
                                                @php $count++; @endphp
                                            @endforeach
                                        </tbody>
                                        <!--end::Table body-->
                                    </table>
                                    <!--end::Table-->
                                </div>
                            </div>
                            <!--end::items info-->
                        </div>
                    </div>
                </div>

                <!--begin::Reservations Info-->
                <div class="row gy-5 g-xl-6">
                    <div class="col-xl-12 mb-4 mb-xl-6">
                        <div class="card card-flush">
                            <!--begin::Card header-->
                            <div class="card-header py-5 gap-2 gap-md-5">
                                <div class="order_info_label">
                                    <h2>{{ __('app.order.products_info.reservations_info') }}</h2>
                                </div>

                                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                    @if ($order->payment_method != 'fatora')
                                        <a id="openReservationModal" data-bs-toggle="modal"
                                            data-bs-target="#addReservation" class="btn btn-primary mx-2">إضافة
                                            حجز</a>
                                    @endif
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::items info-->
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                                        <!--begin::Table head-->
                                        <thead>
                                            <!--begin::Table row-->
                                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                                @foreach ($reservations_info as $item)
                                                    <th class="min-w-100px">{{ $item }}</th>
                                                @endforeach
                                            </tr>
                                            <!--end::Table row-->
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody class="fw-bold text-gray-600 reservations_table">
                                            @foreach ($reservationsItems as $item)
                                                @php
                                                    $product = App\Models\Product::where('id', $item->product_id)
                                                        ->withTrashed()
                                                        ->first();
                                                @endphp
                                                <tr class="static">
                                                    <!-- Product Name -->
                                                    <td>
                                                        <input type="hidden" class="reservation_id"
                                                            id="{{ $count }}" value='{{ $product->id }}'
                                                            name="products[{{ $count }}][product_id]" />
                                                        <input type="hidden" class="orderItem_id"
                                                            id="{{ $count }}"
                                                            name="products[{{ $count }}][orderItem_id]"
                                                            value='{{ $item->id }}' />
                                                        <div class="text-gray-800 fw-bolder">
                                                            {{ $product->name }}
                                                        </div>
                                                    </td>
                                                    <!-- Product Price -->
                                                    <td>
                                                        <div
                                                            class="text-gray-800 fw-bolder reservationsPrice{{ $count }}">
                                                            {{ price_format($item->price, __('app.currency_types.' . $product->currency)) }}
                                                        </div>
                                                        <input type="hidden" class="updatedPrice" value="old"
                                                            id="{{ $count }}"
                                                            name="products[{{ $count }}][price]" />
                                                        @if ($product->price != $item->price)
                                                            <div class="modal fade"
                                                                id="differentPriceModal{{ $count }}"
                                                                tabindex="-1" role="dialog"
                                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="exampleModalLabel">
                                                                                تنبيه!
                                                                            </h5>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="me-10">
                                                                                <label class="form-label">هذا المنتج قد تم
                                                                                    تعديل سعره .. يرجى اختيار السعر الذي تود
                                                                                    إتمام الطلب به</label>
                                                                                <form>
                                                                                    <div class="">
                                                                                        <input type="radio"
                                                                                            name="new_price{{ $count }}"
                                                                                            class="old"
                                                                                            id="{{ $count }}"
                                                                                            value="{{ $item->price }}"
                                                                                            checked="checked"
                                                                                            formatted="{{ price_format($item->price, __('app.currency_types.' . $product->currency)) }}" />
                                                                                        <label class="form-label">
                                                                                            {{ 'السعر القديم:  ' . price_format($item->price, __('app.currency_types.' . $product->currency)) }}
                                                                                        </label>
                                                                                    </div>
                                                                                    <div>
                                                                                        <input type="radio"
                                                                                            name="new_price{{ $count }}"
                                                                                            class="new"
                                                                                            value="{{ $product->price }}"
                                                                                            id="{{ $count }}"
                                                                                            formatted="{{ price_format($product->price, __('app.currency_types.' . $product->currency)) }}" />
                                                                                        <label class="form-label">
                                                                                            {{ 'السعرالجديد:  ' . price_format($product->price, __('app.currency_types.' . $product->currency)) }}
                                                                                        </label>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-primary change_reservation_price"
                                                                                id="{{ $count }}">حفظ
                                                                            </button>
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-dismiss="modal"
                                                                                onclick="$('#differentPriceModal{{ $count }}').modal('hide');">
                                                                                إلغاء
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </td>

                                                    @php
                                                        $startDate = '';
                                                        $endDate = '';
                                                        if ($product->type == 'reservation') {
                                                            $startDate = Carbon\Carbon::parse($item->additional['checkin'])->timezone('Asia/Damascus');
                                                        
                                                            if ($product->require_end_date && ($item->additional['checkout'] ?? null)) {
                                                                $endDate = Carbon\Carbon::parse($item->additional['checkout'])->timezone('Asia/Damascus');
                                                            }
                                                        }
                                                        
                                                        $hourFormat = ifHourEnabled() ? 'H:i' : '';
                                                        $fatora = $order->payment_method == 'fatora' ? true : false;
                                                    @endphp

                                                    <!-- Reservation Start Date -->
                                                    <td>
                                                        <div class="text-gray-800 fw-bolder">
                                                            @if (!$fatora)
                                                                <input
                                                                    class="form-control dateTimePicker old_start_date required reservation_date"
                                                                    placeholder="Pick date" id="{{ $count }}"
                                                                    name="products[{{ $count }}][checkin_date]"
                                                                    hourEnabled="{{ ifHourEnabled() }}"
                                                                    hourFormat="{{ $hourFormat }}"
                                                                    value='{{ $startDate->format('Y-m-d ' . $hourFormat) }}' />
                                                            @else
                                                                {{ $startDate->format('Y-m-d ' . $hourFormat) }}
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <!-- Reservation End Date -->
                                                    <td>
                                                        <div class="text-gray-800 fw-bolder">
                                                            @if ($product->require_end_date && ($endDate ?? null))
                                                                @if (!$fatora)
                                                                    <input
                                                                        class="form-control dateTimePicker old_end_date required reservation_date"
                                                                        placeholder="Pick date" id="{{ $count }}"
                                                                        name="products[{{ $count }}][checkout_date]"
                                                                        hourEnabled="{{ ifHourEnabled() }}"
                                                                        hourFormat="{{ $hourFormat }}"
                                                                        value='{{ $endDate->format('Y-m-d ' . $hourFormat) }}' />
                                                                @else
                                                                    {{ $endDate->format('Y-m-d ' . $hourFormat) }}
                                                                @endif
                                                            @else
                                                                {{ '---' }}
                                                            @endif
                                                        </div>
                                                    </td>

                                                    <!-- Fees -->
                                                    <td>
                                                        <div class="text-gray-800 fw-bolder" id="">
                                                            {{ price_format($item->fees_amount, __('app.currency_types.' . $product->currency)) }}
                                                        </div>
                                                    </td>
                                                    <!-- Tax -->
                                                    <td>
                                                        <div class="text-gray-800 fw-bolder" id="">
                                                            {{ price_format($item->tax_amount, __('app.currency_types.' . $product->currency)) }}
                                                        </div>
                                                    </td>

                                                    <!-- Subtotal -->
                                                    <td>
                                                        <div class="text-gray-800 fw-bolder reservationsSubtotal{{ $count }}"
                                                            id="">
                                                            {{ price_format($item->subtotal, __('app.currency_types.' . $product->currency)) }}
                                                        </div>
                                                    </td>
                                                    <!-- Fees Total -->
                                                    <td>
                                                        <div class="text-gray-800 fw-bolder reservationsFeesTotal{{ $count }}"
                                                            id="">
                                                            {{ price_format($item->fees_amount_subtotal, __('app.currency_types.' . $product->currency)) }}
                                                        </div>
                                                    </td>
                                                    <!-- Tax Total -->
                                                    <td>
                                                        <div class="text-gray-800 fw-bolder reservationsTaxTotal{{ $count }}"
                                                            id="">
                                                            {{ price_format($item->tax_amount_subtotal, __('app.currency_types.' . $product->currency)) }}
                                                        </div>
                                                    </td>
                                                    <!-- Total -->
                                                    <td>
                                                        <div
                                                            class="text-gray-800 fw-bolder reservationsTotal{{ $count }}">
                                                            {{ price_format($item->total, __('app.currency_types.' . $product->currency)) }}
                                                        </div>
                                                    </td>
                                                    @if (!$fatora)
                                                        <td>
                                                            <button type="button" class="btn btn-light btn-sm delete"
                                                                onclick=" $(this).parents('tr').remove();">حذف
                                                            </button>
                                                        </td>
                                                    @endif
                                                </tr>
                                                @php $count++; @endphp
                                            @endforeach
                                        </tbody>
                                        <!--end::Table body-->
                                    </table>
                                    <!--end::Table-->
                                </div>
                            </div>
                            <!--end::items info-->
                        </div>
                    </div>
                </div>
            </form>

            <!--begin::Products Modal-->
            <div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                                إضافة منتج جديد
                            </h5>
                        </div>
                        <div class="modal-body">
                            <div class="me-10">
                                <label class="form-label d-block">المنتجات</label>
                                <select class="form-select mb-2 products" name="">
                                    @foreach ($products as $product)
                                        <option id="{{ $product->id }}" value="{{ $product->id }}">
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if (auth()->user()->store->currency_status)
                                    <label
                                        class="form-label d-block text-gray-600 fs-6 mt-2">{{ __('app.list_same_currency_products') }}</label>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary addProductButton">حفظ</button>
                            <button type="button" class="btn btn-secondary closeProductsModal"
                                data-dismiss="modal">إلغاء
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Reservations Modal-->
            <div class="modal fade" id="addReservation" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                                إضافة حجز جديد
                            </h5>
                        </div>
                        <div class="modal-body">
                            <div class="me-10">
                                <label class="form-label d-block">الحجوزات</label>
                                <select class="form-select mb-2 reservations" name="">
                                    @foreach ($reservations as $reservation)
                                        <option id="{{ $reservation->id }}" value="{{ $reservation->id }}">
                                            {{ $reservation->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if (auth()->user()->store->currency_status)
                                    <label
                                        class="form-label d-block text-gray-600 fs-6 mt-2">{{ __('app.list_same_currency_products') }}</label>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary addReservationButton">حفظ</button>
                            <button type="button" class="btn btn-secondary closeReservationModal"
                                data-dismiss="modal">إلغاء
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Empty Products (show error) Modal-->
            <div class="modal fade" id="emptyProducts" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                                خطأ
                            </h5>
                        </div>
                        <div class="modal-body">
                            <div class="me-10">
                                <label class="form-label">لايمكنك حذف كافة المنتجات والحجوزات</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="$('#emptyProducts').modal('hide');"
                                data-dismiss="modal">موافق
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Notes-->
            <div class="row">
                <div class="col-xl-12">
                    <x-notes-component :route="route('orders.note.store', $order)" :notes="$notes"></x-notes-component>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            var currency;
            var ifCurrencyDifferent;
            var products_ids = [];

            $('.products').select2({
                placeholder: "ابحث عن منتج",
                multiple: true,
            });

            $('.reservations').select2({
                placeholder: "ابحث عن حجز",
                multiple: true,
            });

            $('.products').val(null).trigger('change');
            $('.reservations').val(null).trigger('change');

            count = '{{ $count }}';

            // handle products

            $('#openProductsModal').click(function() {
                $(".products option").attr('disabled', false);
                $('.product_id').each(function() {
                    $(".products option[value='" + $(this).val() + "']").attr('disabled', true);
                });
            });

            $('.closeProductsModal').click(function() {
                $('.products').val(null).trigger('change');
                $('#addProduct').modal('hide');
            });

            $('.addProductButton').on('click', function() {

                products = $('.products').val();

                if (products.length) {

                    $.ajax({
                        url: '{{ route('orders.get_products') }}',
                        type: 'GET',
                        data: {
                            products: products
                        },
                        dataType: 'json',
                        success: function(response) {
                            //console.log(response);
                            products = response.data;
                            products.forEach(element => {
                                $('.products_table').append(
                                    '<tr class="productRow dynamic" id="' + count +
                                    '">' +

                                    '<td>' +
                                    '<input type="hidden" class="product_id" id="' +
                                    count + '" value="' +
                                    element.id + '" name="products[' + count +
                                    '][product_id]"/>' +
                                    '<div class="text-gray-800 fw-bolder">' +
                                    element.name +
                                    '</div>' +
                                    '</td>' +

                                    '<td>' +
                                    '<div class="text-gray-800 fw-bolder price"id="' +
                                    count + '">' +
                                    element.formatted_price +
                                    '</div>' +
                                    '</td>' +

                                    '<td>' +
                                    '<div class="text-gray-800 fw-bolder" id="' +
                                    count + '">' +
                                    element.formatted_fees +
                                    '</div>' +
                                    '</td>' +

                                    '<td>' +
                                    '<div class="text-gray-800 fw-bolder" id="' +
                                    count + '">' +
                                    element.formatted_tax +
                                    '</div>' +
                                    '</td>' +

                                    '<td>' +
                                    '<div class="text-gray-800 fw-bolder">' +
                                    '<input type="number" class="form-control w-100px quantityNewProduct" id="' +
                                    count + '" name="products[' + count +
                                    '][quantity]" value="1" min="1"/>' +
                                    '</div>' +
                                    '</td>' +

                                    '<td>' +
                                    '<div class="text-gray-800 fw-bolder subtotal" id="' +
                                    count + '">' +
                                    element.formatted_price +
                                    '</div>' +
                                    '</td>' +

                                    '<td>' +
                                    '<div class="text-gray-800 fw-bolder fees_total" id="' +
                                    count + '">' +
                                    element.formatted_fees +
                                    '</div>' +
                                    '</td>' +

                                    '<td>' +
                                    '<div class="text-gray-800 fw-bolder tax_total" id="' +
                                    count + '">' +
                                    element.formatted_tax +
                                    '</div>' +
                                    '</td>' +

                                    '<td>' +
                                    '<div class="text-gray-800 fw-bolder total" id="' +
                                    count + '">' +
                                    element.formatted_subtotal +
                                    '</div>' +
                                    '</td>' +

                                    '<td>' +
                                    '<button type="button" class="btn btn-light btn-sm delete" id="' +
                                    count +
                                    '" onclick=$(".productRow#' + count +
                                    '").remove();>' +
                                    'حذف</button>' +
                                    '</td>' +

                                    '</tr>'
                                );

                                count++;
                            });
                            calculateOrderTotal();
                        }
                    });

                }
                $('#addProduct').modal('hide');
                $('.products').val(null).trigger('change');
            });

            // on change for quantities for old items
            $(document).on('change', '.quantityOldProduct', function() {
                var id = $(this).attr('id');
                var quantity = $(this).val();
                var orderItem_id = $('#' + id + '.orderItem_id').val();

                if ($('#differentPriceModal' + id).length) {
                    $('#differentPriceModal' + id).modal('show');
                } else {

                    isOld = $('#' + id + '.updatedPrice').val();

                    $.ajax({
                        url: '{{ route('orders.calculateTotalOldOrderLine') }}',
                        type: 'GET',
                        data: {
                            orderItem_id: orderItem_id,
                            quantity: quantity,
                            isOld: isOld
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#' + id + '.subtotal').text(response.subtotal);
                            $('#' + id + '.fees_total').text(response.fees_total);
                            $('#' + id + '.tax_total').text(response.tax_total);
                            $('#' + id + '.total').text(response.total);
                        }
                    }).done(calculateOrderTotal());
                }
            });

            // on change for quantities for new items
            $(document).on('change', '.quantityNewProduct', function() {
                var id = $(this).attr('id');
                var quantity = $(this).val();
                var product_id = $('#' + id + '.product_id').val();

                $.ajax({
                    url: '{{ route('orders.calculateTotalNewOrderLine') }}',
                    type: 'GET',
                    data: {
                        product_id: product_id,
                        quantity: quantity,
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#' + id + '.subtotal').text(response.subtotal);
                        $('#' + id + '.fees_total').text(response.fees_total);
                        $('#' + id + '.tax_total').text(response.tax_total);
                        $('#' + id + '.total').text(response.total);
                    }
                }).done(calculateOrderTotal());

            });

            $("#addProduct").on("hidden.bs.modal", function() {
                $('.products').val(null).trigger('change');
            });

            $('.change_product_price').on('click', function() {
                var id = $(this).attr('id');
                var isOld = 'old';

                if ($('input:radio[name=new_price' + id + ']:checked').hasClass('new')) {
                    isOld = 'new';
                }

                var newPrice = $('input:radio[name=new_price' + id + ']:checked').attr('formatted');

                $('#' + id + '.updatedPrice').val(isOld);
                $('#' + id + '.price').text(newPrice);

                var quantity = $('#' + id + '.quantityOldProduct').val();
                var orderItem_id = $('#' + id + '.orderItem_id').val();

                $.ajax({
                    url: '{{ route('orders.calculateTotalOldOrderLine') }}',
                    type: 'GET',
                    data: {
                        orderItem_id: orderItem_id,
                        quantity: quantity,
                        isOld: isOld
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#' + id + '.subtotal').text(response.subtotal);
                        $('#' + id + '.fees_total').text(response.fees_total);
                        $('#' + id + '.tax_total').text(response.tax_total);
                        $('#' + id + '.total').text(response.total);
                    }
                }).done(calculateOrderTotal());

                $('#differentPriceModal' + id).modal('hide');
                $('#differentPriceModal' + id).removeAttr('id');
            });

            // handle reservations

            $('.closeReservationModal').click(function() {
                $('.reservations').val(null).trigger('change');
                $('#addReservation').modal('hide');
            });

            $('.addReservationButton').on('click', function() {

                reservations = $('.reservations').val();

                if (reservations.length) {

                    $.ajax({
                        url: '{{ route('orders.get_products') }}',
                        type: 'GET',
                        data: {
                            products: reservations
                        },
                        dataType: 'json',
                        success: function(response) {
                            reservations = response.data;
                            reservations.forEach(element => {

                                nameTd = '<td>' +
                                    '<input type="hidden" class="reservation_id" id="' +
                                    count + '" value="' +
                                    element.id + '" name="products[' +
                                    count +
                                    '][product_id]"/>' +
                                    '<div class="text-gray-800 fw-bolder">' +
                                    element.name +
                                    '</div>' +
                                    '</td>';

                                priceTd =
                                    '<td>' +
                                    '<div class="text-gray-800 fw-bolder reservationsPrice' +
                                    count + '" id="' +
                                    count + '">' +
                                    element.formatted_price +
                                    '</div>' +
                                    '</td>';

                                ifHourEnabled = '{{ ifHourEnabled() }}';
                                if (ifHourEnabled)
                                    hourFormat = 'H:i';
                                else
                                    hourFormat = '';

                                startDateTd = '<td>' +
                                    '<div class="" id="' +
                                    count + '">' +
                                    '<input hourFormat="' + hourFormat +
                                    '" class="form-control reservation_date required new_start_date dateTimePicker" name="products[' +
                                    count + '][checkin_date]" id="' +
                                    count +
                                    '" value="" hourEnabled="' + ifHourEnabled +
                                    '" />' +
                                    '</div>' +
                                    '</td>';

                                if (element.require_end_data) {
                                    endDateTd = '<td>' +
                                        '<div class="" id="">' +
                                        '<input hourFormat="' + hourFormat +
                                        '" class="form-control required reservation_date new_end_date dateTimePicker" id="' +
                                        count +
                                        '" name="products[' +
                                        count +
                                        '][checkout_date]" value="" hourEnabled="' +
                                        ifHourEnabled + '" />' +
                                        '</div>' +
                                        '</td>';
                                } else {
                                    endDateTd = '<td><div>---</div></td>';
                                }

                                feesTd = '<td>' +
                                    '<div class="text-gray-800 fw-bolder resFee' +
                                    count + '" id="' +
                                    count + '">' +
                                    element.formatted_fees +
                                    '</div>' +
                                    '</td>';

                                taxTd = '<td>' +
                                    '<div class="text-gray-800 fw-bolder resTax' +
                                    count + '" id="' +
                                    count + '">' +
                                    element.formatted_tax +
                                    '</div>' +
                                    '</td>';

                                subtotalTd = '<td>' +
                                    '<div class="text-gray-800 fw-bolder reservationsSubtotal' +
                                    count + '" id="' +
                                    count + '">' +
                                    element.formatted_price +
                                    '</div>' +
                                    '</td>';

                                feestotalTd = '<td>' +
                                    '<div class="text-gray-800 fw-bolder reservationsFeesTotal' +
                                    count + '" id="' +
                                    count + '">' +
                                    element.formatted_fees +
                                    '</div>' +
                                    '</td>';

                                taxtotalTd = '<td>' +
                                    '<div class="text-gray-800 fw-bolder reservationsTaxTotal' +
                                    count + '" id="' +
                                    count + '">' +
                                    element.formatted_tax +
                                    '</div>' +
                                    '</td>';

                                totalTd = '<td>' +
                                    '<div class="text-gray-800 fw-bolder reservationsTotal' +
                                    count + '" id="' +
                                    count + '">' +
                                    element.formatted_subtotal +
                                    '</div>' +
                                    '</td>';

                                deleteTd = '<td>' +
                                    '<button type="button" class="btn btn-light btn-sm delete" id="' +
                                    count +
                                    '" onclick=$(".reservationRow' +
                                    count +
                                    '").remove();>' +
                                    'حذف</button>' +
                                    '</td>';

                                $('.reservations_table').append(
                                    '<tr class="dynamic reservationRow' +
                                    count + '">' +

                                    nameTd +
                                    priceTd +
                                    startDateTd +
                                    endDateTd +
                                    feesTd +
                                    taxTd +
                                    subtotalTd +
                                    feestotalTd +
                                    taxtotalTd +
                                    totalTd +
                                    deleteTd +

                                    '</tr>'
                                );
                                count++;
                            });
                            calculateOrderTotal();
                        }
                    });

                }

                $('#addReservation').modal('hide');
                $('.reservations').val(null).trigger('change');
            });

            $(document).on('change', '.old_start_date', function() {
                var id = $(this).attr('id');
                if ($('#differentPriceModal' + id).length) {
                    $('#differentPriceModal' + id).modal('show');
                } else {
                    // in case product require end date
                    if ($('#' + id + '.old_end_date').length) {
                        var orderItem_id = $('#' + id + '.orderItem_id').val();
                        var startDate = $(this).val();
                        var endDate = $('#' + id + '.old_end_date').val();
                        var isOld = $('#' + id + '.updatedPrice').val();

                        $.ajax({
                            url: '{{ route('orders.calculateTotalOldOrderLine') }}',
                            type: 'GET',
                            data: {
                                orderItem_id: orderItem_id,
                                startDate: startDate,
                                endDate: endDate,
                                isOld: isOld
                            },
                            dataType: 'json',
                            success: function(response) {
                                $('.reservationsSubtotal' + id).text(response.subtotal);
                                $('.reservationsFeesTotal' + id).text(response.fees_total);
                                $('.reservationsTaxTotal' + id).text(response.tax_total);
                                $('.reservationsTotal' + id).text(response.total);
                            }
                        }).done(calculateOrderTotal());
                    }
                }
            });

            $(document).on('change', '.new_start_date', function() {
                var id = $(this).attr('id');

                // in case product require end date
                if ($('#' + id + '.new_end_date').length) {
                    var product_id = $('#' + id + '.reservation_id').val();
                    var startDate = $(this).val();
                    var endDate = $('#' + id + '.new_end_date').val();

                    $.ajax({
                        url: '{{ route('orders.calculateTotalNewOrderLine') }}',
                        type: 'GET',
                        data: {
                            product_id: product_id,
                            startDate: startDate,
                            endDate: endDate
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('.reservationsSubtotal' + id).text(response.subtotal);
                            $('.reservationsFeesTotal' + id).text(response.fees_total);
                            $('.reservationsTaxTotal' + id).text(response.tax_total);
                            $('.reservationsTotal' + id).text(response.total);
                        }
                    }).done(calculateOrderTotal());

                }
            });

            $(document).on('change', '.old_end_date', function() {
                var id = $(this).attr('id');
                if ($('#differentPriceModal' + id).length) {
                    $('#differentPriceModal' + id).modal('show');
                } else {
                    var orderItem_id = $('#' + id + '.orderItem_id').val();
                    var startDate = $('#' + id + '.old_start_date').val();
                    var endDate = $(this).val();
                    var isOld = $('#' + id + '.updatedPrice').val();

                    $.ajax({
                        url: '{{ route('orders.calculateTotalOldOrderLine') }}',
                        type: 'GET',
                        data: {
                            orderItem_id: orderItem_id,
                            startDate: startDate,
                            endDate: endDate,
                            isOld: isOld
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('.reservationsSubtotal' + id).text(response.subtotal);
                            $('.reservationsFeesTotal' + id).text(response.fees_total);
                            $('.reservationsTaxTotal' + id).text(response.tax_total);
                            $('.reservationsTotal' + id).text(response.total);
                        }
                    }).done(calculateOrderTotal());
                }
            });

            $(document).on('change', '.new_end_date', function() {
                var id = $(this).attr('id');
                var product_id = $('#' + id + '.reservation_id').val();
                var startDate = $('#' + id + '.new_start_date').val();
                var endDate = $(this).val();

                $.ajax({
                    url: '{{ route('orders.calculateTotalNewOrderLine') }}',
                    type: 'GET',
                    data: {
                        product_id: product_id,
                        startDate: startDate,
                        endDate: endDate
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('.reservationsSubtotal' + id).text(response.subtotal);
                        $('.reservationsFeesTotal' + id).text(response.fees_total);
                        $('.reservationsTaxTotal' + id).text(response.tax_total);
                        $('.reservationsTotal' + id).text(response.total);
                    }
                }).done(calculateOrderTotal());

            });

            $(document).on('hidden.bs.modal', '#addReservation', function() {
                $('.reservations').val(null).trigger('change');
            });

            $('.change_reservation_price').on('click', function() {
                var id = $(this).attr('id');
                var orderItem_id = $('#' + id + '.orderItem_id').val();
                var startDate = $('#' + id + '.old_start_date').val();
                var endDate = $('#' + id + '.old_end_date').val();
                var isOld = 'old';

                if ($('input:radio[name=new_price' + id + ']:checked').hasClass('new'))
                    isOld = 'new';

                var newPrice = $('input:radio[name=new_price' + id + ']:checked').attr(
                    'formatted');
                $('#' + id + '.updatedPrice').val(isOld);
                $('.reservationsPrice' + id).text(newPrice);

                var orderItem_id = $('#' + id + '.orderItem_id').val();

                $.ajax({
                    url: '{{ route('orders.calculateTotalOldOrderLine') }}',
                    type: 'GET',
                    data: {
                        orderItem_id: orderItem_id,
                        startDate: startDate,
                        endDate: endDate,
                        isOld: isOld
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('.reservationsSubtotal' + id).text(response.subtotal);
                        $('.reservationsFeesTotal' + id).text(response.fees_total);
                        $('.reservationsTaxTotal' + id).text(response.tax_total);
                        $('.reservationsTotal' + id).text(response.total);
                    }
                }).done(calculateOrderTotal());

                $('#differentPriceModal' + id).modal('hide');
                $('#differentPriceModal' + id).removeAttr('id');
            });

            function calculateOrderTotal() {
                products = [];

                // fetch data of static items
                $('.products_table tr.static').each(function() {
                    orderItem_id = $(this).find('.orderItem_id').val();
                    quantity = $(this).find('.quantityOldProduct').val();

                    isOld = $(this).find('.updatedPrice').val();

                    products.push({
                        orderItem_id: orderItem_id,
                        quantity: quantity,
                        isOld: isOld
                    });
                });

                $('.reservations_table tr.static').each(function() {

                    orderItem_id = $(this).find('.orderItem_id').val();
                    startDate = $(this).find('.old_start_date').val();
                    endDate = $(this).find('.old_end_date').val();

                    isOld = $(this).find('.updatedPrice').val();

                    products.push({
                        orderItem_id: orderItem_id,
                        startDate: startDate,
                        endDate: endDate,
                        isOld: isOld
                    });

                });

                // fetch data of dynamic items
                $('.products_table tr.dynamic').each(function() {

                    product_id = $(this).find('.product_id').val();
                    quantity = $(this).find('.quantityNewProduct').val();

                    products.push({
                        product_id: product_id,
                        quantity: quantity
                    });
                });

                $('.reservations_table tr.dynamic').each(function() {

                    product_id = $(this).find('.reservation_id').val();
                    startDate = $(this).find('.new_start_date').val();
                    endDate = $(this).find('.new_end_date').val();

                    products.push({
                        product_id: product_id,
                        startDate: startDate,
                        endDate: endDate,
                    });

                });

                $.ajax({
                    url: '{{ route('orders.calculateOrderTotal') }}',
                    type: 'GET',
                    data: {
                        products: products,
                        order_id: '{{ $order->id }}'
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('.orderSubtotal').text(response.subtotal);
                        $('.orderFeesAmount').text(response.fees_total);
                        $('.orderTaxAmount').text(response.tax_total);
                        $('.orderTotal').text(response.total);
                    }
                });
            }

            // update order total when delete order item
            $(document).on('click', '.delete', function() {
                calculateOrderTotal()
            });

            $(document).ajaxStart(function() {
                $(".progress-bar").addClass("progressbar-active");
            });

            $(document).ajaxComplete(function() {
                $(".progress-bar").removeClass("progressbar-active");
            });


            $('body').on('focus', '.dateTimePicker', function() {
                $(this).flatpickr({
                    allowInput: true,
                    enableTime: Boolean($(this).attr('hourEnabled')),
                    dateFormat: "Y-m-d " + $(this).attr('hourFormat'),
                    disableMobile: "true"
                });
            });

            $("#updateForm").submit(function(e) {

                $('.reservation_date').each(function() {

                    if (!$(this).val()) {

                        if ($(this).hasClass('old_start_date') || $(this).hasClass(
                                'new_start_date'))
                            msg = 'يرجى إدخال تاريخ البداية';
                        else
                            msg = 'يرجى إدخال تاريخ النهاية';

                        $(this).addClass('is-invalid');
                        if (!$(this).parent().find('.message').length)
                            $(this).parent().append(
                                '<label class="message" style="color:#f1416c">' + msg +
                                '</label>'
                            );
                        e.preventDefault();
                    } else {
                        $(this).removeClass('is-invalid');
                        $(this).parent().find('.message').remove();
                    }


                });


                /* shouldAlerted = false;
                 $('.start_date').each(function() {
                     var id = $(this).attr('id');
                     var startDate = $(this).val();
                     var endDate = $('#' + id + '.end_date').val();
                     if (startDate > endDate) {
                         $(this).css('border', '1px solid #f1416c');
                         $('#' + id + '.end_date').css('border', '1px solid #f1416c');
                         e.preventDefault();
                         shouldAlerted = true;
                     }
                 });
                 if (shouldAlerted)
                     alert("Invalid Date Range");
                     */
            });

            quill = new Quill('#editor', {
                modules: {
                    toolbar: [
                        [{
                            header: [1, 2, false]
                        }],
                        ['bold', 'italic', 'underline'],
                        ['align', {
                            align: 'center'
                        }],
                        ['align', {
                            align: 'right'
                        }],
                    ]
                },
                theme: 'snow',
            });

            quill.on('text-change', function(delta, oldDelta, source) {
                $("#body").html(quill.root.innerHTML);
            });
        });
    </script>

    @if ($errors->has('products'))
        <script>
            $('#emptyProducts').modal('show');
        </script>
    @endif
@endpush
