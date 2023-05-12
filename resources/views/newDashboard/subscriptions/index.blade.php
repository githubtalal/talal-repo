@extends('newDashboard.layouts.master')
@section('content')
    @php
        $items = [__('app.stores-info.subscriptionId'), __('app.stores-info.services'), __('app.stores-info.payment_method'), __('app.stores-info.approved_at'), __('app.stores-info.status')];
    @endphp
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div class="toolbar">
            <!--begin::Page title-->
            <div class="container-fluid">
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                    قائمة الاشتراكات
                </h1>
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar-->
        <!--Tables-->
        <div class="app-content flex-column-fluid ">
            <div class="app-container container-xxl">
                <div class="card card-flush">
                    <!--begin::Header-->
                    <div class="card-header border-0 pt-5">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <div class="d-flex align-items-center">
                            </div>
                        </div>
                        <!--end::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                            <a href="{{ route('resubscription.create') }}" class="btn btn-primary add-new-product">إعادة
                                الاشتراك</a>
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::header-->
                    <!--begin::Body-->
                    <div class="card-body py-3">
                        <!--begin::Table container-->
                        @if ($permissions)
                            <div class="col-lg-4 mb-4">
                                <label class="text-gray-800 fw-bolder fs-4 mb-2">تاريخ انتهاء الاشتراك:</label>
                                <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2">
                                    <tbody>
                                        @foreach ($permissions as $permission)
                                            <tr>
                                                <td class="text-gray-800 fw-bolder">
                                                    {{ __('app.features.' . $permission['name']) }}</td>
                                                <td class="text-gray-800 fw-bolder">
                                                    {{ Carbon\Carbon::parse($permission['expires_at'])->format('Y-m-d') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
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
                                    @foreach ($storeSubscriptions as $subscription)
                                        <tr>
                                            <td>
                                                <span class="text-gray-800 fw-bolder">{{ $subscription['id'] }}</span>
                                            </td>
                                            <td>
                                                @foreach ($subscription['services'] as $service)
                                                    <div class="text-gray-800 fw-bolder">
                                                        {{ __('app.features.' . $service['name']) }}
                                                    </div>
                                                @endforeach
                                            </td>
                                            <td>
                                                <span
                                                    class="text-gray-800 fw-bolder">{{ __('payment_methods.payment_methods.' . $subscription['payment_method']) }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="text-gray-800 fw-bolder">{{ $subscription['approved_at'] ? Carbon\Carbon::parse($subscription['approved_at'])->format('Y-m-d g:i a') : '---' }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="text-gray-800 fw-bolder">{{ __('app.order.statuses.' . $subscription['status']) }}</span>
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
                </div>
                <!--end::Table-->
            </div>
        </div>
    </div>
    <!--end::Content-->
@endsection
