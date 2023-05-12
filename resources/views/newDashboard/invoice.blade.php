<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ public_path('assets\css\style.bundle.rtl.css') }}" rel="stylesheet" type="text/css" media="all" />

    <style>
        * {
            font-family: DejaVu Sans
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        body {
            direction: rtl;
            background-color: white;
            padding: 50px;
        }

        .sale-summary {
            margin-top: 40px;
            margin-right: 400px;
            float: right;
            text-align: right;
        }

        .sale-summary tr td {
            padding: 3px 5px;
        }

        .sale-summary tr.bold {
            font-weight: normal;
        }

        .invoice_info {
            margin-top: 10px;
            margin-bottom: 40px;
        }

        .fatoraLabel {
            font-size: 20px;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .table_info {
            margin-bottom: 30px;
        }

        .label_info {
            font-weight: normal;
            font-size: 14px;
            color: rgb(8, 79, 112)
        }

        .table_info td {
            width: 50%;
        }

        .items td {
            padding-top: 7px;
            padding-bottom: 4px;
        }

        .table_top_line {
            border-top: rgb(8, 79, 112) 2px solid;
        }
    </style>

</head>

<body>

    <!--Invoice Label-->
    <div class="fatoraLabel">
        :{{ $data['invoiceId'] }} فاتورة
    </div>

    <!--Invoice Info-->
    <div class="invoice_info">
        <div class="row table_info">
            <table class="text-right">
                <thead>
                    <th class="label_info">تاريخ انتهاء الدفع</th>
                    <th class="label_info">تاريخ الفاتورة</th>

                </thead>

                <tbody>
                    <tr>
                        <td>{{ $data['date'] }}</td>
                        <td>{{ $data['date'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="row table_info">
            <table class="text-right">
                <thead>
                    <th class="label_info">معلومات الزبون</th>
                    <th class="label_info">معلومات المتجر</th>
                </thead>

                <tbody>
                    <tr>
                        <td>{{ $data['customer_name'] }}
                        </td>
                        <td>{{ $data['store_name'] }}</td>
                    </tr>
                    <tr>
                        <td>{{ $data['customer_phone_number'] }}</td>
                        <td>{{ $data['store_type'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <!--Items-->
    <div class="row">
        <table class="table_top_line">
            <thead>
                <tr class="border-bottom">
                    <th class="text-center label_info p-1">الإجمالي</th>
                    <th class="text-center label_info p-1">السعر</th>
                    <th class="text-center label_info p-1">الكمية</th>
                    <th class="text-center label_info p-1">المنتج</th>
                </tr>
            </thead>
            <tbody class='border-bottom'>
                @foreach ($data['products'] as $product)
                    @php
                        $total = explode('ليرة سورية', price_format($product->total))[0];
                        $price = explode('ليرة سورية', price_format($product->product->price))[0];
                    @endphp

                    <tr class="border-bottom items">
                        <td class="text-center">{{ $total }}</td>
                        <td class="text-center">{{ $price }}</td>
                        <td class="text-center">{{ $product->quantity }}</td>
                        <td class="text-center">{{ $product->product->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @php
        $subTotal = price_format($data['subtotal']);
        $arr = explode('ليرة سورية', $subTotal);
        $subTotal = $arr[0];
    @endphp

    <!--Summary-->
    <table class="sale-summary">
        <tr>
            <td>ليرة سورية</td>
            <td>{{ $subTotal }}</td>
            <td>-</td>
            <td>المجموع الجزئي</td>
        </tr>

        <tr>
            <td>ليرة سورية</td>
            <td>0.00</td>
            <td>-</td>
            <td>الضريبة</td>
        </tr>

        <tr>
            <td>ليرة سورية</td>
            <td>0.00</td>
            <td>-</td>
            <td>الحسم</td>
        </tr>

        <tr>
            <td colspan="4">
                <hr>
            </td>
        </tr>

        <tr>
            <td>ليرة سورية</td>
            <td>{{ $subTotal }}</td>
            <td>-</td>
            <td>المجموع الكلي</td>
        </tr>
    </table>

</body>

</html>
