@extends('newDashboard.layouts.master')
@section('content')
    

    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div class="toolbar">
            <!--begin::Page title-->
            <div class="container-fluid">
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                    رسائل الترويج
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
                                <!--begin::Search-->
                                <form class="d-flex align-items-center position-relative my-1 mb-2" id="searchForm" action="{{ route('bot.telegram.promotion_messages') }}" method="get" enctype="multipart/form-data">
                
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
                                        class="form-control w-225px ps-14" placeholder="بحث باسم المتجر" />
                                <!--end::Search-->
                                <button class="btn btn-primary btn-sm apply mx-4" type="submit" id="outside_search">بحث</button>
                                </form>
                            </div>
                        </div>
                        <!--end::Card title-->
              

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
                                            <th class="min-w-150px">المتجر</th>
                                            <th class="min-w-150px">معرف قناة تلغرام</th>
                                            <th class="min-w-150px">نص الرسالة</th>
                                            <th class="min-w-150px">الصورة</th>
                                            <th class="min-w-150px">تاريخ الإرسال</th>
                                    </tr>
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody>
                                        @foreach ($promotion_messages as $message)
                                        <tr>
                                        
                                            <td>
                                                <span class="text-gray-800 fw-bolder">{{ $message->store->name }}</span>
                                            </td>
                                        
                                            <td>
                                                <span class="text-gray-800 fw-bolder">{{ $message->channel_id }}</span>
                                            </td>
                                        
                                            <td>
                                                <span
                                                    class="text-gray-800 fw-bolder">{{ $message->message_text }}</span>
                                            </td>
                                        
                                            <td class="pe-0">
                                                @if(isset($message->image))
                                                <img class="rounded" src="{{ asset($message->image) }}" alt="صورة" width="60px" height="60px">
                                                @endif
                                            </td>
                                        
                                            <td>
                                                <span
                                                    class="text-gray-800 fw-bolder">{{ $message->created_at }}</span>
                                            </td>

                                        </tr>
                                        @endforeach
                                        <!--begin::Table row-->
                                        <!--end::Table row-->
                                </tbody>
                                <!--end::Table body-->
                            </table>
                            <!--end::Table-->
                        </div>
                        <!--end::Table container-->
                        <div>
                            {!! $promotion_messages->links() !!}
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

@endpush
