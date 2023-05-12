@extends('layouts.master')

@section('content')
    <div class="card view-card">
        <div class="card-body p-res">
            <div class="row">
                <div class="col-6"><h3>إستعراض الشكوى</h3></div>
                <div class="col-6">
                    <button onclick="window.history.back()" type="button" class="btn btn-primary claim-btn bg-green"
                            data-bs-dismiss="modal" aria-label="Close" style="margin: 0em 1em ;">الرجوع
                    </button>
                </div>
                <div class="col-6 custom-div">
                    <p>رقم الشكوى</p>
                </div>
                <div class="col-6 custom-div">
                    <p>{{$claims->code}}</p>
                </div>
                <div class="col-6 custom-div">
                    <p>رقم الموبايل</p>
                </div>
                <div class="col-6 custom-div">
                    <p>{{$claims->phone_number}}</p>
                </div>
                <div class="col-6 custom-div">
                    <p>المحافظة</p>
                </div>
                @php
                    $dep = \Illuminate\Support\Facades\DB::table('district')->find($claims->department);
                    $gov = \Illuminate\Support\Facades\DB::table('city')->find($claims->governorate);
                @endphp
                <div class="col-6 custom-div">
                    <p>{{$gov->name}}</p>
                </div>
                <div class="col-6 custom-div bg-f4">
                    <p>المنطقة</p>
                </div>
                <div class="col-6 custom-div bg-f4">
                    <p>{{$dep->name}}</p>
                </div>
                <div class="col-6 custom-div">
                    <p>نوع الشكوى</p>
                </div>
                <div class="col-6 custom-div">
                    <p>{{$claims->type->name ?? '---'}}</p>
                </div>
                {{--                <div class="col-6 custom-div bg-f4">--}}
                {{--                    <p>الشكوى</p>--}}
                {{--                </div>--}}
                {{--                <div class="col-6 custom-div bg-f4">--}}
                {{--                    <p>{{$claims->claim}}</p>--}}
                {{--                </div>--}}
                {{--                <div class="col-6 custom-div">--}}
                {{--                    <p>المرفقات</p>--}}
                {{--                </div>--}}
{{--                <div class="col-6 custom-div">--}}
{{--                    @if(!$claims->attachment_url == null)--}}
{{--                        <a href="{{asset($url)}}" download><i class="fa fa-download" aria-hidden="true"></i></a>--}}
{{--                    @endif--}}
{{--                </div>--}}
                <div class="col-6 custom-div bg-f4">
                    <p>حالة الشكوى</p>
                </div>
                <div class="col-6 custom-div bg-f4">
                    <p>{{$claims->status}}</p>
                </div>
                <div class="col-6 custom-div bg-f4">
                    <p>نتيجة المعالجة</p>
                </div>
                <div class="col-6 custom-div bg-f4">
                    <p>{{$claims->claim_note}}</p>
                </div>
            </div>

        </div>
    </div>
@endsection
