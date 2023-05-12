@extends('layouts.mini-master')

@section('content')
    <div class="card welcome-card " style="text-align:center">
        <div class="card-body p-2-4">
            <img src="{{asset('img/2-01.png')}}" style="width:35%;margin-bottom: 1em"/>
            <h1 class="green">منصة لتقديم الشكاوى التموينية</h1>
            <p>تمكن هذه المنصة الأخوة المواطنين من تقديم شكاويهم التموينية ومتابعتيها</p>
            <div class="card-text">
                <hr>
                <div class="row">
                    <div class="col-6" style="display: flex;justify-content: center;align-items: center">
                        <a href="{{route('claim')}}"><button class="btn btn-primary bg-blue" style="background-color: #0E497D;border-color: #0E497D;">شكوى جديدة</button></a>
                    </div>
                    <div class="col-6" style="display: flex;justify-content: center;align-items: center">
                        <a href="{{route('search')}}"><button class="btn btn-primary" style="background-color: #047835;border-color: #047835;"> مراجعة شكوى</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
