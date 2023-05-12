@extends('layouts.footer')

@section('footer')

@endsection

@extends('layouts.app')

@section('content')
    <style>
        #h1-mob{
            display: none;
        }
        #safeDeskImg{
            margin-bottom: 0px !important;
        }
        #safeMobImg{
            display: none;
        }
        .login-img{
            width: 85%;
            margin-top: 3em;
        }
        @media (min-width:992px) and (max-width:1200px) {
            #form{
                margin-top: 10em;
            }
        }
        @media (max-width: 1200px) {
            #h1-desk{
                display: none;
            }
            input{
                margin-left: 0em !important;
                width: 100% !important;
            }

            #h1-mob{
                display: block;
            }
            #but1{
                color: white !important ;
                background-color:#E96424 !important ;
                border: 4px solid #E96424 !important;
                width: 100% !important;
                margin-right: 0px !important;
            }
            #but2{
                color: #2B415D !important;
                border: 4px solid #2B415D !important;
                width: 100% !important;
                margin-right: 0px !important;
            }
            #but2 a{
                color: #2B415D !important;
            }
            #email{
                margin-left: 8rem;
            }
            #password{
                margin-left: 8rem;
            }
            #rf{
                margin-right: 0px !important;
            }
            #rf a{
                margin-left: 0px !important;
            }
            #safeMobImg{
                display: block;
            }
            #safeText{
                display: none;
            }
            #safeDeskImg{
                display: none;
            }
            #safeHandle{
                display: none;
            }
        }
        @media (max-width:1440px) and (min-width:1200px) {
            #rf{
                margin-right: 4em;
            }
        }
    </style>
    <script>
        function myfunction(){
            document.getElementById("safeText").value ="**";
            document.getElementById("safeHandle").style.transform="rotate(45deg)";
        }
        function myfunction2(){
            document.getElementById("safeText").value ="****";
            document.getElementById("safeHandle").style.transform="rotate(105deg)";
        }
    </script>
    <link href="css/app.css" rel="stylesheet">
    <img class="lower-path position-absolute" src="Baseet/images/LoginBackground.svg" style="width:13%"/>
    <div class="container">
        <div class="row">
            <div id="safe" class="col-lg-7 col-md-12 col-sm-12 d-flex flex-col " style="align-items:center;">
                <h1 id="h1-mob" class="mt-10 mb-0 text-center" style="color:#181F3A; font-size:3rem;font-family:'Bahij-Plain',sans-serif;">تسجيل الدخول</h1>
                <img id="h1-mob" src="Login_Img/Group 5259.svg" alt="">
                <img class="login-img" src="Baseet/images/LoginImg.svg" alt="">
            </div>
            <div id="form" class="col-lg-5 col-md-12 col-sm-12 d-flex flex-col align-center">
                <h1 id="h1-desk" class="mt-10 mb-12 text-center" style="color:#181F3A; font-size:3rem;font-family:'Bahij-Plain',sans-serif;">تسجيل الدخول</h1>
                @if (session('status'))
                    <div class="bg-red-500 pt-2 pb-2 rounded-lg mb-6 text white text-center" style=" font-family:'Somar'; font-size:2rem; color:white;margin: 0 2.2em;;font-weight:bold;">
                        {{session('status')}}
                    </div>
                @endif
                <form class="px-3"  action="{{route('login.store')}}" method="post">
                    @csrf
                    <div class="mb-4 dir-rtl d-flex justify-content-center mt-4">
                        <label for="email" class="sr-only">Email</label>
                        <input onchange="myfunction()" dir="rtl" type="email" name="email" id="email" placeholder="البريد الالكتروني" class="mb-2 pr-4 pt-2 pb-2 rounded-xl @error('email') border-red-500 @enderror" value="{{old('email')}}" style="border:2px solid #63BEDD; width:370px">
                        @error ('email')
                        <div class="text-red-500 mr-24 text-sm text-right">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-4 dir-rtl d-flex justify-content-center">
                        <label for="password" class="sr-only">Password</label>
                        <input onchange="myfunction2()" dir="rtl" type="password" name="password" id="password" placeholder="كلمة المرور" class="pr-4 pt-2 pb-2  rounded-xl @error('password') border-red-500 @enderror" style="border:2px solid #63BEDD; width:370px">
                        @error ('password')
                        <div class="text-red-500 mr-24 text-sm text-right">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-2 dir-rtl"style="position:relative; bottom:15px;">
                        <div id="rf" class="flex justify-content-start"  style="direction: rtl;">
                            <input dir="rtl" type="checkbox" name="remember" id="remember" style="width: 50px !important;">
                            <label for="remember" style="color:#2B415D; font-family:'Bahij-Plain',sans-serif; font-size:1rem; margin-right: 0.2em">تذكرني</label>
{{--                            <a href="#" class="ml-16 mr-32" style="white-space: nowrap;  text-overflow: clip;color:#035AA7; font-family:'Somar'; font-size:1.25rem;">نسيت كلمة المرور؟</a>--}}
                        </div>
                    </div>
                    <div class="flex align-center justify-center items-center dir-rtl">
                        <button id="but1" type="submit" class="text-center bold rounded-xl mb-4" style="color: white; font-family:'Bahij-Plain',sans-serif; font-size:1.3rem; background-color: #E96424 ; width:370px;height:50px">
                            تسجيل الدخول
                        </button>
                    </div>
                </form>
                <div class="flex align-center justify-center items-center  px-3">
                    <button id="but2" type="submit" class="text-center bold rounded-xl mb-12" style="color: #2B415D; font-family:'Bahij-Plain',sans-serif; font-size:1.8rem; border: 3px solid #2B415D; height:50px; width:370px; ">
                        {{--            background-image: -webkit-linear-gradient(right , white 56%, #ea676c 45%)--}}
                        <a  href="{{route('register')}}" style="color: #2B415D;">إشترك الآن
                            {{--              <span style="color:white; margin-right:0.2em; ">ليس لديك حساب متجر</span>--}}
                        </a>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
