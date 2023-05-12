<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div class="toolbar">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Title-->
            <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">{{ $name }}
            </h1>
            <!--end::Title-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-xxl">
            {{ $slot }}
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->
