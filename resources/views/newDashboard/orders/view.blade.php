@extends('newDashboard.layouts.master')
@section('content')
    @php
        $order_info = [
            __('app.order.order_info.Date') => $order->created_at->format('Y-m-d g:i a'),
            __('app.order.order_info.Platform') => $order->platform ?? '---',
            __('app.order.order_info.Subtotal') => price_format($order->subtotal, __('app.currency_types.' . $order->currency)),
            __('app.order.order_info.TotalFees') => price_format($order->fees_amount, __('app.currency_types.' . $order->currency)),
            __('app.order.order_info.TotalTax') => price_format($order->tax_amount, __('app.currency_types.' . $order->currency)),
            __('app.order.order_info.Total') => price_format($order->total, __('app.currency_types.' . $order->currency)),
            __('app.order.order_info.Status') => __('app.order.statuses.' . $order->status),
        ];
        
        $notesLabel = __('app.order.customer_info.Notes');
        
        $bot_settings = \App\Models\StoreSettings::where([['store_id', auth()->user()->store->id], ['key', 'bot_settings']])->first();
        
        if ($bot_settings) {
            $bot_settings = $bot_settings->value;
            if (array_key_exists('notes', $bot_settings) && $bot_settings['notes']) {
                $notesLabel = $bot_settings['notes'];
            }
            if (array_key_exists('question1', $bot_settings) && $bot_settings['question1']) {
                $q1Label = $bot_settings['question1'];
            }
            if (array_key_exists('question2', $bot_settings) && $bot_settings['question2']) {
                $q2Label = $bot_settings['question2'];
            }
        }
        
        $customer_info = [
            __('app.order.customer_info.Name') => $order->first_name . ' ' . $order->last_name,
            __('app.order.customer_info.Phone_number') => $order->phone_number ?? '---',
        ];
        
        $steps = getStoreSteps();
        
        if ($steps) {
            if (array_key_exists('governorate', $steps)) {
                $customer_info[__('app.order.customer_info.Governorate')] = $order->governorate ?: '---';
            }
        
            if (array_key_exists('address', $steps)) {
                $customer_info[__('app.order.customer_info.Address')] = $order->address ?: '---';
            }
        
            if (array_key_exists('notes', $steps)) {
                $customer_info[$notesLabel] = $order->notes ?: '---';
            }
            if (array_key_exists('question1', $steps)) {
                $customer_info[$q1Label] = $order->additional_question1 ?: '---';
            }
            if (array_key_exists('question2', $steps)) {
                $customer_info[$q2Label] = $order->additional_question2 ?: '---';
            }
        }
        
        if (
            auth()
                ->user()
                ->hasRole('super-admin')
        ) {
            $subscription = \App\Models\Subscription::where('order_id', $order->id)->first();
        
            if ($subscription) {
                $store = \App\Models\Store::query()
                    ->where('id', $subscription->store_id)
                    ->first();
        
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
        
        // Back Button
        $previousRouteName = app('router')
            ->getRoutes()
            ->match(app('request')->create(url()->previous()))
            ->getName();
        
        if ($previousRouteName == 'reservations.index') {
            $backButtonRoute = url()->previous();
            $backButtonLabel = 'العودة إلى الحجوزات';
        } elseif ($previousRouteName == 'orders.index') {
            $backButtonRoute = url()->previous();
            $backButtonLabel = 'العودة إلى الطلبات';
        } elseif ($previousRouteName == 'orders.subscriptions.index') {
            $backButtonRoute = url()->previous();
            $backButtonLabel = 'العودة إلى الاشتراكات';
        } else {
            // if we come from editing order page
            if ($order->isSubscription()) {
                $backButtonRoute = route('orders.subscriptions.index');
                $backButtonLabel = 'العودة إلى الاشتراكات';
            } else {
                $backButtonRoute = route('orders.index');
                $backButtonLabel = 'العودة إلى الطلبات';
            }
        }
        
        $currentRoute = \Illuminate\Support\Facades\Route::currentRouteName();
        
        if (str_contains($currentRoute, 'subscriptions')) {
            $editRoute = 'orders.subscriptions.edit';
        } else {
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
            <div class="container-fluid">
                <h4> {{ __('app.order.order_info.order_info') }} :</h4>
            </div>
            <!--begin::Container-->
            <div class="container-fluid d-flex justify-content-end">
                <a href="{{ route($editRoute, [$order]) }}" class="btn btn-sm btn-primary">تعديل الطلب</a>
            </div>
            <!--end::Container-->
        </div>
        <!--end::Toolbar-->

        <div class="app-toolbar pb-4">
            <div class="app-container container-xxl d-flex flex-stack ">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
                    <a href="{{ $backButtonRoute }}" class="position-absolute pb-2 pt-2 text-gray-600 text-hover-primary">
                        {{ $backButtonLabel }}
                    </a>
                </div>
            </div>
        </div>

        <!--begin::Post-->
        <div class="container-xxl">

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
                                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                            <th class="min-w-100px">{{ $label }}</th>
                                            <td class="text-gray-800 fw-bolder"> {{ $item }}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-100px">{{ __('app.order.order_info.Change_status') }}</th>
                                        <td style="">
                                            <form action="{{ route('orders.update', $order) }}" method="post">
                                                @csrf
                                                <select class="form-select w-200px" data-control="select2"
                                                    data-hide-search="true" name="status" onchange="this.form.submit()">
                                                    <option value="" disabled>أختر</option>
                                                    @foreach (\App\Models\Order::getStatuses() as $statusCode => $status)
                                                        <option value="{{ $statusCode }}"
                                                            {{ $order->status == $statusCode ? 'selected' : '' }}>
                                                            {{ $status }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-100px">{{ trans_choice('app.payment_methods', 1) }}</th>
                                        <td class="text-gray-800 fw-bolder">
                                            {{ __('payment_methods.payment_methods.' . $order->payment_method) }}</td>
                                    </tr>

                                    @php
                                    $methods = [];

                                    foreach(get_enabled_payment_methods() as $key => $className){

                                        $obj  = new $className();
                                        
                                        if ($className->needsRedirect()) {
                                    
                                            $methods [] = $obj->getKey();
                                        }

                                    }
                                    @endphp


                                    @if($order->status == \App\Models\Order::PENDING && $methods != null)
                                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                        <td class="text-gray-800 fw-bolder">
                                        <form method="POST" action="{{ route('orders.generate_payment_link',$order) }}">
                                            @csrf
                                        <button class="btn btn-success" > إنشاء رابط للدفع</button>    
                                        </form>   
                                    </td>
                                    </tr>
                                    @endif
                                        
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
                <div class="col-xl-6 mb-4 mb-xl-1">
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
                                    <!--begin::Table row-->
                                    @foreach ($customer_info as $label => $item)
                                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                            <th class="min-w-100px">{{ $label }}</th>
                                            @if ($label == __('app.order.customer_info.Phone_number'))
                                                @php
                                                    $phoneNumber = translateNumber($item);
                                                    $agreed = is_standard($phoneNumber);                    
                                                @endphp
                                            @endif
                                            <td class="text-gray-800 fw-bolder"> {{ $item }}
                                            @if ($label == __('app.order.customer_info.Phone_number'))
                                                @if ($agreed == true) 
                                                    <a href = "https://wa.me/+963{{$phoneNumber}}" target="_blank"><i class="lab fs-3 la-whatsapp" ></i></a>
                                                @endif
                                            @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                            </table>
                        </div>
                        <!--end::items info-->
                    </div>
                </div>
            </div>

            <!--begin::Products Info-->
            <div class="row gy-5 g-xl-6 {{ !$productsItems ? 'd-none' : '' }}">
                <div class="col-xl-12 mb-4 mb-xl-6">
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header py-5 gap-2 gap-md-5">
                            <div class="order_info_label">
                                <h2>{{ __('app.order.products_info.products_info') }}</h2>
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
                                    <tbody class="fw-bold text-gray-600">
                                        @foreach ($productsItems as $item)
                                            @php
                                                $product = $item
                                                    ->product()
                                                    ->withTrashed()
                                                    ->first();
                                            @endphp
                                            <tr>
                                                <!-- Product Name -->
                                                <td>
                                                    <div class="text-gray-800 fw-bolder">
                                                        {{ $product->name }}
                                                    </div>
                                                </td>
                                                <!-- Product Price -->
                                                <td>
                                                    <div class="text-gray-800 fw-bolder">
                                                        {{ price_format($item->price, __('app.currency_types.' . $product->currency)) }}
                                                    </div>
                                                </td>
                                                <!-- Product Fees -->
                                                <td>
                                                    <div class="text-gray-800 fw-bolder">
                                                        {{ price_format($item->fees_amount, __('app.currency_types.' . $product->currency)) }}
                                                    </div>
                                                </td>
                                                <!-- Product Tax -->
                                                <td>
                                                    <div class="text-gray-800 fw-bolder">
                                                        {{ price_format($item->tax_amount, __('app.currency_types.' . $product->currency)) }}
                                                    </div>
                                                </td>
                                                <!-- Quantity -->
                                                <td>
                                                    <div class="text-gray-800 fw-bolder">{{ $item->quantity }}</div>
                                                </td>
                                                <!-- Subtotal -->
                                                <td>
                                                    <div class="text-gray-800 fw-bolder">
                                                        {{ price_format($item->subtotal, __('app.currency_types.' . $product->currency)) }}
                                                    </div>
                                                </td>
                                                <!-- Subtotal Fees -->
                                                <td>
                                                    <div class="text-gray-800 fw-bolder">
                                                        {{ price_format($item->fees_amount_subtotal, __('app.currency_types.' . $product->currency)) }}
                                                    </div>
                                                </td>
                                                <!-- Subtotal Tax -->
                                                <td>
                                                    <div class="text-gray-800 fw-bolder">
                                                        {{ price_format($item->tax_amount_subtotal, __('app.currency_types.' . $product->currency)) }}
                                                    </div>
                                                </td>
                                                <!-- Total -->
                                                <td>
                                                    <div class="text-gray-800 fw-bolder">
                                                        {{ price_format($item->total, __('app.currency_types.' . $product->currency)) }}
                                                    </div>
                                                </td>
                                            </tr>
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
            <div class="row gy-5 g-xl-6 {{ !$reservationsItems ? 'd-none' : '' }}">
                <div class="col-xl-12 mb-4 mb-xl-6">
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header py-5 gap-2 gap-md-5">
                            <div class="order_info_label">
                                <h2>{{ __('app.order.products_info.reservations_info') }}</h2>
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
                                    <tbody class="fw-bold text-gray-600">
                                        @foreach ($reservationsItems as $item)
                                            @php
                                                $product = $item
                                                    ->product()
                                                    ->withTrashed()
                                                    ->first();
                                            @endphp
                                            <tr>
                                                <!-- Product Name -->
                                                <td>
                                                    <div class="text-gray-800 fw-bolder">
                                                        {{ $product->name }}
                                                    </div>
                                                </td>
                                                <!-- Product Price -->
                                                <td>
                                                    <div class="text-gray-800 fw-bolder">
                                                        {{ price_format($item->price, __('app.currency_types.' . $product->currency)) }}
                                                    </div>
                                                </td>

                                                @php
                                                    $startDate = '';
                                                    $endDate = '';
                                                    if ($product->type == 'reservation') {
                                                        $startDate = Carbon\Carbon::parse($item->additional['checkin'])
                                                            ->timezone('Asia/Damascus')
                                                            ->format('Y-m-d g:i a');
                                                    
                                                        if ($product->require_end_date && ($item->additional['checkout'] ?? null)) {
                                                            $endDate = Carbon\Carbon::parse($item->additional['checkout'])
                                                                ->timezone('Asia/Damascus')
                                                                ->format('Y-m-d g:i a');
                                                        }
                                                    }
                                                @endphp

                                                <!-- Reservation Start Date -->
                                                <td>
                                                    <div class="text-gray-800 fw-bolder">
                                                        {{ $startDate ?: '---' }}
                                                    </div>
                                                </td>
                                                <!-- Reservation End Date -->
                                                <td>
                                                    <div class="text-gray-800 fw-bolder">
                                                        {{ $endDate ?: '---' }}
                                                    </div>
                                                </td>
                                                <!-- Fees -->
                                                <td>
                                                    <div class="text-gray-800 fw-bolder">
                                                        {{ price_format($item->fees_amount, __('app.currency_types.' . $product->currency)) }}
                                                    </div>
                                                </td>
                                                <!-- Tax -->
                                                <td>
                                                    <div class="text-gray-800 fw-bolder">
                                                        {{ price_format($item->tax_amount, __('app.currency_types.' . $product->currency)) }}
                                                    </div>
                                                </td>
                                                <!-- Subtotal -->
                                                <td>
                                                    <div class="text-gray-800 fw-bolder">
                                                        {{ price_format($item->subtotal, __('app.currency_types.' . $product->currency)) }}
                                                    </div>
                                                </td>
                                                <!-- Subtotal Fees -->
                                                <td>
                                                    <div class="text-gray-800 fw-bolder">
                                                        {{ price_format($item->fees_amount_subtotal, __('app.currency_types.' . $product->currency)) }}
                                                    </div>
                                                </td>
                                                <!-- Subtotal Tax -->
                                                <td>
                                                    <div class="text-gray-800 fw-bolder">
                                                        {{ price_format($item->tax_amount_subtotal, __('app.currency_types.' . $product->currency)) }}
                                                    </div>
                                                </td>
                                                <!-- Total -->
                                                <td>
                                                    <div class="text-gray-800 fw-bolder">
                                                        {{ price_format($item->total, __('app.currency_types.' . $product->currency)) }}
                                                    </div>
                                                </td>
                                            </tr>
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

            <!--begin::Notes-->
            <div class="row">
                <div class="col-xl-12">
                    <x-notes-component :route="route('orders.note.store', $order)" :notes="$notes"></x-notes-component>
                </div>
            </div>
        </div>
        <!--end::Content-->
    @endsection

    @push('scripts')
        <script>
            $(document).ready(function() {
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

       
            @if (Session::has('payment_link'))
            <script>
                Swal.fire({
                    title: "تم توليد رابط الدفع",
                    text: " {!! Session::get('payment_link') !!} ",
                    icon: 'success',
                    confirmButtonText: 'انسخ الرابط',
                }).then((result) => {
                    if (result.isConfirmed) {
                        navigator.clipboard.writeText('{!! Session::get('payment_link') !!}')
                            .then(() => {
                                Swal.fire('Copied!', 'تم نسخ الرابط بنجاح', 'success');
                            })
                            .catch((err) => {
                                Swal.fire('Oops!', `فشل نسخ الرابط: ${err}`, 'error');
                            });
                    }
                });
            </script>
        @endif
    @endpush
