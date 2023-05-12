<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Amiri', serif; }
        tr {
            text-align: center;
        }

        /*html {*/
        /*'Noto Sans', sans-serif;            line-height: 1.15;*/
        /*}*/
    </style>
</head>
<body>

<table style="width:100%;height:100%;" border="">
    <tr>
        <td>{{ $claim->code }}</td>
        <td>رقم الشكوى</td>
    </tr>
    @if (auth()->user()->role === 1)
        <tr>
            <td>{{ $claim->name }}</td>
            <td>الأسم</td>
        </tr>
        <tr>
            <td>{{ $claim->phone_number }}</td>
            <td>رقم الجوال</td>
        </tr>
    @endif
    @php
        $dep = \Illuminate\Support\Facades\DB::table('district')->find($claim->department);
        $gov = \Illuminate\Support\Facades\DB::table('city')->find($claim->governorate);
    @endphp
    <tr>
        <td>{{ $gov->name }}</td>
        <td>المحافظة</td>
    </tr>
    <tr>
        <td>{{ $dep->name }}</td>
        <td>الدائرة</td>
    </tr>
    <tr>
        <td>{{__("messages.$claim->claim_type")}}</td>
        <td>نوع الشكوى</td>
    </tr>
    @if(auth()->user()->role === 1)
        <tr>
            <td>{{ $claim->address }}</td>
            <td>العنوان</td>
        </tr>
    @endif
    <tr>
        <td>{{ $claim->claim }}</td>
        <td>الشكوى</td>
    </tr>
    <tr>
        <td>{{ $claim->status }}</td>
        <td>حالة الشكوى</td>
    </tr>
    @if(auth()->user()->role === 1)
        <tr>
            @if(!($logs ===null))
                @php
                    $log = \Illuminate\Support\Facades\DB::table('users')->find($logs->user_id);
                @endphp
                <td>{{$log->name}}</td>
            @else
                <td></td>

            @endif
            <td>اسم الموظف</td>
        </tr>
    @endif
    <tr>
        <td>{{ $logs ? $logs->created_at : '' }}</td>
        <td>تاريخ و توقيت حفظ الطلب</td>
    </tr>
</table>

</body>
</html>
