@extends('newDashboard.layouts.master')
@section('content')
    

    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div class="toolbar">
            <!--begin::Page title-->
            <div class="container-fluid">
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                    {{ __('app.notification.notifications') }}
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
                                            <th class="min-w-150px">{{ __('app.notification.text_message') }}</th>
                                            <th class="min-w-150px">{{ __('app.notification.created_at') }}</th>
                                            <th class="min-w-150px"></th>
                                    </tr>
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody>
                                        @foreach ($notifications as $notification)
                                        <tr>
                                        
                                            <td>
                                                <span class="text-gray-800 fw-bolder">{{ json_decode($notification->data)->text }}</span>
                                            </td>
                                        
                                            <td>
                                                <span
                                                    class="text-gray-800 fw-bolder">{{ $notification->created_at }}</span>
                                            </td>

                                            <td>
                                                <a href="{{ route('notifications.show_notification',$notification->id) }}"><i class="las fs-1 la-external-link-alt"></i></a>
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
                            {!! $notifications->links() !!}
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
