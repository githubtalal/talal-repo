@extends('layouts.master')

@section('content')
    <div class="card search-card ">
        <div class="card-body p-2-4">
            <h3 class="card-title">إبحت عن طلبك</h3>
            <p class="card-sub-title"> الرجاء إدخال رقم الشكوى أو رقم الموبايل للمراجعة شكواكم</p>
            <div class="card-text">
                <form method="GET" action="{{route('view')}}">
                    @csrf
                    <div class="row">
                        <div class="col-5  p-0-1">
                            <div class="form-floating">
                                <input name="id" type="text" class="form-control @error('id') is-invalid @enderror"
                                       id="floatingInputGrid" placeholder="رقم الشكوى">
                                <label for="floatingInputGrid">رقم الشكوى</label>
                                @error('id')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-5  p-0-1">
                            <div class="form-floating">
                                <input name="phone_number" type="text"
                                       class="form-control @error('phone_number') is-invalid @enderror"
                                       id="floatingInputGrid" placeholder="رقم الموبايل">
                                <label for="floatingInputGrid">رقم الموبايل</label>
                                @error('phone_number')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-2">
                            <button type="submit" class="btn btn-primary btn-block search-btn bg-blue">بحث</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
