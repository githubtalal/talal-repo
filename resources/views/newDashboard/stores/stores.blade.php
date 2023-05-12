@extends('newDashboard.layouts.master')
@section('content')
    @php
        $items = [__('app.stores-info.id'), __('app.stores-info.name'), __('app.stores-info.services'), __('app.stores-info.status'), __('app.stores-info.expiry_date'), __('app.actions.update')];
    @endphp
    <!--begin::Toolbar-->
    <div class="toolbar">
        <!--begin::Container-->
        <div class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                    قائمة المتاجر
                </h1>
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Toolbar-->

    <!--Tables-->
    <div class="row gy-5 g-xl-12 mt-5">

        <!--begin::Stores Table-->
        <div class="col-xl-12 mb-xl-2">
            <div class="card card-xl-stretch mb-xl-8">
                <!--begin::Header-->
                <div class="card-header border-0 pt-5">

                </div>
                <!--end::header-->

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
                                @foreach ($stores as $store)
                                    <tr>
                                        <!--begin::store ID=-->
                                        <td>
                                            <a href="{{ route('stores.edit_subscribtion_setting', [$store]) }}"
                                                class="text-gray-800 fw-bolder">{{ $store->id }}</a>
                                        </td>
                                        <!--end::store ID=-->
                                        <!--begin::store Name=-->
                                        <td>
                                            <a href="{{ route('stores.edit_subscribtion_setting', [$store]) }}"
                                                class="text-gray-800 fw-bolder">{{ $store->store_name }}</a>
                                        </td>
                                        <!--end::store Name=-->
                                        <!--begin::store services=-->
                                        <td>
                                            <a href="{{ route('stores.edit_subscribtion_setting', [$store]) }}"
                                                class="text-gray-800 fw-bolder">
                                                @if (count($store->services))
                                                    @foreach ($store->services as $service)
                                                        {{ __('app.features.' . $service) . ' ، ' }}
                                                    @endforeach
                                                @else
                                                    -----
                                                @endif
                                            </a>
                                        </td>
                                        <!--end::store services-->
                                        <!--begin::store Status-->
                                        <td class="pe-0">
                                            <a href="{{ route('stores.edit_subscribtion_setting', [$store]) }}"
                                                class="text-gray-800 fw-bolder">{{ $store->is_active == 1 ? __('app.stores-info.active') : __('app.stores-info.not-active') }}</a>
                                        </td>
                                        <!--end::store Status-->
                                        <!--begin::store expiry date=-->
                                        <td>
                                            <a href="{{ route('stores.edit_subscribtion_setting', [$store]) }}"
                                                class="text-gray-800 fw-bolder">{{ $store->expires_at ?? '----' }}</a>
                                        </td>
                                        <!--end::store expiry date=-->
                                        <!--begin::Action-->
                                        <td>
                                            <!--edit icon-->
                                            <a href="{{ route('stores.edit_subscribtion_setting', [$store]) }}"
                                                class="btn btn-icon btn-active-light-primary w-30px h-30px m-2">
                                                <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Edit">
                                                    <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none">
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
                                        </td>
                                        <!--end::Action-->
                                    </tr>
                                @endforeach
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                        <div>
                            {!! $stores->links() !!}
                        </div>
                    </div>
                    <!--end::Table container-->
                </div>
                <!--begin::Body-->
            </div>
        </div>
        <!--end::Stores Table-->
    </div>
@endsection
