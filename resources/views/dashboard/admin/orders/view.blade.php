@extends('dashboard.layouts.master')

@section('content')
    <div class="dashboard">
        <div class="container">

            <div class="card">
                <div class="card-body">

                    <div class="card-body p-2-4">

                        <div class="row">
                            <div class="col-md-12 col-md-offset-1">
                                <div class="panel panel-default panel-table">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col col-xs-6">
                                                <h3 class="panel-title">
                                                    معلومات الطلب ( {{$order->id}})
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-striped table-bordered table-list">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">التاريخ</th>
                                                <th scope="col">المنصة</th>
                                                <th scope="col">المجموع</th>
                                                <th scope="col">حالة الطلب</th>
                                                <th scope="col">تغيير الحالة</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>{{ $order->id }}</td>
                                                <td>{{ $order->created_at->format('Y-m-d g:i A') }}</td>
                                                <td>{{ $order->platform ?? '' }}</td>
                                                <td>{{ price_format($order->total) }}</td>
                                                <td>{{ $order->statusLabel() }}</td>
                                                <td>
                                                    <form action="{{ route('orders.update', $order->id) }}" method="post">
                                                        @csrf
                                                        <select name="status"
                                                                required
                                                                class="form-control @error('status') is-invalid @enderror"
                                                                id="status-select" placeholder="السعر">
                                                            @foreach (\App\Models\Order::getStatuses() as $key => $value)
                                                                <option value="{{ $key }}"
                                                                        @if ($order->status == $key)
                                                                        selected
                                                                    @endif
                                                                >{{ $value }}</option>
                                                            @endforeach
                                                        </select>
                                                    </form>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        {{-- Pagination --}}
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12 col-md-offset-1">
                                <div class="panel panel-default panel-table">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col col-xs-6">
                                                <h3 class="panel-title">معلومات الزبون</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-striped table-bordered table-list">
                                            <thead>
                                            <tr>
                                                <th scope="col">الإسم</th>
                                                <th scope="col">رقم الهاتف</th>
                                                <th scope="col">المحافظة</th>
                                                <th scope="col">العنوان</th>
                                                <th scope="col">ملاحظات اخرى</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>{{ $order->first_name . ' ' .$order->last_name }}</td>
                                                <td>{{ $order->phone_number }}</td>
                                                <td>{{ __('responses.states.'. $order->governorate) }}</td>
                                                <td>{{ $order->address }}</td>
                                                <td>{{ $order->notes ?? '---' }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        {{-- Pagination --}}
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12 col-md-offset-1">
                                <div class="panel panel-default panel-table">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col col-xs-6">
                                                <h3 class="panel-title">المنتجات</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-striped table-bordered table-list">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">أسم المنتج</th>
                                                <th scope="col">سعر المنتج</th>
                                                <th scope="col">الكميه</th>
                                                <th scope="col">معلومات اضافية</th>
                                                <th scope="col">المجموع</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($order->items as $idx => $item)
                                                <tr>
                                                    <td>{{ $idx + 1 }}</td>
                                                    <td>{{ $item->product->name }}</td>
                                                    <td>{{ price_format($item->product->price) }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>
                                                        @if ($item->product->isReservation())
                                                            @if ($item->additional['checkin'] ?? false)
                                                                تاريخ
                                                                الحجز:  {{ \Carbon\Carbon::parse($item->additional['checkin'])->format('Y-m-d g:i A') }}
                                                            @endif

                                                            @if ($item->additional['checkout'] ?? false)
                                                                <br>
                                                                تاريخ
                                                                المغادرة: {{ \Carbon\Carbon::parse($item->additional['checkout'])->format('Y-m-d g:i A') }}
                                                            @endif
                                                        @else
                                                            ---
                                                        @endif
                                                    </td>
                                                    <td>{{ price_format($item->total) }}</td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                        {{-- Pagination --}}
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

@push('scripts')
    <script>
        $('#status-select').change(function() {
            $(this).parent().submit();
        });

    </script>

@endpush
