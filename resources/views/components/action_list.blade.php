<a href="#" class="btn btn-sm btn-light btn-active-light-primary action" data-kt-menu-trigger="click"
    data-kt-menu-placement="bottom-end">{{ __('app.actions.actions') }}
    <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
    <span class="svg-icon svg-icon-5 m-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
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
        <a href="{{ $edit }}" class="menu-link px-3">{{ __('app.actions.update') }}</a>
    </div>
    <!--end::Menu item-->
    <!--begin::Menu item-->
    <div class="menu-item px-3">
        <a href="{{ $delete }}" class="menu-link px-3">{{ __('app.actions.delete') }}</a>
    </div>
    <!--end::Menu item-->
</div>
<!--end::Menu-->
