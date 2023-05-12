<table class="table table-striped table-bordered table-list">
    <thead>
    <tr>
        <th class="hidden-xs">رقم الشكوى</th>
        <th scope="col">اسم المتجر</th>
        <th scope="col">المحافظة</th>
        <th scope="col">المنطقة</th>
        <th scope="col">نوع الشكوى</th>
        <th scope="col">حالة الشكوى</th>
        <th scope="col">الشكوى</th>
        @if (auth()->user()->role === 1)
            <th scope="col">الأسم</th>
            <th scope="col">رقم الجوال</th>
            <th scope="col">اسم الموظف</th>
            <th scope="col">تاريخ و توقيت حفظ الطلب</th>

        @endif

    </tr>
    </thead>
    <tbody>
    @foreach($claims as $data)
        @php
            $dep = \Illuminate\Support\Facades\DB::table('district')->find($data->department);
            $gov = \Illuminate\Support\Facades\DB::table('city')->find($data->governorate);
            $logs = DB::table('logs')->where('claim_id', $data->id)->get()->last();
            if ($logs)
                $log = \Illuminate\Support\Facades\DB::table('users')->find($logs->user_id);
        @endphp
        <tr>
            <th scope="row" class="hidden-xs">{{ $data->code }}</th>
            <td>{{$data->store_name }} </td>
            <td>{{ $gov->name }} </td>
            <td>{{ $dep->name }}</td>
            <td>{{__("messages.$data->claim_type")}}</td>
            <td>{{$data->status}}</td>
            <td>{{$data->claim}}</td>
            @if (auth()->user()->role === 1)
                <td>{{ $data->name }}</td>
                <td>{{ $data->phone_number }}</td>
                <td>{{$log->name ?? ''}}</td>
                <td>{{$logs->created_at ?? ''}}</td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
