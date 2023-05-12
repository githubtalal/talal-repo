@extends('newDashboard.layouts.master')
@section('content')
    @php
        $items = [__('app.customers-info.name'), __('app.customers-info.phone-number')];
    @endphp
    <x-ecommerce :name="__('app.customers')" :items="$items">

        <x-slot name="header">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Form-->
                <form id="searchForm" action="{{ route('customers.index') }}" method="get" enctype="multipart/form-data">
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
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control w-250px ps-14" placeholder="بحث" />
                        <a class="btn btn-sm btn-primary apply mx-4">بحث</a>
                    </div>
                </form>
                <!--end::form-->
            </div>
            <!--end::Card title-->
            <a href="{{ route('customers.export') }}" class="btn btn-primary" style="height: 46px; margin-top:10px">Export</a>
        </x-slot>

        <x-slot name="data">
            @foreach ($customers as $customer)
                <!--begin::Table row-->
                <tr>
                    <td>
                        <span class="text-gray-800 fw-bolder">{{ $customer->name }}</span>
                    </td>
                    <td>
                        <span class="text-gray-800 fw-bolder">{{ $customer->phone_number }}</span>
                    </td>
                </tr>
                <!--end::Table row-->
            @endforeach
        </x-slot>

        <x-slot name="links">
            {!! $customers->links() !!}
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
        });
    </script>
@endpush
