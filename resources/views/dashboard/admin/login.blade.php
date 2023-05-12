@include('layouts.mini-master')
<div class="global-container">
    <div class="card login-form">
        <div class="card-body">
            <h3 class="card-title">تسجيل الدخول</h3>
            <div class="card-text">
                <!--
                <div class="alert alert-danger alert-dismissible fade show" role="alert">Incorrect username or password.</div> -->
                <form action="{{route('login')}}" method="post" class="login">
                @csrf
                <!-- to error: add class "has-danger" -->
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
                        <a href="{{route('register')}}">ليس لديك حسابك الخاص بك أنشئ حساب جديد</a>
                    </div>
                    <div class="btn-div">
                        <button type="submit" class="btn btn-primary btn-block bg-blue">تسحيل الدخول</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

