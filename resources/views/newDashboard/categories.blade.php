@extends('newDashboard.layouts.master')
@section('content')
    @php
        $items = [__('app.categories.info.category_id'), __('app.categories.info.Name'), __('app.actions.actions')];
    @endphp
    <x-ecommerce :name="trans_choice('app.categories.categories', 2)" :items="$items">
        <x-slot name="header">

            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search Form-->
                <form id="searchForm" action="{{ route('category.index') }}" method="get" enctype="multipart/form-data">
                    <div class="d-flex align-items-center position-relative my-1 mb-2">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                        <span class="svg-icon svg-icon-1 position-absolute ms-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                    rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                <path
                                    d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                    fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <input type="text" name="search" class="form-control w-250px ps-14" placeholder="بحث" />
                        <a class="btn btn-primary btn-sm apply mx-4">بحث</a>
                    </div>
                </form>
                <!--end::Search form-->
            </div>
            <!--end::Card title-->

            <!--begin::Card Toolbar-->
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                <a href="{{ route('category.create') }}"
                    class="btn btn-primary">{{ __('app.categories.add_new_category') }}</a>
                {{--                <a data-bs-toggle="modal" data-bs-target="#import" class="btn btn-primary importButton">Import</a> --}}
                                <a href="{{ route('category.export') }}" class="btn btn-primary">Export</a> 

                <div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Import Excel File</h5>
                            </div>

                            <form action="{{ route('category.import') }}" method="POST" id="importForm"
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
            <!--end::Card Toolbar-->

        </x-slot>
        <x-slot name="data">
            @foreach ($categories as $category)
                <?php
                $route = route('category.edit', [$category]);
                ?>
                <!--begin::Table row-->
                <tr class='clickable-row' data-href='{{ $route }}'>
                    <!--begin::category ID=-->
                    <td>
                        <span class="text-gray-800 fw-bolder">{{ $category->id }}</span>
                    </td>
                    <!--end::category ID=-->
                    <!--begin::category Name=-->
                    <td>
                        <span class="text-gray-800 fw-bolder">{{ $category->name }}</span>
                    </td>
                    <!--end::category Name=-->
                    <!--begin::Action=-->
                    <td class="action">
                        <a href="#" class="btn btn-sm btn-light btn-active-light-primary action"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">{{ __('app.actions.actions') }}
                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                            <span class="svg-icon svg-icon-5 m-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
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
                                <a href="{{ route('category.edit', [$category]) }}"
                                    class="menu-link px-3">{{ __('app.actions.update') }}</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3 action">
                                <a class="menu-link px-3 action" data-bs-toggle="modal"
                                    data-bs-target="#cat{{ $category->id }}">{{ __('app.actions.delete') }}</a>
                            </div>
                            <!--end::Menu item-->
                        </div>
                        <!--end::Menu-->
                    </td>
                    <!--end::Action=-->
                </tr>
                <!--end::Table row-->

                <!--Delete Modal-->
                <div class="modal fade" id="cat{{ $category->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">تنبيه !</h5>
                            </div>
                            <div class="modal-body">
                                <label class="form-label mb-6">
                                    سيؤدي هذا إلى حذف المنتجات الخاصة بهذه الفئة
                                </label>
                            </div>
                            <div class="modal-footer">
                                <a href="{{ route('category.destroy', [$category]) }}" class="btn btn-primary">موافق</a>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                    onclick="$('#cat{{ $category->id }}').modal('hide');">إلغاء</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </x-slot>
        <x-slot name="links">
            {!! $categories->links() !!}
        </x-slot>
    </x-ecommerce>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(function() {
                $('.apply').on('click', function() {
                    $('#searchForm').submit();
                });
            })

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
