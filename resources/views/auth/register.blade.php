<?php
$facebook = \App\Models\Product::query()->withoutGlobalScope('store_access')->where('additional->permission_name', 'messenger_bot')->first();
$telegram = \App\Models\Product::query()->withoutGlobalScope('store_access')->where('additional->permission_name', 'telegram_bot')->first();
$widget = \App\Models\Product::query()->withoutGlobalScope('store_access')->where('additional->permission_name', 'website_widget')->first();
$codes = [
    'fatora',
    'ecash-card',
    'syriatel-cash-api',
    'haram',
    'mtn-cash-api',
];
$names = [
    '<img class="payment-method-img" src="Baseet/images/BlueFatoraLogo.svg" />',
    '<img class="payment-method-img" src="Baseet/images/ecash.png" />',
    'سيريتل كاش - Syriatel Cash',
    'حوالة مالية (الهرم أو الفؤاد)',
    'كاش موبايل - MTN',
];

$description = [
    '<div class="row">
    <div class="col-12" style="font-family:Bahij-Bold, sans-serif;font-size:1.3em ">
    عبر بوابة الدفع الالكتروني من فاتورة
    </div>
    <div class="col-12" style="font-family:Bahij-SemiBold, sans-serif ">
     يمكنك الدفع باستخدام بطاقات البنوك العاملة على شبكة فاتورة (بنك سورية الدولي الإسلامي - بنك الشام - بنك سورية والخليج - بنك البركة الدولي الإسلامي)
    </div>
    </div>',
    '<div class="row">
    <div class="col-12" style="font-family:Bahij-Bold, sans-serif;font-size:1.3em ">
    عبر بوابة الدفع الالكتروني من إي-كاش
    </div>
    <div class="col-12" style="font-family:Bahij-SemiBold, sans-serif ">
     يمكنك الدفع باستخدام بطاقات البنوك العاملة على شبكة إي-كاش (بنك سورية الدولي الإسلامي - بنك الشام - بنك سورية والخليج - بنك البركة - بنك بيمو)
    </div>
    </div>',
    '  <div class="row" >
                                            <div class="col-12" style="font-family: Bahij-SemiBold,sans-serif">
                                              عند اختيارك الدفع عن طريق  سيريتل كاش - Syriatel Cash ، سيتم ارسال رمز التحقق لك لاتمام العملية
                                            </div>
                                        </div>',
//    '                                         <div class="row" >
//                                            <div class=" col-12" style="font-family:Bahij-Bold, sans-serif;font-size:1.3em ">
//                                                عن طريق تطبيق أقرب إليك
//                                            </div>
//                                            <div class="col-12" style="font-family: Bahij-SemiBold,sans-serif">
//                                                - افتح تطبيق أقرب إليك
//                                            </div>
//                                            <div class="col-12" style="font-family: Bahij-SemiBold,sans-serif">
//                                                - اذهب لقسم سيرياتيل كاش
//                                            </div>
//                                             <div class="col-12" style="font-family: Bahij-SemiBold,sans-serif">
//                                                - ادخل الرمز السري الخاص بحسابك
//                                            </div>
//                                            <div class="col-12" style="font-family: Bahij-SemiBold,sans-serif">
//                                                - انقر على الدفع اليدوي
//                                            </div>
//                                            <div class="col-12" style="font-family: Bahij-SemiBold,sans-serif">
//                                                - ادخل رقم التاجر (0987209640) ثم أكده مرة ثانية
//                                            </div>
//                                             <div class="col-12" style="font-family: Bahij-SemiBold,sans-serif">
//                                                - ادخل المبلغ المطلوب دفعه وأكد المبلغ
//                                            </div>
//                                            <div class="col-12" style="font-family: Bahij-SemiBold,sans-serif">
//                                                - ادخل المبلغ المطلوب دفعه
//                                            </div>
//                                             <div class="col-12" style="font-family: Bahij-SemiBold,sans-serif">
//                                                - انتظر رسالة تاكيد عملية الدفع
//                                            </div>
//                                              <div class="col-12" style="font-family: Bahij-SemiBold,sans-serif">
//                                                - قم بالتقاط صورة للشاشة و ارسلها لنا عبر واتس اب على الرقم (0987209645)
//                                            </div>
//                                        </div>
//      ',
    '
    <div class="row">
        <div style="font-family:Bahij-Bold, sans-serif;font-size:1.3em ">
        حوالة مالية عبر شركة (الهرم أو الفؤاد)
        </div>
        <div class="col-12" style="font-family:Bahij-SemiBold, sans-serif; ">
        يمكنك الدفع عن طريق (الهرم أو الفؤاد) بإرسالة حوالة بحسب المعلومات التالية
        </div>
      <div class="col-12" style="font-family: Bahij-SemiBold,sans-serif">
          - الاسم: محمد عادل محمد زهير علبي
      </div>
      <div class="col-12" style="font-family: Bahij-SemiBold,sans-serif">
          - رقم الموبايل: (0987209645)
      </div>
      <div class="col-12" style="font-family: Bahij-SemiBold,sans-serif">
          - العنوان: دمشق-الطلياني
      </div>
      <div class="col-12" style="font-family: Bahij-SemiBold,sans-serif">
          - المبلغ المراد دفعه
      </div>
    </div>
    '
    ,
    '                                        <div class="row" >
                                            <div class="col-12" style="font-family: Bahij-SemiBold,sans-serif">
                                              عند اختيارك الدفع عن طريق كاش موبايل، سيتم ارسال رمز التحقق لك لاتمام العملية
                                            </div>
                                        </div>',

];


?>

@extends('layouts.app')
@section('content')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        footer {
            padding-left: 11em;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Verdana, sans-serif;
        }

        .mySlidesReg {
            display: none;
        }

        .mySlidesReg img {
            vertical-align: middle;
            width: 435px
        }

        /* Slideshow container */
        .slideshowReg-container {
            max-width: 1000px;
            position: fixed;
            margin: auto;
        }

        /* Caption text */
        .text {
            font-family: 'Somar';
            color: #09091D;
            font-size: 1.5625rem;
            font-weight: 500;
            position: absolute;
            bottom: auto;
            left: 30px;
            top: 19%;
            right: 0;
            text-align: center;
        }

        .animate-top-1 {
            animation: animatetop-3 0.5s forwards;
        }

        @keyframes animatetop-3 {
            from {
                top: -300px;
                left: 50%;
                opacity: 0;
            }
            to {
                top: 0px;
                left: 50%;
                opacity: 1;
            }
        }

        .animate-top-2 {
            animation: mymove 1.5s infinite, animatetop-4 1s forwards;
        }

        @keyframes animatetop-4 {
            from {
                top: 0%;
                left: 0%;
                opacity: 0;
            }
            to {
                top: 0%;
                left: 0%;
                opacity: 1;
            }
        }

        #background1 {
            position: absolute;
            top: -10px;
            right: 100px;
            z-index: -1;
        }

        #h1-mob {
            display: none;
        }

        input {
            border: 2px solid #63BEDD;
            width: 530px;
        }

        .popup {
            content: "";
            background: rgba(0, 0, 0, 0.4);
            width: 100%;
            height: 250%;
            position: absolute;
            left: 0;
            top: 0;
            z-index: 1;
        }

        .handle {
            position: fixed;
            top: 0;
            left: 50%;
            z-index: 3;
        }

        .after_reg {
            position: fixed;
            top: 25%;
            left: 32.65%;
            z-index: 3;
        }

        .logo1 {
            position: fixed;
            top: 28.3%;
            left: 44%;
            z-index: 3;
            animation: mymove 1s infinite
        }

        .approve {
            position: fixed;
            top: 42%;
            left: 40%;
            z-index: 3;
            animation: mymove 1s infinite
        }

        .popup h1 {
            position: fixed;
            top: 69%;
            left: 45%;
            color: #000000;
            font-family: 'Somar';
            font-size: 1.6rem;
            font-weight: bold;
            z-index: 3;
            text-align: right;
        }

        .popup h2 {
            position: fixed;
            top: 74%;
            left: 44.5%;
            color: #000000;
            font-family: 'Somar';
            font-size: 1.1rem;
            z-index: 3;
            text-align: center;
        }

        #popbut {
            position: fixed;
            top: 82%;
            left: 47%;
            z-index: 3;
        }

        #regbut-mob {
            display: none;
        }

        @keyframes mymove {
            0% {
                transform: rotate(0deg);
            }
            33% {
                transform: rotate(1.5deg);
            }
            66% {
                transform: rotate(-1.5deg);
            }
            100% {
                transform: rotate(0deg);
            }
        }

        @
        @media (min-width: 992px) and (max-width: 1300px) {
            #form {
                width: 100% !important;
            }
        }

        @
        @media (max-width: 1300px) {
            #h1-desk {
                display: none;
            }

            #h1-mob {
                display: block;
                text-align: center !important;
                margin-right: 0em !important;
            }

            #l {
                color: #ea676c !important;
                font-weight: 1000 !important;
                margin-right: 0em !important;
            }

            h3 {
                margin-right: 0em !important;
            }

            p {
                margin-right: 0em !important;
            }

            input {
                margin-left: 0em !important;
                width: 100% !important;
            }

            #regbutDesk {
                display: none;
            }

            #regbut-mob {
                display: block;
                margin-top: 0.7em;
                width: 100% !important;
                color: white !important;
                background-color: #ea676c;
            }
        }

        /* On smaller screens, decrease text size */
        @media only screen and (max-width: 300px) {
            .text {
                font-size: 11px
            }
        }
    </style>
    <div class="position-relative">
        <img class="position-fixed  desk" style="top:0;left: 0;width: 38%;z-index:-1"
             src="Baseet/images/RegBackground.svg"/>
        <div class="row reg-row">
            <div class="col-4 flex align-center justify-center">
                <img class="position-fixed desk" src="Baseet/images/RegisterImg.svg" style="height: 60%;top: 30%;"/>
            </div>
            <div id="form" class="col-lg-8 col-md-12 col-sm-12">
                <div class="mr-20">
                    <h1 class="text-right mt-12 mb-1"
                        style="color:#192B4B; font-family:'Bahij-Bold',sans-serif; font-size:2.5rem; ">أنشئ متجرك</h1>
                    <h1 id="h1-desk" class="text-right mb-8 "
                        style="color:#192B4B; font-family:'Bahij-Bold',sans-serif; font-size:1.525rem;">يا أهلاً بك في
                        عالم التجارة الإلكترونية</h1>
                    <h1 id="h1-mob" class="text-rightmb-8"
                        style="color:#183C6C; font-family:'Somar'; font-size:3.125rem; font-weight:bold;">يا أهلاً بك في
                        <br> عالم التجارة الإلكترونية</h1>
                    <form action="{{route('register.store')}}" method="post">
                        @csrf
                        <div class="row dir-rtl">

                            <div class="mb-2 dir-rtl col-lg-6 col-12 ">
                                <h3 class="text-right mb-2"
                                    style="color:#183C6C; font-family:'Bahij-SemiBold',sans-serif;font-size:1rem;">اسم
                                    المتجر</h3>
                                <label for="name" class="sr-only">Store-Name</label>
                                <input dir="rtl" type="text" name="store_name" id="name"
                                       class="register_input w-100 p-3  @error('store_name') border-red-500 @enderror"
                                       value="{{old('store_name')}}" required>
                                @error ('store_name')
                                <div class="text-red-500 mr-24 text-sm text-right"
                                     style="margin:0;font-family: 'Bahij-SemiBold',sans-serif ">
                                    {{$message}}
                                </div>
                                @enderror

                            </div>
                            <div class="mb-2 dir-rtl col-lg-6 col-12">
                                <h3 class="text-right mb-2"
                                    style="color:#183C6C; font-family:'Bahij-SemiBold',sans-serif;font-size:1rem;">
                                    نوع
                                    المتجر</h3>
                                <label for="storeType" class="sr-only">Store-Type</label>
                                <input dir="rtl" type="text" name="store_type" id="storeType"
                                       class="register_input w-100 p-3  @error('store_type') border-red-500 @enderror"
                                       value="{{old('store_type')}}" required>
                                @error ('store_type')
                                <div class="text-red-500 text-sm text-right"
                                     style="margin:0;font-family: 'Bahij-SemiBold',sans-serif ">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-2 dir-rtl col-lg-6 col-12 ">
                                <h3 class="text-right mb-2"
                                    style="color:#183C6C; font-family:'Bahij-SemiBold',sans-serif;font-size:1rem;">مدير
                                    المتجر</h3>
                                <label for="managerName" class="sr-only">ManagerName</label>
                                <input dir="rtl" type="text" name="store_manager" id="managerName"
                                       class="register_input p-3 w-100  @error('store_manager') border-red-500 @enderror"
                                       value="{{old('store_manager')}}" required>
                                @error ('store_manager')
                                <div class="text-red-500 text-sm text-right"
                                     style="margin:0;font-family: 'Bahij-SemiBold',sans-serif ">
                                    {{$message}}
                                </div>
                                @enderror

                            </div>
                            <div class="mb-2 dir-rtl col-lg-6 col-12 ">
                                <h3 class="text-right mb-2"
                                    style="color:#183C6C; font-family:'Bahij-SemiBold',sans-serif;font-size:1rem;">رقم
                                    الجوال</h3>
                                <label for="phoneNumber" class="sr-only">PhoneNumber</label>
                                <div class="input-group mb-3">
                                    <input dir="rtl" type="tel" maxlength="10" name="phone_number" id="phoneNumber"
                                           class="register_input p-3  @error('phone_number') border-red-500 @enderror"
                                           value="{{old('phone_number')}}" max="10" required>
                                    <button class="btn input-btn" type="button" id="send_otp">إرسال رمز التحقق</button>
                                    <button class="btn input-btn d-none" type="button" id="resend_otp">إعادة إرسال رمز التحقق</button>
                                </div>
                                @error ('phone_number')
                                <div class="text-red-500 text-sm text-right"
                                     style="margin:0;font-family: 'Bahij-SemiBold',sans-serif ">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3 dir-rtl col-lg-6 col-12">
                                <h3 class="text-right  mb-2"
                                    style="color:#183C6C; font-family:'Bahij-SemiBold',sans-serif;font-size:1rem;">
                                    البريد
                                    الالكتروني</h3>
                                <label for="email" class="sr-only">Email</label>
                                <input dir="rtl" type="email" name="email" id="email"
                                       class="register_input p-3 w-100  @error('email') border-red-500 @enderror"
                                       value="{{old('email')}}" required>
                                @error ('email')
                                <div class="text-red-500 text-sm text-right"
                                     style="margin:0;font-family: 'Bahij-SemiBold',sans-serif ">
                                    {{$message}}
                                </div>
                                @enderror

                            </div>
                            <div class="mb-3 dir-rtl col-lg-6 col-12">
                                <h3 class="text-right mb-2"
                                    style="color:#183C6C; font-family:'Bahij-SemiBold',sans-serif;font-size:1rem;">كلمة
                                    مرور</h3>
                                <label for="password" class="sr-only">Password</label>
                                <input dir="rtl" type="password" name="password" id="password"
                                       class="register_input p-3 w-100  @error('password') border-red-500 @enderror"
                                       required>
                                @error ('password')
                                <div class="text-red-500 text-sm text-right"
                                     style="margin:0;font-family: 'Bahij-SemiBold',sans-serif ">
                                    {{$message}}
                                </div>
                                @enderror

                            </div>
                            <div class="mb-3 dir-rtl col-lg-6 col-12 ">
                                <h3 class="text-right mb-2"
                                    style="color:#183C6C; font-family:'Bahij-SemiBold',sans-serif;font-size:1rem;">رمز التحقق
                                </h3>
                                <label for="رمز التحقق" class="sr-only">رمز التحقق</label>
                                <div class="input-group mb-3">
                                    <input dir="rtl" type="tel" name="otp_code" id="otp_code"
                                           class="register_input p-3  @error('otp_code') border-red-500 @enderror"
                                           value="{{old('otp_code')}}" required aria-label="otp_code" aria-describedby="button-addon2">
                                    <button class="btn input-btn" type="button" id="confirm_otp" >تأكيد رمز التحقق</button>
                                </div>
                                @error ('otp_code')
                                <div class="text-red-500 text-sm text-right"
                                     style="margin:0;font-family: 'Bahij-SemiBold',sans-serif ">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div id="disabled" class="disabled-opa-pop">
                            <div class="disable">
                                <div class="mb-4 row">
                                    <h1 class=" mt-12 mb-2 mob-align"
                                        style="color:#192B4B; font-family:'Bahij-Bold',sans-serif; font-size:2.5rem;text-align: right; ">
                                        الباقات المختارة</h1>
                                    <div class="col-lg-4 col-12 d-flex justify-content-center align-center">
                                        <div class="checkout-card messenger"
                                             onclick="this.querySelector('input').checked = !this.querySelector('input').checked;checkbox()">
                                            <img class="img-fluid m-0" src="Baseet/images/6MonthsPlan.svg" alt="">
                                            <h5 style="font-size: 30px ;margin:0.5em 0">6 <span style="float: left;font-size:30px;margin-right: 0.2em;font-family: Bahij-Bold, sans-serif">أشهر</span></h5>
                                            <p style="margin-bottom: 0.5em">أسبوعين مجانا+</p>

                                            {{--                                            <img src="Baseet/images/MessengerIcon3.svg"/>--}}
                                            {{--                                            <h5>عربة تسوق إلكترونية</h5>--}}
                                            <p class="dir-rtl" style="font-size: 1.3em">
                                                {{-- price_format($widget->price * 6) --}}
                                                {{ price_format(210000) }}
                                                <br>
                                                {{--                                                لمدة 6 شهر--}}
                                            </p>
                                            {{--                                            <p class="dir-rtl" style="font-size: 0.9rem;">--}}
                                            {{--                                                +أسبوعين مجاناً--}}
                                            {{--                                            </p>--}}
                                            <input class="messenger-checkbox" name="products[]"
                                                   {{ in_array($facebook->id, request('products', old('products', []))) ? 'checked' : '' }} value="{{ 6 }}"
                                                   type="checkbox" onclick="this.checked=!this.checked"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12 d-flex justify-content-center align-center">
                                        <div class="checkout-card telegram"
                                             onclick="this.querySelector('input').checked = !this.querySelector('input').checked;checkbox()">
                                            <img class="img-fluid m-0" src="Baseet/images/3MonthsPlan.svg" alt="">
                                            <h5 style="font-size: 30px; margin:0.5em 0">3 <span style="float: left;font-size:30px;margin-right: 0.2em;font-family: Bahij-Bold, sans-serif">أشهر</span></h5>
                                            <p style="margin-bottom: 0.5em">أسبوع مجانا+</p>

                                            {{--                                            <img src="Baseet/images/TelegramIcon2.svg"/>--}}
                                            {{--                                            <h5>عربة تسوق إلكترونية</h5>--}}
                                            <p class="dir-rtl" style="font-size:1.3em">
                                                {{-- price_format($widget->price * 3) --}}
                                                {{ price_format(105000) }}
                                                <br>
                                                {{--                                                لمدة 3 شهر--}}
                                            </p>
                                            {{--                                            <p class="dir-rtl" style="font-size: 0.9rem;">--}}
                                            {{--                                                +أسبوع مجاناُ--}}
                                            {{--                                            </p>--}}
                                            <input class="telegram-checkbox" name="products[]"
                                                   {{ in_array($telegram->id, request('products', old('products', []))) ? 'checked' : '' }} value="{{ 3 }}"
                                                   type="checkbox" onclick="this.checked=!this.checked"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12 d-flex justify-content-center align-center">
                                        <div class="checkout-card web"
                                             onclick="this.querySelector('input').checked = !this.querySelector('input').checked;checkbox()">
                                            <img class="img-fluid m-0" src="Baseet/images/1MonthPlan.svg" alt="">
                                            <h5 style="font-size: 30px; margin:0.5em 0">شهر</h5>
                                            <p style="margin-bottom: 0.5em">-</p>

                                            {{--                                            <img src="Baseet/images/WebIcon2.svg"/>--}}
                                            {{--                                            <h5>عربة تسوق إلكترونية</h5>--}}
                                            <p class="dir-rtl" style="font-size: 1.3em">
                                                {{-- price_format($widget->price) --}}
                                                {{ price_format(35000) }}
                                                <br>
                                                {{--                                                لمدة شهر--}}
                                            </p>
                                            {{--                                            <p class="dir-rtl" style="font-size: 0.9rem; color:white">--}}
                                            {{--                                                ...--}}
                                            {{--                                            </p>--}}
                                            <input class="web-checkbox" name="products[]"
                                                   {{ in_array($widget->id, request('products', old('products', [])))  ? 'checked' : '' }} value="{{ 1 }}"
                                                   type="checkbox" onclick="this.checked=!this.checked"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <h1 class="text-right mt-12 mb-3"
                                        style="color:#192B4B; font-family:'Bahij-Bold',sans-serif; font-size:2.5rem; ">طريقة
                                        الدفع</h1>
                                    <div class="accordion dir-rtl " id="accordionExample">
                                        @foreach ($codes as $idx  => $code)
                                            <div class="accordion-item" onclick="this.querySelector('input').checked = true ">
                                                <h2 class="accordion-header" id="heading{{ $code }}">
                                                    <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $code }}"
                                                            aria-expanded="true" aria-controls="collapse{{ $code }}">
                                                        <input class="radioF" name="payment_method" value="{{ $code }}"
                                                               type="radio" onclick="this.checked = !this.checked"/>
                                                        <p style="width: 100%;text-align:start;margin-right:1em;font-family:'Bahij-SemiBold',sans-serif;margin-bottom:0; ">
                                                            {!!  $names[$idx] !!}
                                                        </p>
                                                    </button>
                                                </h2>
                                                <div id="collapse{{ $code }}" class="accordion-collapse collapse "
                                                     aria-labelledby="heading{{ $code }}" data-bs-parent="#accordionExample"
                                                     onclick="toggle(event,{{$idx}})">
                                                    <div class="accordion-body">
                                                        {!! $description[$idx] !!}
                                                    </div>
                                                </div>
                                            </div>

                                        @endforeach
                                        @foreach ($codes as $idx  => $code)
                                                <?php
                                                $payment_method = app(config('payment_methods.' . $code));
                                                ?>
                                            <div id="custom-input-{{$code}}" class="d-none">
                                                @foreach ($payment_method->getExtraUserFields() as $field)

                                                    <div class="mb-2 dir-rtl col-lg-6 col-12 ">
                                                        <h3 class="text-right mb-2"
                                                            style="color:#183C6C; font-family:'Bahij-SemiBold',sans-serif;font-size:1rem;">
                                                            {{ $field['label'] ?? '' }}
                                                        </h3>
                                                        <label for="phoneNumber" class="sr-only">
                                                            {{ $field['label'] ?? '' }}
                                                        </label>
                                                        <input dir="rtl" type="tel" maxlength="10" name="additional_user_fields[{{ $field['name'] }}]"
                                                               id="{{ $field['name'] }}"
                                                               placeholder="09XXXXXXXX"
                                                               class="register_input p-3 w-100  @error($field['name']) border-red-500 @enderror"
                                                               max="10">
                                                        @error ('phone_number')
                                                        <div class="text-red-500 text-sm text-right"
                                                             style="margin:0;font-family: 'Bahij-SemiBold',sans-serif ">
                                                            {{$message}}
                                                        </div>
                                                        @enderror

                                                    </div>
                                                @endforeach
                                            </div>

                                        @endforeach
                                    </div>
                                </div>
                                <div class="row mb-4 px-3">
                                    <h1 class="text-right mt-12 mb-4"
                                        style="color:#192B4B; font-family:'Bahij-Bold',sans-serif; font-size:2.5rem; ">ملخص
                                        الطلب</h1>
                                    <div class="col-6 mb-3">
                                        <p style="text-align:start;font-family:'Bahij-SemiBold',sans-serif;margin-left:2em;color:#808080;">
                                            السعر </p>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <p style="text-align:end;font-family:'Bahij-SemiBold',sans-serif;margin-right:2em;color:#808080;">
                                            اسم الخدمة</p>
                                    </div>
                                    <div class="col-12 item-box p-6 mb-6 web-box d-none">
                                        <div class="row">
                                            <div class="col-6">
                                                <p class="dir-rtl"
                                                   style="text-align: end; font-family:'Bahij-SemiBold',sans-serif;color:#808080;margin-bottom:0;">{{ price_format($widget->price) }} </p>
                                            </div>
                                            <div class="col-6">
                                                <p style="text-align: end; font-family:'Bahij-SemiBold',sans-serif;color:#808080;margin-bottom:0;">
                                                    اشتراك عربة إلكترونية - لمدة شهر</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 item-box p-6 mb-6 tele-box d-none">
                                        <div class="row">
                                            <div class="col-6">
                                                <p class="dir-rtl"
                                                   style="text-align: end; font-family:'Bahij-SemiBold',sans-serif;color:#808080;margin-bottom:0;"> {{ price_format($widget->price * 3) }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p style="text-align: end; font-family:'Bahij-SemiBold',sans-serif;color:#808080;margin-bottom:0;">
                                                    اشتراك عربة إلكترونية - لمدة 3 شهر +أسبوع مجاناً </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 item-box mess-box p-6 mb-6 d-none">
                                        <div class="row">
                                            <div class="col-6">
                                                <p class="dir-rtl"
                                                   style="text-align: end; font-family:'Bahij-SemiBold',sans-serif;color:#808080;margin-bottom:0;"> {{ price_format($widget->price * 6) }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p style="text-align: end; font-family:'Bahij-SemiBold',sans-serif;color:#808080;margin-bottom:0;">
                                                    اشتراك عربة إلكترونية - لمدة 6 شهر +أسبوعين مجاناً</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 item-box p-6 mb-6">
                                        <div class="row">
                                            <div class="col-6">
                                                <p class="dir-rtl" id="total"
                                                   style="text-align: end; font-family:'Bahij-Bold',sans-serif;color:#EA6625;margin-bottom:0;">
                                                    0 ليرة سورية</p>
                                            </div>
                                            <div class="col-6">
                                                <p style="text-align: end; font-family:'Bahij-Bold',sans-serif;color:#808080;margin-bottom:0;">
                                                    :المجموع الكلي</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <p class="mb-4" style="margin-right:0.6em;font-size: 1.3em;text-align: end">بالنقر على إتمام
                                        عملية الشراء فأنا أوافق على <a style="color:#63BEDD; " href="{{route('tos')}}"> شروط
                                            الاستخدام</a> و <a style="color:#63BEDD ;" href="{{route('privacy')}}">سياسة
                                            الخصوصية</a></p>
                                </div>
                                <div class="butt flex align-center justify-end items-center">
                                    <button id="regbutDesk" type="submit" class="bot-2 mb-16"
                                            style="margin-right:0.6em;width:230px; background-color:#EA6625;">إتمام عملية الشراء
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <footer id="footer" class="d-flex justfiy-content-center align-items-center">
                <div class="w-100">
                    <div class="footer-background desk">
                        <img class="img-fluid" src="Baseet/images/DesktopFooterBackground.svg" id="footer-desk-back">
                        <img src="Baseet/images/Path 5892.svg" id="footer-mob-back">
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="footer-col-2" style="align-items: end;">
                            <ul id="about" style="margin-right: 5em;">
                                <li><h3 class="big-src-text">تواصل معنا</h3></li>
                                <li><a>Info@ecart.sy</a></li>
                                <li><a href="tel:+963987209645">+963 987 209 645 </a></li>
                            </ul>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 desk ">
                            <ul id="social" style="justify-content: center;flex-direction: row;display: flex;">
                                <li>
                                    <a href="#">
                                        <img class="img-fluid" src="Baseet/images/TelegramIcon5.png" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a href="https://facebook.com/ecart.sy">
                                        <img class="img-fluid" src="Baseet/images/FacebookIcon.svg" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.instagram.com/ecart.sy">
                                        <img class="img-fluid" src="Baseet/images/InstagramIcon.svg" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img class="img-fluid" src="Baseet/images/WhatsappIcon.png" alt="">
                                    </a>
                                </li>
                            </ul>
                            <h3 id="rights">جميع الحقوق محفوظة لشركة الثعلب الماكر</h3>
                            <h3 id="rights">بالتعاون مع فاتورة</h3>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                            <ul id="about">
                                <li><h3 class="big-src-text">عن إي كارت</h3></li>
                                <li><a href="{{route('tos')}}">اتفاقية الاستخدام</a></li>
                                <li><a href="{{route('privacy')}}">سياسة الخصوصية</a></li>
                            </ul>
                        </div>

                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mob-flex mob">
                            <ul id="social">
                                <li>
                                    <a href="https://t.me/+963987209645">
                                        <img class="img-fluid" src="Baseet/images/TelegramIcon5.png" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a href="https://facebook.com/ecart.sy">
                                        <img class="img-fluid" src="Baseet/images/FacebookIcon.svg" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.instagram.com/ecart.sy">
                                        <img class="img-fluid" src="Baseet/images/InstagramIcon.svg" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a href="https://wa.me/+963987209645">
                                        <img class="img-fluid" src="Baseet/images/WhatsappIcon.png" alt="">
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 mob ord-3">
                            <h3 id="rights">جميع الحقوق محفوظة لشركة الثعلب الماكر</h3>
                            <h3 id="rights">بالتعاون مع فاتورة</h3>
                        </div>
                    </div>
                </div>
            </footer>
            <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ"
            crossorigin="anonymous"></script>
            <script>


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                });

                $('#send_otp').on('click',function (){

                    let phone_number = $('#phoneNumber').val();

                    if(phone_number.length != 10){

                        Swal.fire(
                            "حدث خطأ",
                            "رقم الهاتف يجب أن يكون 10 أرقام",
                            'error'
                        );

                    }else{

                        $.ajax({
                            type:'post',
                            url:'{{ route("register.send_otp") }}',
                            dataType:'json',
                            data: {phone_number: phone_number},
                            success:function (response) {

                                Swal.fire(
                                    "",
                                    "تم إرسال رمز التحقق بنجاح",
                                    'success'
                                );

                                $('#send_otp').addClass('d-none')
                                $('#resend_otp').removeClass('d-none')
                            },
                            error: function(){

                                Swal.fire(
                                    "حدث خطأ",
                                    "رمز التحقق غير صحيح",
                                    'error'
                                );

                            }
                        })

                    }
                })
                $('#resend_otp').on('click',function (){

                    let phone_number = $('#phoneNumber').val();

                    $.ajax({
                        type:'post',
                        url:'{{ route("register.send_otp") }}',
                        dataType:'json',
                        data: {phone_number: phone_number},
                        success:function (response) {

                            Swal.fire(
                                "",
                                "تم إرسال رمز التحقق بنجاح",
                                'success'
                            );
                        },
                        error: function(){

                            Swal.fire(
                                "حدث خطأ",
                                "رمز التحقق غير صحيح",
                                'error'
                            );

                        }
                    })
                })
                $('#confirm_otp').on('click',function (){

                    let otp_code = $('#otp_code').val();

                    console.log(otp_code);
                    $.ajax({
                        type:'post',
                        url:'{{ route("register.validate_otp") }}',
                        dataType:'json',
                        data: {otp_code: otp_code},
                        success:function (response) {

                            Swal.fire(
                                "",
                                "تمت مطابقة الرمز بنجاح",
                                'success'
                            );
                            $('#disabled').removeClass('disabled-opa-pop').data('valid', 'valid');
                            $('.disable').removeClass('disable')

                        },
                        error: function(){

                            Swal.fire(
                                "حدث خطأ",
                                "رمز التحقق غير صحيح",
                                'error'
                            );

                        }
                    })
                })
                $('.disabled-opa-pop').on('click',function (){
                    if (!($('#disabled').data('valid') == 'valid')) {
                        window.scrollTo({top: 0, behavior: 'smooth'});
                        Swal.fire({
                            title: '',
                            text: "الرجاء إدخال رمز التحقق",
                            icon: 'error',
                        })
                    }

                })
            </script>
            <script>
                // Toggle Accordion Item #1
                function toggle(event, idx) {
                    var myCollapse = document.getElementsByClassName('collapse')[idx];
                    var bsCollapse = new bootstrap.Collapse(myCollapse, {
                        toggle: true
                    });
                }
            </script>
            <script>

                let web = 0
                let tele = 0
                let mess = 0
                let total = 0

                function checkbox() {
                    let once = 0
                    if (document.querySelector('.messenger-checkbox').checked) {
                        // document.querySelector('.messenger img').src = '/Baseet/images/MessengerIcon.svg'
                        document.querySelector('.messenger h5').style.color = '#49CEE6'
                        document.querySelector('.messenger p').style.color = '#49CEE6'
                        document.querySelector('.mess-box').classList.remove('d-none');
                        if (once === 0 && mess === 0) {
                            once = 1;
                            mess = 1;
                            total = total + {{$widget->price * 6}};
                        }
                        document.getElementById("total").innerHTML = total.toLocaleString() + '' + 'ليرة سورية';
                    } else {
                        // document.querySelector('.messenger img').src = 'Baseet/images/MessengerIcon3.svg'
                        document.querySelector('.messenger h5').style.color = '#2B415D'
                        document.querySelector('.messenger p').style.color = '#2B415D'
                        document.querySelector('.mess-box').classList.add('d-none');
                        if (once === 0 && mess === 1) {
                            once = 1;
                            mess = 0;
                            total = total - {{$widget->price * 6}};
                        }
                        total.toLocaleString();
                        document.getElementById("total").innerHTML = total.toLocaleString() + ' ' + 'ليرة سورية';

                    }
                    if (document.querySelector('.telegram-checkbox').checked) {
                        // document.querySelector('.telegram img').src = '/Baseet/images/TelegramIcon4.svg'
                        document.querySelector('.telegram h5').style.color = '#49CEE6'
                        document.querySelector('.telegram p').style.color = '#49CEE6'
                        document.querySelector('.tele-box').classList.remove('d-none')
                        if (once === 0 && tele === 0) {
                            once = 1;
                            tele = 1;
                            total = total + {{$widget->price * 3}};

                        }
                        total.toLocaleString();

                        document.getElementById("total").innerHTML = total.toLocaleString() + ' ' + 'ليرة سورية';


                    } else {
                        // document.querySelector('.telegram img').src = 'Baseet/images/TelegramIcon2.svg'
                        document.querySelector('.telegram h5').style.color = '#2B415D'
                        document.querySelector('.telegram p').style.color = '#2B415D'
                        document.querySelector('.tele-box').classList.add('d-none')
                        if (once === 0 && tele === 1) {
                            once = 1;
                            tele = 0;
                            total = total - {{$widget->price * 3}};
                        }
                        total.toLocaleString();

                        document.getElementById("total").innerHTML = total.toLocaleString() + ' ' + 'ليرة سورية';

                    }
                    if (document.querySelector('.web-checkbox').checked) {
                        // document.querySelector('.web img').src = 'Baseet/images/WebIcon.svg'
                        document.querySelector('.web h5').style.color = '#49CEE6'
                        document.querySelector('.web p').style.color = '#49CEE6'
                        document.querySelector('.web-box').classList.remove('d-none')
                        if (once === 0 && web === 0) {
                            once = 1;
                            web = 1;
                            total = total + {{$widget->price}};

                        }
                        total.toLocaleString();

                        document.getElementById("total").innerHTML = total.toLocaleString() + ' ' + 'ليرة سورية';


                    } else {
                        // document.querySelector('.web img').src = 'Baseet/images/WebIcon2.svg'
                        document.querySelector('.web h5').style.color = '#2B415D'
                        document.querySelector('.web p').style.color = '#2B415D'
                        document.querySelector('.web-box').classList.add('d-none')
                        if (once === 0 && web === 1) {
                            once = 1;
                            web = 0;
                            total = total - {{$widget->price}};
                        }
                        total.toLocaleString();

                        document.getElementById("total").innerHTML = total.toLocaleString() + " " + 'ليرة سورية';

                    }
                }
                @if (request()->has('products') || old('products'))
                let products = '{{ count(old("products", [1])) }}';
                for (let i = 0; i < products; ++i)
                    checkbox();
                @endif
            </script>
            <script>
                const targetDiv = document.getElementById("popup");
                const btn = document.getElementById("regbut");
                regbutDesk.onsubmit = function () {
                    if (targetDiv.style.display !== "none") {
                        targetDiv.style.display = "none";
                    } else {
                        targetDiv.style.display = "block";
                    }
                };
                document.querySelector('#accordionExample').addEventListener('click', function (e) {
                    let input_value = document.querySelector('input[name="payment_method"]:checked').value;
                    let custom_inputs = document.querySelectorAll('div[id^=custom-input]');
                    for (let i = 0; i < custom_inputs.length; ++i)
                        custom_inputs[i].classList.add('d-none');
                    let custom_input = document.querySelector(`#custom-input-${input_value}`);
                    if (custom_input) {
                        custom_input.classList.remove('d-none');
                    }
                });

            </script>
@endsection

