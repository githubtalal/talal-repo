@include('layouts.mini-master')
<div class="global-container">
    <div class="card login-form">
        <div class="card-body">
            <h3 class="card-title">تسجيل الدخول</h3>
            <div class="card-text">
                <!--
                <div class="alert alert-danger alert-dismissible fade show" role="alert">Incorrect username or password.</div> -->
                <form action="{{route('register')}}" method="post" class="login">
                    @csrf
                    <!-- to error: add class "has-danger" -->
                    <div class="row">
                    <div class="col-6 p-0-1">
                        <div class="form-floating">
                            <input name="name" type="text"
                                   class="login-input form-control @error('name') is-invalid @enderror"
                                   id="floatingInputGrid" placeholder="الإسم الكامل">
                            <label for="floatingInputGrid">الإسم الكامل</label>
                            @error('name')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6 p-0-1">
                        <div class="form-floating">
                            <input name="phone_number" type="number"
                                   class="login-input form-control @error('phone_number') is-invalid @enderror"
                                   id="floatingInputGrid" placeholder="رقم الهاتف">
                            <label for="floatingInputGrid">رقم الهاتف</label>
                            @error('phone_number')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 p-0-1">
                        <div class="form-floating">
                            <input name="email" type="email"
                                   class="login-input form-control @error('email') is-invalid @enderror"
                                   id="floatingInputGrid" placeholder="البريد الإلكتروني">
                            <label for="floatingInputGrid">البريد الإلكتروني</label>
                            @error('email')
                            <div class="alert alert-danger">{{ __("validation.email") }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 p-0-1">
                        <div class="form-floating">
                            <input name="password" type="password"
                                   class="login-input form-control @error('password') is-invalid @enderror"
                                   id="floatingInputGrid" placeholder="كلمة المرور">
                            <label for="floatingInputGrid">كلمة المرور</label>
                            @error('password')
                            <div class="alert alert-danger">{{ __("validation.password") }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 p-0-1">
                        <div class="form-floating form-claim">
                            <select name="location"
                                    class="form-input governorate_input form-select @error('location') is-invalid @enderror"
                                    id="loc" aria-label="Floating label select example">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                            <label for="floatingSelectGrid">المنطقة/موقع<span class="red">*</span></label>
                            @error('location')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="btn-div">
                        <button type="submit" class="btn btn-primary btn-block bg-blue">إنشاء حساب جديد</button>
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
