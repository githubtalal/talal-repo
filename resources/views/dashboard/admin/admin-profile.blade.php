@extends('layouts.master')

@section('content')
    <div class="dashboard">
        <div class="card admin-card">
            <div class="card-body">
                <h3> معلومات: {{$user->name}}</h3>
                <hr>
    <form action="{{route('changePassword', ['user' => $user->id])}}" method="post">
        @csrf
        <div class="row">
            <div class="col-lg-6 col-12 p-0-1">
                <div class="form-floating">
                    @if(auth()->user()->role == 1)
                    <input name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                           id="floatingInputGrid" placeholder="الإسم الكامل" value="{{$user-> name}}">
                    @endif
                    @if(auth()->user()->role == 0)
                            <input name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="floatingInputGrid" placeholder="الإسم الكامل" value="{{$user-> name}}" disabled>
                        @endif
                    <label for="floatingInputGrid">الاسم<span class="red">*</span></label>
                        @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6 col-12 p-0-1">
                <div class="form-floating">
                    <input name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                           id="floatingInputGrid" placeholder="البريد الإلكتروني" value="{{$user-> email}}" disabled>
                    <label for="floatingInputGrid">البريد الإلكتروني<span class="red">*</span></label>
                    @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-12 p-0-1">
                <div class="form-floating">
                    <input name="current_password" type="password"  class="form-control @error('current_password') is-invalid @enderror"
                           id="floatingInputGrid" placeholder="كلمة المرور الحالية">
                    <label for="floatingInputGrid">كلمة المرور الحالية<span class="red">*</span></label>
                    @error('current_password')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-12 p-0-1">
                <div class="form-floating">
                    <input name="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror"
                           id="floatingInputGrid" placeholder="كلمة المرور الجديدة">
                    <label for="floatingInputGrid">كلمة المرور الجديدة<span class="red">*</span></label>
                        @error('new_password')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-12 p-0-1">
                <div class="form-floating">
                    <input name="new_password_confirmation" type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror"
                           id="floatingInputGrid" placeholder="تأكيد كلمة المرور">
                    <label for="floatingInputGrid">تأكيد كلمة المرور<span class="red">*</span></label>
                        @error('new_password_confirmation')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <button type="submit"  class="btn btn-primary submit-btn btn-block bg-blue">حفظ</button>
            </div>
        </div>
    </form>
            </div>
        </div>
    </div>
@endsection
