@extends('layouts.app')

@section('content')
  <style>
    #h1-mob{
      display: none;
    }
    @keyframes mymove {
      0% {transform: rotate(0deg);}
      50% {transform: rotate(5deg);}
      100% {transform: rotate(0deg);}
    }
    @media (min-width:992px) and (max-width:1300px) {
      #form{
        margin-top: 10em;
      }
    }
    @media (max-width: 1300px) {
    #desk{
      display: none;
    }
    #h1-desk{
      display: none;
    }

    #h1-mob{
      display: block;
    }
    #but1{
      color: white !important ;
      background-color:#ea676c !important ;
      border: 4px solid #ea676c !important;
      width: 100% !important;
      margin-right: 0px !important;
    }
    #but1 a {
      color: white;
    }
    #but2{
      border: 4px solid #035AA7 !important;
      background-color:#035AA7 !important ;
      width: 100% !important;
      margin-right: 0px !important;
    }
    #email{
      margin-left: 8rem;
    }
    input{
      margin-left: 0em !important;
      width: 100% !important;
    }
  }
  </style>
  <link href="css/app.css" rel="stylesheet">
<div class="container">
  <div class="hero-background">
    <img class="img-fluid" src="Baseet/images/ForgetBackground.svg" id="hero-background-des">
  </div>
  <div class="row">
    <div class="col-lg-7 col-md-12 col-sm-12 d-flex flex-col" style="align-items:center;">
      <h1 id="h1-mob" class="mt-10 mb-0 text-center" style="color:#035AA7; font-size:3.5rem;font-family:'Somar'; font-weight:800;">استعادة كلمة المرور</h1>
      <img id="h1-mob" src="Forgot_Img/Group 5304.svg" alt="">
      <img id="desk" src="Forgot_Img/Group 2951.svg" alt="">
      <img id="desk" src="Forgot_Img/Group 5079.svg" style="position:absolute; top:160px; left:170px; animation: mymove 0.8s  ;transition: all 0.3s ease;">
      <img id="h1-mob"class="img-fluid mb-12" src="Forgot_Img/Group 5082.svg" alt="">
    </div>
    <div id="form" class="col-lg-5 col-md-12 col-sm-12 d-flex flex-col align-center">
      <h1 id="h1-desk" class="mt-10 mb-12 text-center" style="color:#035AA7; font-size:3.5rem;font-family:'Somar'; font-weight:800;">استعادة كلمة المرور</h1>
      @if (session('status'))
        <div class="bg-red-500 p4 rounded-lg mb-6 text white text-center" style=" font-family:'Somar;'">
          {{session('status')}}
        </div>
      @endif
      <form  action="{{route('forgot')}}" method="post">
        @csrf
        <div class="mb-4">
        <label for="email" class="sr-only">Email</label>
        <input dir="rtl" type="email" name="email" id="email" placeholder="البريد الالكتروني" class="mb-2 ml-16 pl-3 pr-3 rounded-xl @error('email') border-red-500 @enderror" style="border:4px solid #035AA7; width:370px; padding-top:0.5em; padding-bottom:0.5em;">
          @error ('email')
            <div class="text-red-500 mr-24 text-sm text-right">
              {{$message}}
            </div>
          @enderror
        </div>
        <div class="flex align-center justify-center items-center">
          <button type="submit" class="text-center mr-4  bold rounded-xl mb-3 " style="color: white; font-family:'Somar'; font-size:2.25em;  font-weight:800; background-color:#035AA7;padding-left:1.5em; padding-right:1.5em;">
            ارسال
          </button>
        </div>
      </form>
      <p class="text-center mr-8" style="color:#035AA7;font-family:'Somar'; font-size:2rem; font-weight:bold;">ليس لديك حساب متجر</p>
      <div class="flex align-center justify-center items-center">
        <button id="but1" type="submit" class="text-center mr-4 pr-3 pl-3 bold rounded-xl mb-4" style="color: #035AA7; font-family:'Somar'; font-size:1.8rem; border: 4px solid #035AA7; font-weight:800; width:370px">
          <a href="{{route('login')}}">
          تسجيل الدخول
          </a>
        </button>
      </div>
{{--      <div class="flex align-center justify-center items-center">--}}
{{--        <button id="but2" type="submit" class="text-center mr-4 pr-3 pl-3 bold rounded-xl mb-12" style="color: #ea676c; font-family:'Somar'; font-size:1.8rem; border: 4px solid #ea676c; font-weight:bold; width:370px;   background-color:#ea676c;">--}}
{{--          <a href="{{route('register')}}" style="color: white;">--}}
{{--          أنشئ متجر جديد--}}
{{--         </a>--}}
{{--        </button>--}}
{{--      </div>--}}
    </div>
  </div>
</div>
@endsection
