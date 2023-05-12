@extends('dashboard.layouts.master')

@section('content')
    <div class="dashboard">
        <div class="container">
            <div class="card">
                <div class="card-body p-2-4">
                    <div class="row">
                        <div class="col-md-12 col-md-offset-1">
                            <div class="panel panel-default panel-table">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col col-xs-6">
                                            <h3 class="panel-title">الطلبات</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered table-list">
                                        <thead>
                                        <tr>
                                            <th scope="col">رقم الطلب</th>
                                            <th scope="col">التاريخ</th>
                                            <th scope="col">الإسم</th>
                                            <th scope="col">رقم الهاتف</th>
                                            <th scope="col">المحافظة</th>
                                            <th scope="col">مجموع الطلب</th>
                                            <th scope="col">حالة الطلب</th>
                                            <th scope="col"><em class="fa fa-cog"
                                                                style="display: flex; justify-content: center"></em>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($orders as $order)
                                            <tr>
                                                <td>{{ $order->id }}</td>
                                                <td>{{ $order->first_name . ' ' .$order->last_name }}</td>
                                                <td>{{ $order->created_at->format('Y-m-d g:i A') }}</td>
                                                <td>{{ $order->phone_number }}</td>
                                                <td>{{ __('responses.states.'. $order->governorate) }}</td>
                                                <td>{{ price_format($order->total) }}</td>
                                                <td>{{ $order->statusLabel() }}</td>
                                                <td align="center">
                                                    <a class="btn btn-default"
                                                       href="{{route('orders.view',['order' => $order->id])}}"><em
                                                            class="fa fa-eye"></em></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    {{-- Pagination --}}
                                    <div class="d-flex justify-content-center">
                                        {!! $orders->links() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
