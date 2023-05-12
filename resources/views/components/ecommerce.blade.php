<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div class="toolbar">
        <!--begin::Container-->
        <div class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                    {{ __('app.listing', ['name' => $name]) }}
                </h1>
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Table-->
    <x-table_info :items="$items">
        <x-slot name="header">
            {{ $header }}
        </x-slot>
        <x-slot name="data">{{ $data }}</x-slot>
        <x-slot name="links">{{ $links }}</x-slot>
    </x-table_info>
    <!--end::Table-->
</div>
<!--end::Content-->
