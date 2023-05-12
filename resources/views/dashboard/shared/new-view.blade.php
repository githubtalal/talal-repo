@extends('layouts.master')

@section('content')
    <div class="alert alert-success" role="alert">
        تم إرسال طلب الشكوى الخاص بك سيتم معالجته بأسرع وقت ممكن
    </div>
    <div class="card view-card">
        <div class="card-body p-res">
            <div class="row">
                <div class="col-6"><h3>إستعراض الشكوى</h3></div>
                <div class="col-3">
                    <button onclick="window.location.href ='/' " type="button"
                            class="btn btn-primary claim-btn bg-green" data-bs-dismiss="modal" aria-label="Close"
                            style="margin: 0em 1em ;">الرجوع
                    </button>
                </div>
                <div class="col-6 custom-div">
                    <p>رقم الشكوى</p>
                </div>
                <div class="col-6 custom-div">
                    <p>{{$claim->code}}</p>
                </div>
                <div class="col-6 custom-div">
                    <p>الإسم</p>
                </div>
                <div class="col-6 custom-div">
                    <p>{{$claim->name}}</p>
                </div>
                <div class="col-6 custom-div bg-f4">
                    <p>رقم الجوال</p>
                </div>
                <div class="col-6 custom-div bg-f4">
                    <p>{{$claim->phone_number}}</p>
                </div>
                <div class="col-6 custom-div">
                    <p>المحافظة</p>
                </div>
                @php
                    $dep = \Illuminate\Support\Facades\DB::table('district')->find($claim->department);
                    $gov = \Illuminate\Support\Facades\DB::table('city')->find($claim->governorate);
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
                    <p>{{__("messages.$claim->claim_type")}}</p>
                </div>
                <div class="col-6 custom-div bg-f4">
                    <p>العنوان</p>
                </div>
                <div class="col-6 custom-div bg-f4">
                    <p>{{$claim->address}}</p>
                </div>
                <div class="col-6 custom-div bg-f4">
                    <p>الشكوى</p>
                </div>
                <div class="col-6 custom-div bg-f4">
                    <p>{{$claim->claim}}</p>
                </div>
                <div class="col-6 custom-div">
                    <p>المرفقات</p>
                </div>
                <div class="col-6 custom-div">
                    @if(!$claim->attachment_url == null)
                    <a href="{{asset($url)}}" download><i class="fa fa-download" aria-hidden="true"></i></a>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
