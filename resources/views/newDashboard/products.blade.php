@extends('newDashboard.layouts.master')
@section('content')
    @php
        $mobileItems = [__('app.products.info.Name'), __('app.products.info.Price'), __('app.actions.actions')];
        $items = [__('app.products.info.product_id'), __('app.products.info.Name'), __('app.products.info.Type'), __('app.products.info.Price'), __('app.products.info.Category'), __('app.products.info.Status'), __('app.actions.actions')];

        $hasData = false;

        if (request('type') || request('category') || request()->filled('active')) {
            $hasData = true;
        }

    @endphp

    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div class="toolbar">
            <!--begin::Page title-->
            <div class="container-fluid">
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                    قائمة المنتجات
                </h1>
            </div>
            <!--end::Page title-->
            <div class='container-fluid d-flex justify-content-end'>
                <form id="searchForm" action="{{ route('products.index') }}" method="get" enctype="multipart/form-data">
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
                                <div class="px-7 py-5 overflow-auto" style="height:350px">
                                    <!--begin::Input group-->
                                    <div class="mb-10">
                                        <!--begin::Label-->
                                        <label class="form-label fw-bold">نوع المنتج</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <div>
                                            <select class="form-select" data-control="select2" data-hide-search="true"
                                                data-placeholder="Select an option" name="type" id="">
                                                <option disabled selected>اختر نوع المنتج</option>
                                                @foreach ($types as $type)
                                                    <option value="{{ $type }}"
                                                        {{ request('type') == $type ? 'selected' : '' }}>
                                                        {{ __('app.products.type.' . $type) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="mb-10">
                                        <!--begin::Label-->
                                        <label class="form-label fw-bold">فئة المنتج</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <div>
                                            <select class="form-select" data-control="select2" data-hide-search="true"
                                                data-placeholder="Select an option" name="category" id="">
                                                <option disabled selected>اختر فئة المنتج</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
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
                                        <label class="form-label fw-bold">حالة المنتج</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <div>
                                            <select class="form-select" data-control="select2" data-hide-search="true"
                                                data-placeholder="Select an option" name="active" id="">
                                                <option disabled selected>اختر حالة المنتج</option>
                                                <option value="1" {{ request('active') == 1 ? 'selected' : '' }}>
                                                    {{ __('app.products.status.active') }}
                                                </option>
                                                <option value="0"
                                                    {{ (request()->filled('active') and request('active') == 0) ? 'selected' : '' }}>
                                                    {{ __('app.products.status.not-active') }}
                                                </option>
                                            </select>
                                        </div>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->

                                    <input type="hidden" name="search" id="inside" />

                                    <!--begin::Actions-->
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-sm btn-primary me-2 apply"
                                            data-kt-menu-dismiss="true">تطبيق</button>
                                        <a href="{{ route('products.index') }}"
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
                    <!--end::filters-->
                </form>
            </div>
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
                                <!--begin::Search-->
                                <div class="d-flex align-items-center position-relative my-1 mb-2">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                    <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546"
                                                height="2" rx="1" transform="rotate(45 17.0365 15.1223)"
                                                fill="currentColor" />
                                            <path
                                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    <input type="text" name="search" value="{{ request('search') }}" id="outside"
                                        class="form-control w-225px ps-14" placeholder="بحث" />
                                </div>
                                <!--end::Search-->
                                <a class="btn btn-primary btn-sm apply mx-4" id="outside_search">بحث</a>
                            </div>
                        </div>
                        <!--end::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                            <a href="{{ route('products.create') }}"
                                class="btn btn-primary add-new-product">{{ __('app.products.add_new_product') }}</a>

                            {{--                        <a data-bs-toggle="modal" data-bs-target="#import" class="btn btn-primary">Import</a> --}}
                                                    <a href="{{ route('products.export') }}" class="btn btn-primary">Export</a> 

                        </div>
                        <!--end::Card toolbar-->

                        <!-- Modal -->
                        <div class="modal fade" id="noCategoriesModal" tabindex="-1" role="dialog"
                            aria-labelledby="noCategoriesModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="noCategoriesModalLabel">تنبيه</h5>
                                    </div>
                                    <div class="modal-body">
                                        لا يمكنك إنشاء منتج الآن
                                        يرجى إنشاء فئة أولا
                                    </div>
                                    <div class="modal-footer">
                                        <a type="submit" class="btn btn-primary" href="{{ route('category.create') }}">
                                            إنشاء فئة جديدة</a>
                                        <a type="button" class="btn btn-secondary close" data-dismiss="modal"
                                            onclick="$('#notes').modal('hide');">إلغاء</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Import Modal -->
                        <div class="modal fade" id="import" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Import Excel File</h5>
                                    </div>

                                    <form action="{{ route('products.import') }}" method="POST" id=""
                                        enctype="multipart/form-data">
                                        @csrf

                                        <div class="modal-body">
                                            <input class="form-control @error('file') is-invalid @enderror" type="file"
                                                id="" name='file' />
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Import</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                                onclick="$('#import').modal('hide');">إلغاء</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

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
                                    @foreach ($products as $product)
                                        <tr class='clickable-row' data-href='{{ route('products.edit', [$product]) }}'>
                                            <!--begin::product ID=-->
                                            <td>
                                                <span class="text-gray-800 fw-bolder">{{ $product->id }}</span>
                                            </td>
                                            <!--end::product ID=-->
                                            <!--begin::product Name=-->
                                            <td>
                                                <span class="text-gray-800 fw-bolder">{{ $product->name }}</span>
                                            </td>
                                            <!--end::product Name=-->
                                            <!--begin::product Type=-->
                                            <td>
                                                <span
                                                    class="text-gray-800 fw-bolder">{{ __('app.products.type.' . $product->type) }}</span>
                                            </td>
                                            <!--end::product Type=-->

                                            <!--begin::Price=-->
                                            <td class="pe-0">
                                                <span
                                                    class="fw-bolder">{{ price_format($product->price, __('app.currency_types.' . $product->currency)) }}</span>
                                            </td>
                                            <!--end::Price=-->
                                            <!--begin::Category=-->
                                            <td class="pe-0">
                                                <div class="badge badge-light-success">
                                                    {{ $product->category->name ?? '---' }}
                                                </div>
                                            </td>
                                            <!--end::Category=-->
                                            <!--begin::Status-->
                                            <td class="pe-0 action">
                                                <form action="{{ route('products.update_status', [$product]) }}"
                                                    method="post">
                                                    @csrf
                                                    <select class="form-select p-2 action" name="active"
                                                        style='width:85%;' onchange="this.form.submit()">
                                                        <option value="1" {{ $product->active ? 'selected' : '' }}>
                                                            {{ __('app.products.status.active') }}
                                                        </option>
                                                        <option value="0"
                                                            {{ $product->active == 0 ? 'selected' : '' }}>
                                                            {{ __('app.products.status.not-active') }}
                                                        </option>
                                                    </select>
                                                </form>
                                            </td>
                                            <!--end::Status-->
                                            <!--begin::Action-->
                                            <td class="action">
                                                <x-action_list :delete="route('products.destroy', $product)" :edit="route('products.edit', [$product])"></x-action_list>
                                            </td>
                                            <!--end::Action-->
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
                            {!! $products->links() !!}
                        </div>
                    </div>
                    <!--begin::Body-->
                </div>
                <!--end::Top Products Table-->
            </div>
        </div>
    </div>
    <!--end::Content-->
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {

            $(function() {
                $('.apply').on('click', function() {
                    $('#inside').val($('#outside').val());
                    $('#searchForm').submit();
                });
            })
            $('#outside_search').click(function() {
                $('#inside').val($('#outside').val());
                $('#searchForm').submit();
            });

            // Handle when Store doesn't have categories
            var categoriesCount = '{{ $categoriesCount }}';
            if (categoriesCount == 0)
                $('.add-new-product').attr('data-toggle', 'modal')
                .attr('data-target', '#noCategoriesModal');
            else {
                $('.add-new-product').removeAttr('data-toggle')
                    .removeAttr('data-target');
            }

            $('.close').on('click', function() {
                $('.add-new-product').removeAttr('data-toggle')
                    .removeAttr('data-target');
            });

            $(".clickable-row").click(function() {
                if (!$(event.target).hasClass('action')) {
                    window.location = $(this).data("href");
                }
            });

        });
    </script>

    @if ($errors->has('file'))
        <script>
            $(document).ready(function() {
                $('#import').modal('show');
            });
        </script>
    @endif
@endpush
