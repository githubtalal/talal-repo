<?php
$facebook = \App\Models\Product::query()->withoutGlobalScope('store_access')->where('additional->permission_name', 'messenger_bot')->first();
$telegram = \App\Models\Product::query()->withoutGlobalScope('store_access')->where('additional->permission_name', 'telegram_bot')->first();
$widget = \App\Models\Product::query()->withoutGlobalScope('store_access')->where('additional->permission_name', 'website_widget')->first();
?>

@extends('layouts.footer')

@section('footer')

@endsection

@extends('layouts.contactUs')

@section('contactUs')

@endsection

@extends('layouts.app')

@section('content')

    <section id="hero" class="">
        <div class=" hero-mob" style="height:72vh">
            <div class="desk">
                <div class="row big-src-mar" style="margin:0 4em;">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12  p-0 ">
                        <img class="img-fluid-2" src="Baseet/images/WebStoreSlider.svg"
                             id="hero-img"/>
                    </div>
                    <div class=" col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12 ord-1 p-0 mt-2h-big " id="">
                        <h1 class="h1-1 mtop-1 h1-1-mob text-right"> أضف عربة التسوق إلى أي <span>موقع إلكتروني </span> <br>
                            مع إمكانية <span style="color:#5FC3E1;">الدفع أونلاين</span>
                        </h1>


                        <h3 class="h3-1 mtop-1">حول أي موقع إلكتروني إلى متجر إلكتروني يُمكنك من بيع منتجاتك أو تثبيت الحجوزات لزبائنك عن طريق الانترنت مع إمكانية الدفع أونلاين وإدارة جميع الطلبات. <br>
                        </h3>

                        <div class="row w-100 m-0">
                            <div class="col-12 know-more-btn mtop-2 p-0">
                                <a href="{{route('register')}}"> <button type="button" class="bot-2">إشترك الآن </button> </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mob">
                <div class="row" >
                    <div class="col-12 mtop-2 p-0">
                        <h1 class="h1-1 h1-1-mob" > أضف عربة التسوق إلى أي <span>موقع إلكتروني </span> <br>
                            مع إمكانية <span style="color:#5FC3E1;">الدفع أونلاين</span>
                        </h1>
                    </div>
                    <div class="col-12 p-0 ">
                        <img class="img-fluid-2" src="Baseet/images/WebStoreSlider.svg"
                             id="hero-img"/>
                    </div>
                    <div class="col-12 p-0 mt-1-mob" id="">
                        <h3 class="h3-mob ">حول أي موقع إلكتروني إلى متجر إلكتروني يُمكنك من بيع منتجاتك أو تثبيت الحجوزات لزبائنك عن طريق الانترنت مع إمكانية الدفع أونلاين وإدارة جميع الطلبات. <br></h3>

                        <div class="row w-100 m-0">
                            <div class="col-12 know-more-btn mtop-2 p-0">
                                <a href="{{route('register')}}"> <button type="button" class="bot-2">إشترك الآن </button> </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
{{--            <div class="swiper mySwiper4 desk">--}}
{{--                <div class="swiper-wrapper">--}}
{{--                    <div class="swiper-slide">--}}
{{--                        <div class="row big-src-mar" style="margin:0 4em;">--}}
{{--                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12 p-0 ord-2">--}}
{{--                                <img class="img-fluid-2" src="Baseet/images/MessangerStoreSlider.svg"--}}
{{--                                     id="hero-img"/>--}}
{{--                            </div>--}}
{{--                            <div class=" col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12 ord-1 p-0 mt-5-big mtop-1h " id="">--}}
{{--                                <h1 class="h1-1 mtop-1 h1-1-mob text-right">امتلك الآن متجر إلكتروني متكامل  <br>--}}
{{--                                    يدعم <span> الدفع الإلكتروني</span> على <span style="color:#5FC3E1;">فيسبوك</span>--}}
{{--                                </h1>--}}

{{--                                <h3 class="h3-1 mtop-1">قم بإضافة بوت إي كارت إلى صفحتك على فيسبوك واسمح لزبائنك بتصفح وشراء منتجاتك عن طريق المسنجر مع إمكانية الدفع الإلكتروني وإدارة كاملة لطلبات الزبائن. <br>--}}
{{--                                </h3>--}}

{{--                                <div class="row w-100 m-0">--}}
{{--                                    <div class="col-12 know-more-btn mtop-2 p-0">--}}
{{--                                        <a href="{{route('register')}}"> <button type="button" class="bot-2">إشترك الآن </button> </a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="swiper-slide">--}}
{{--                        <div class="row big-src-mar" style="margin:0 4em;">--}}
{{--                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12 p-0 ord-2">--}}
{{--                                <img class="img-fluid-2" src="Baseet/images/TelegramStoreSlider.svg"--}}
{{--                                     id="hero-img"/>--}}
{{--                            </div>--}}
{{--                            <div class=" col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12 ord-1 p-0 mt-5-big mtop-1h" id="">--}}
{{--                                <h1 class="h1-1 mtop-1 h1-1-mob text-right"> امتلك الآن متجر إلكتروني متكامل  <br>--}}
{{--                                    يدعم <span> الدفع الإلكتروني</span> على <span style="color:#5FC3E1;">تيلجرام</span>--}}
{{--                                </h1>--}}
{{--                                <h3 class="h3-1 mtop-1">مكّن زبائنك من تصفح كامل منتجاتك أو طلب حجوزات عن طريق بوت تيلجرام يعمل على منصة إي كارت مع دعم لطرق الدفع الإلكتروني وإدارة كاملة لطلبات الزبائن. <br>--}}
{{--                                </h3>--}}
{{--                                <div class="row w-100 m-0">--}}
{{--                                    <div class="col-12 know-more-btn mtop-2 p-0">--}}
{{--                                        <a href="{{route('register')}}"> <button type="button" class="bot-2">إشترك الآن </button> </a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="swiper-slide">--}}
{{--                        <div class="row big-src-mar" style="margin:0 4em;">--}}
{{--                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12  p-0 ">--}}
{{--                                <img class="img-fluid-2" src="Baseet/images/WebStoreSlider.svg"--}}
{{--                                     id="hero-img"/>--}}
{{--                            </div>--}}
{{--                            <div class=" col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12 ord-1 p-0 mt-2h-big " id="">--}}
{{--                                <h1 class="h1-1 mtop-1 h1-1-mob text-right"> أضف عربة التسوق إلى أي <span>موقع إلكتروني </span> <br>--}}
{{--                                    مع إمكانية <span style="color:#5FC3E1;">الدفع أونلاين</span>--}}
{{--                                </h1>--}}


{{--                                <h3 class="h3-1 mtop-1">حول أي موقع إلكتروني إلى متجر إلكتروني يُمكنك من بيع منتجاتك أو تثبيت الحجوزات لزبائنك عن طريق الانترنت مع إمكانية الدفع أونلاين وإدارة جميع الطلبات. <br>--}}
{{--                                </h3>--}}

{{--                                <div class="row w-100 m-0">--}}
{{--                                    <div class="col-12 know-more-btn mtop-2 p-0">--}}
{{--                                        <a href="{{route('register')}}"> <button type="button" class="bot-2">إشترك الآن </button> </a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
            {{--not needed when removing commnets --}}
{{--                    <div class="swiper-slide">--}}
{{--                        <div class="row big-src-mar" style="margin:0 4em;">--}}
{{--                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12  p-0 ">--}}
{{--                                <img class="img-fluid-2" src="Baseet/images/GetBack5%.svg"--}}
{{--                                     id="hero-img"/>--}}
{{--                            </div>--}}
{{--                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12 ord-1 p-0 mt-5-big" id="">--}}
{{--                                <h1 class="h1-1 mtop-2 h1-1-mob text-right"> استخدم بوابة الدفع الالكتروني <span style="color:#5FC3E1;">من فاتورة </span> <br>--}}
{{--                                    واحصل على استرداد نقدي <span> بقيمة %5 </span>--}}
{{--                                </h1>--}}


{{--                                <h3 class="h3-1 mtop-1">الاسترداد المستحق هو فقط على المبالغ المتلقاة عبر بوابة فاتورة.--}}
{{--                                    هذا العرض لا يلغي رسوم بوابة فاتورة (1.5% من المبلغ). <br>--}}
{{--                                </h3>--}}

{{--                                <div class="row w-100 m-0">--}}
{{--                                    <div class="col-12 know-more-btn mtop-2 p-0">--}}
{{--                                        <a href="{{route('register')}}"> <button type="button" class="bot-2">إشترك الآن </button> </a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
            {{--to here--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="swiper mySwiper5 mob">--}}
{{--                <div class="swiper-wrapper">--}}
{{--                    <div class="swiper-slide">--}}
{{--                        <div class="row">--}}
{{--                            <div class="mtop-2 p-0 col-12">--}}
{{--                                <h1 class="h1-1 h1-1-mob" >امتلك الآن متجر إلكتروني متكامل  <br>--}}
{{--                                    يدعم <span> الدفع الإلكتروني</span> على <span style="color:#5FC3E1;">فيسبوك</span>--}}
{{--                                </h1>--}}
{{--                            </div>--}}
{{--                            <div class="col-12 p-0 ">--}}
{{--                                <img class="img-fluid-2" src="Baseet/images/MessangerStoreSlider.svg"--}}
{{--                                     id="hero-img"/>--}}
{{--                            </div>--}}
{{--                            <div class="col-12 p-0 mt-1-mob" id="">--}}

{{--                                <h3 class="h3-mob">قم بإضافة بوت إي كارت إلى صفحتك على فيسبوك واسمح لزبائنك بتصفح وشراء منتجاتك عن طريق المسنجر مع إمكانية الدفع الإلكتروني وإدارة كاملة لطلبات الزبائن. <br>--}}
{{--                                </h3>--}}

{{--                                <div class="row w-100 m-0">--}}
{{--                                    <div class="col-12 know-more-btn mtop-2 p-0">--}}
{{--                                        <a href="{{route('register')}}"> <button type="button" class="bot-2">إشترك الآن </button> </a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="swiper-slide">--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-12 p-0 mtop-2">--}}
{{--                                <h1 class="h1-1  h1-1-mob" > امتلك الآن متجر إلكتروني متكامل  <br>--}}
{{--                                    يدعم <span> الدفع الإلكتروني</span> على <span style="color:#5FC3E1;">تيلجرام</span>--}}
{{--                                </h1>--}}
{{--                            </div>--}}
{{--                            <div class="col-12 p-0 ">--}}
{{--                                <img class="img-fluid-2" src="Baseet/images/TelegramStoreSlider.svg"--}}
{{--                                     id="hero-img"/>--}}
{{--                            </div>--}}
{{--                            <div class="col-12 p-0 mt-1-mob" id="">--}}

{{--                                <h3 class="h3-mob">مكّن زبائنك من تصفح كامل منتجاتك أو طلب حجوزات عن طريق بوت تيلجرام يعمل على منصة إي كارت مع دعم لطرق الدفع الإلكتروني وإدارة كاملة لطلبات الزبائن. <br></h3>--}}
{{--                            </div>--}}
{{--                            <div class="row w-100 m-0">--}}
{{--                                <div class="col-12 know-more-btn mtop-2 p-0">--}}
{{--                                    <a href="{{route('register')}}"> <button type="button" class="bot-2">إشترك الآن </button> </a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="swiper-slide">--}}
{{--                        <div class="row" >--}}
{{--                            <div class="col-12 mtop-2 p-0">--}}
{{--                                <h1 class="h1-1 h1-1-mob" > أضف عربة التسوق إلى أي <span>موقع إلكتروني </span> <br>--}}
{{--                                    مع إمكانية <span style="color:#5FC3E1;">الدفع أونلاين</span>--}}
{{--                                </h1>--}}
{{--                            </div>--}}
{{--                            <div class="col-12 p-0 ">--}}
{{--                                <img class="img-fluid-2" src="Baseet/images/WebStoreSlider.svg"--}}
{{--                                     id="hero-img"/>--}}
{{--                            </div>--}}
{{--                            <div class="col-12 p-0 mt-1-mob" id="">--}}
{{--                                <h3 class="h3-mob ">حول أي موقع إلكتروني إلى متجر إلكتروني يُمكنك من بيع منتجاتك أو تثبيت الحجوزات لزبائنك عن طريق الانترنت مع إمكانية الدفع أونلاين وإدارة جميع الطلبات. <br></h3>--}}

{{--                                <div class="row w-100 m-0">--}}
{{--                                    <div class="col-12 know-more-btn mtop-2 p-0">--}}
{{--                                        <a href="{{route('register')}}"> <button type="button" class="bot-2">إشترك الآن </button> </a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
            {{--not needed when removing commnets --}}
{{--                    <div class="swiper-slide">--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-12 mtop-4 p-0">--}}
{{--                                <h1 class="h1-1 h1-1-mob" > استخدم بوابة الدفع الالكتروني <span style="color:#5FC3E1;">من فاتورة</span> <br>--}}
{{--                                    واحصل على استرداد نقدي <span>قيمة %5</span>--}}
{{--                                </h1>--}}
{{--                            </div>--}}
{{--                            <div class="col-12 p-0 ">--}}
{{--                                <img class="img-fluid-2" src="Baseet/images/GetBack5%.svg"--}}
{{--                                     id="hero-img"/>--}}
{{--                            </div>--}}
{{--                            <div class="col-12 p-0 " id="">--}}
{{--                                <h3 class="h3-mob ">الاسترداد المستحق هو فقط على المبالغ المتلقاة عبر بوابة فاتورة.--}}
{{--                                    هذا العرض لا يلغي رسوم بوابة فاتورة (1.5% من المبلغ).</h3>--}}

{{--                                <div class="row w-100 m-0">--}}
{{--                                    <div class="col-12 know-more-btn mtop-4 p-0">--}}
{{--                                        <a href="{{route('register')}}"> <button type="button" class="bot-2">إشترك الآن </button> </a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
            {{--to here}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </section>
    <section id="basset-packages" class="d-flex justfiy-content-center align-items-center position-relative">
{{--        <img class="position-absolute why-back" src="Baseet/images/HomeBackgroundImg.svg"/>--}}

        <div class="container" style="padding: 0; margin: 0;max-width: 100%;">
            <h1 class="h1-pack">باقات إي كارت</h1>
            <div class="row mbottom-2">

                <div class="col-lg-4 col-12 d-flex align-center pack1" style="justify-content:center ">
                    <a onclick="window.location.href = '{{ route('register', ['products' => [$facebook->id]]) }}'">
                        <div class="package-card">
                            <img class="img-fluid m-0" src="Baseet/images/6MonthsPlan.svg" alt="">
                            <h5 style="font-size: 30px ;margin:0.5em 0">6 <span style="float: left;font-size:30px;margin-right: 0.2em;font-family: Bahij-Bold, sans-serif">أشهر</span></h5>
                            {{--                            <img src="Baseet/images/MessengerIcon.svg"/>--}}
                            {{--                            <h5 class="mb-2">عربة تسوق لموقع إلكتروني</h5>--}}
                            <p style="margin-bottom: 0.5em;font-size: 1em">+أسبوعين مجانا</p>

                            <p style="font-size: 1.3em;margin-bottom: 0.5em;">
                                {{ price_format($facebook->price)  }}

                                {{--                            سعر 35.000 ليرة سورية/شهرياً--}}
                            </p>
                            {{--                            <p style="font-size: 0.9rem;">--}}
                            {{--                                لمدة 6 أشهر +أسبوعين مجاناُ--}}
                            {{--                            </p>--}}
                            <button >إشترك الآن </button>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-12 d-flex align-center pack2" style="justify-content:center ">
                    <a onclick="window.location.href = '{{ route('register', ['products' => [$telegram->id]]) }}'">
                        <div class="package-card">
                            <img class="img-fluid m-0" src="Baseet/images/3MonthsPlan.svg" alt="">
                            <h5 style="font-size: 30px; margin:0.5em 0">3 <span style="float: left;font-size:30px;margin-right: 0.2em;font-family: Bahij-Bold, sans-serif">أشهر</span></h5>
{{--                            <img src="Baseet/images/TelegramIcon4.svg"/>--}}
{{--                            <h5 class="mb-2"> عربة تسوق لموقع إلكتروني</h5>--}}
                            <p style="margin-bottom: 0.5em;font-size: 1em">+أسبوع مجانا</p>

                            <p style="font-size: 1.3em;margin-bottom: 0.5em;">
                                {{ price_format($telegram->price)  }}
                                {{--                            سعر 35.000 ليرة سورية/شهرياً--}}
                            </p>
{{--                            <p style="font-size: 0.9rem;">--}}
{{--                                لمدة 3 أشهر +أسبوع مجاناُ--}}
{{--                            </p>--}}
                            <button >إشترك الآن </button>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-12 d-flex align-center pack3" style="justify-content:center ">
                    <a onclick="window.location.href = '{{ route('register', ['products' => [$widget->id]]) }}'">
                        <div class="package-card">
                            <img class="img-fluid m-0" src="Baseet/images/1MonthPlan.svg" alt="">
                            <h5 style="font-size: 30px; margin:0.5em 0">شهر</h5>
                            <p style="margin-bottom: 0.5em;font-size: 1em">-</p>
                            {{--                            <img src="Baseet/images/WebIcon.svg"/>--}}
                            {{--                            <h5 class="mb-2">عربة تسوق لموقع إلكتروني</h5>--}}
                            <p style="font-size: 1.3em;margin-bottom: 0.5em;">
                                {{ price_format($widget->price)  }}
                                {{--                            سعر 35.000 ليرة سورية/شهرياً--}}
                            </p>
                            {{--                            <p style="font-size: 0.9rem;">--}}
                            {{--                                لمدة شهر--}}
                            {{--                            </p>--}}
                            <button >إشترك الآن </button>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>
    <section id="why-basset" class="d-flex justfiy-content-center align-items-center position-relative">
        <img class="position-absolute why-back" src="Baseet/images/HomeBackgroundImg.svg" style="transform: rotate(180deg);right:0;left:unset;top:-500px"/>

        {{--        <div class="container" data-aos="fade-up">--}}
            <div class="container">
            <div class="row" id="row1">
                <div class="col-12">
                    <h1 class="mob-title">لماذا إي كارت؟</h1>
                    <img class="img-fluid" src="Baseet/images/UnderlineImg.svg" alt="" id="underline">
                </div>
            </div>
            <div class="row" id="row2">
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                    <img class="img-fluid" src="{{ asset('icons/reports.svg') }}" alt="">
                    <h4 class="dir-rtl">تقارير مبيعات تفصيلية واحترافية</h4>
                    <h6 class="dir-rtl">احصل على تقارير قيَمة عن مبيعاتك بشكل يومي وشهري لتساعدك في مراقبة أداء المتجر واتخاذ أفضل القرارات لتطوير عملك
                    </h6>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                    <img class="img-fluid" src="{{ asset('icons/payment.svg') }}" alt="">
                    <h4 class="dir-rtl">طرق دفع متعددة </h4>
                    <h6 class="dir-rtl">في إي كارت نوفر لك مجموعة واسعة من طرق الدفع المتوافرة في سورية بدءًا من الدفع الإلكتروني عن طريق (شبكة فاتورة و سيرياتيل كاش و كاش موبايل) - الدفع عن طريق الهرم - الدفع عند الاستلام.</h6>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                    <img src="{{ asset('icons/24.svg') }}" alt="">
                    <h4 class="dir-rtl">متجرك يعمل على مدار الساعة</h4>
                    <h6 class="dir-rtl">افتح الفرصة أمام زبائنك لتصفح منتجاتك وخدماتك في أي وقت واستلم طلباتهم على مدار 24 ساعة</h6>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                    <img class="img-fluid" src="{{ asset('icons/manage.svg') }}" alt="">
                    <h4 class="dir-rtl">سهولة في إدارة الفئات والمنتجات</h4>
                    <h6 class="dir-rtl">ستتمكن من إدراج وإدارة منتجاتك مهما كان نوع هذه المنتجات سواءً منتجات بضائع أو حجوزات أو منتجات رقمية وغيرها بكل سهولة</h6>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                    <img class="img-fluid" src="{{ asset('icons/orders.svg') }}" alt="">
                    <h4 class="dir-rtl">إدارة طلبات الزبائن في مكان واحد</h4>
                    <h6 class="dir-rtl">يمكنك متابعة جميع طلبات زبائنك و معالجتها في مكان واحد لضمان تقديم أفضل خدمة ممكنة لزبائنك </h6>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                    <img class="img-fluid" src="{{ asset('icons/dashboard.svg') }}" alt="">
                    <h4 class="dir-rtl">لوحة تحكم سهلة الاستخدام </h4>
                    <h6 class="dir-rtl">تم تصميمها بعناية لتكون بسيطة وسهلة الاستخدام لجميع المشتركين حيث يمكنك إضافة خدماتنا لحساباتك والبدء بالبيع خلال بضع دقائق</h6>
                </div>
            </div>
            <div class="row" id="row3">
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                    <img class="img-fluid" src="{{ asset('icons/24.svg') }}" alt="">
                    <h4 class="dir-rtl">متجرك يعمل على مدار الساعة</h4>
                    <h6 class="dir-rtl">افتح الفرصة أمام زبائنك لتصفح منتجاتك وخدماتك في أي وقت واستلم طلباتهم على مدار 24 ساعة</h6>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                    <img class="img-fluid" src="{{ asset('icons/payment.svg') }}" alt="">
                    <h4 class="dir-rtl">طرق دفع متعددة </h4>
                    <h6 class="dir-rtl">في إي كارت نوفر لك مجموعة واسعة من طرق الدفع المتوافرة في سورية بدءًا من الدفع الإلكتروني عن طريق (شبكة فاتورة و سيرياتيل كاش و كاش موبايل) - الدفع عن طريق الهرم - الدفع عند الاستلام.</h6>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                    <img class="img-fluid" src="{{ asset('icons/manage.svg') }}" alt="">
                    <h4 class="dir-rtl">سهولة في إدارة الفئات والمنتجات</h4>
                    <h6 class="dir-rtl">ستتمكن من إدراج وإدارة منتجاتك مهما كان نوع هذه المنتجات سواءً منتجات بضائع أو حجوزات أو منتجات رقمية وغيرها بكل سهولة</h6>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                    <img class="img-fluid" src="{{ asset('icons/orders.svg') }}" alt="">
                    <h4 class="dir-rtl">إدارة طلبات الزبائن في مكان واحد</h4>
                    <h6 class="dir-rtl">يمكنك متابعة جميع طلبات زبائنك و معالجتها في مكان واحد لضمان تقديم أفضل خدمة ممكنة لزبائنك </h6>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                    <img class="img-fluid" src="{{ asset('icons/reports.svg') }}" alt="">
                    <h4 class="dir-rtl">تقارير مبيعات تفصيلية واحترافية</h4>
                    <h6 class="dir-rtl">احصل على تقارير قيَمة عن مبيعاتك بشكل يومي وشهري لتساعدك في مراقبة أداء المتجر واتخاذ أفضل القرارات لتطوير عملك
                    </h6>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                    <img class="img-fluid" src="{{ asset('icons/dashboard.svg') }}" alt="">
                    <h4 class="dir-rtl">لوحة تحكم سهلة الاستخدام </h4>
                    <h6 class="dir-rtl">تم تصميمها بعناية لتكون بسيطة وسهلة الاستخدام لجميع المشتركين حيث يمكنك إضافة خدماتنا لحساباتك والبدء بالبيع خلال بضع دقائق</h6>
                </div>
            </div>
        </div>
    </section>
{{--    <section id="services" class="">--}}
{{--        <div class="container">--}}
{{--            <div class="row dir-rtl">--}}
{{--                <div class="col-lg-6 col-12">--}}
{{--                    <h1 class="text-right mb-4 mob-title mob-center" style="color:#1A2B4C; font-family:'Bahij-ExtraBold',sans-serif; font-size:3.5rem; margin-right: 0.7em; ">استعراض خدماتنا على</h1>--}}
{{--                    <div class="d-flex align-items-start desk-margin">--}}
{{--                    <div class="nav nav-pills w-100 me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">--}}
{{--                        <button class="nav-link active mb-3" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">--}}
{{--                            <div class="row w-100">--}}
{{--                                <div class="col-lg-2 col-12" style="display: flex;justify-content: end">--}}
{{--                                <div class="icon active">--}}
{{--                                        <img src="Baseet/images/MessengerIcon4.svg"/>--}}
{{--                                </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-lg-10 desk p-0" >--}}
{{--                                <div class="tab-text active">--}}
{{--                                    <p class="dir-rtl">متجر مسنجر</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            </div>--}}
{{--                        </button>--}}
{{--                        <button class="nav-link mb-3" onclick="swiper2.autoplay.running = true;swiper2.autoplay.start();console.log(swiper2.autoplay)" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">--}}
{{--                            <div class="row w-100">--}}
{{--                                <div class="col-lg-2 col-12" style="display: flex;justify-content: end">--}}
{{--                                    <div class="icon ">--}}
{{--                                        <img src="Baseet/images/TelegramIcon2.svg"/>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-lg-10 desk p-0" >--}}
{{--                                    <div class="tab-text">--}}
{{--                                        <p class="dir-rtl">متجر تيلجرام</p>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </button>--}}
{{--                        --}}{{--not needed when removing commnets --}}
{{--                        --}}
{{--                        <button class="nav-link mb-3" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">--}}
{{--                            <div class="row w-100">--}}
{{--                                <div class="col-lg-2 col-12" style="display: flex;justify-content: end">--}}
{{--                                    <div class="icon">--}}
{{--                                        <img src="Baseet/images/WebIcon3.svg"/>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-lg-10 desk p-0">--}}
{{--                                    <div class="tab-text">--}}
{{--                                        <p class="dir-rtl">إضافة متجر لموقع إلكتروني</p>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                </div>--}}
{{--                <div class="col-lg-6 col-12 position-relative">--}}
{{--                    <div class="d-flex align-items-center w-100 h-100 margin-big">--}}
{{--                    <div class="tab-content" id="v-pills-tabContent">--}}
{{--                        <div class="tab-pane fade show active d-flex position-relative center-mob" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0">--}}
{{--                            <img src="Baseet/images/MobileLayout.png" class="w-50 mob-layout"/>--}}
{{--                            <div class="swiper swiper2 mySwiper position-absolute">--}}
{{--                                    <div class="swiper-wrapper ">--}}
{{--                                        <div class="swiper-slide"><img src="Baseet/images/MessengerSliderImg1.png"/></div>--}}
{{--                                        <div class="swiper-slide"><img src="Baseet/images/MessengerSliderImg2.png"/></div>--}}
{{--                                        <div class="swiper-slide"><img src="Baseet/images/MessengerSliderImg3.png"/></div>--}}
{{--                                        <div class="swiper-slide"><img src="Baseet/images/MessengerSliderImg4.png"/></div>--}}
{{--                                        <div class="swiper-slide"><img src="Baseet/images/MessengerSliderImg5.png"/></div>--}}
{{--                                        <div class="swiper-slide"><img src="Baseet/images/MessengerSliderImg6.png"/></div>--}}

{{--                                    </div>--}}
{{--                                    <div class="swiper-pagination"></div>--}}
{{--                                </div>--}}
{{--                            <img src="Baseet/images/CharacterHoldingMob.png" class="char" style="width: 33%">--}}
{{--                        </div>--}}
{{--                        <div class="tab-pane fade d-flex center-mob" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0">--}}
{{--                            <img src="Baseet/images/Mobile-Telegram.png" class=" mob-layout d-none"/>--}}
{{--                            <div class="swiper swiper2 mySwiper2 position-absolute d-none">--}}
{{--                                <div class="swiper-wrapper ">--}}
{{--                                    <div class="swiper-slide"><img src="Baseet/images/TelegramSliderImg1.png"/></div>--}}
{{--                                    <div class="swiper-slide"><img src="Baseet/images/TelegramSliderImg2.png"/></div>--}}
{{--                                    <div class="swiper-slide"><img src="Baseet/images/TelegramSliderImg3.png"/></div>--}}
{{--                                    <div class="swiper-slide"><img src="Baseet/images/TelegramSliderImg4.png"/></div>--}}
{{--                                    <div class="swiper-slide"><img src="Baseet/images/TelegramSliderImg5.png"/></div>--}}
{{--                                    <div class="swiper-slide"><img src="Baseet/images/TelegramSliderImg6.png"/></div>--}}
{{--                                    <div class="swiper-slide"><img src="Baseet/images/TelegramSliderImg7.png"/></div>--}}

{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <img src="Baseet/images/CharacterHoldingMob.png" class="char2 d-none" style="width: 33%">--}}
{{--                        </div>--}}
{{--                        </div>--}}
{{--                        --}}{{--not needed when removing commnets --}}
{{--                        <div class="tab-pane fade d-flex center-mob" id="v-pills-messages" role="tabpanel" aria-labelledby="#v-pills-messages" tabindex="0">--}}
{{--                            <img class="mob-layout d-none" src="Baseet/images/mobile.png"/>--}}
{{--                            <div class="swiper swiper2 mySwiper3 position-absolute d-none">--}}
{{--                                <div class="swiper-wrapper ">--}}
{{--                                    <div class="swiper-slide"><img src="Baseet/images/Slider-1.png"/></div>--}}
{{--                                    <div class="swiper-slide"><img src="Baseet/images/Slider-2.png"/></div>--}}
{{--                                    <div class="swiper-slide"><img src="Baseet/images/Slider-3.png"/></div>--}}
{{--                                    <div class="swiper-slide"><img src="Baseet/images/Slider-4.png"/></div>--}}
{{--                                    <div class="swiper-slide"><img src="Baseet/images/Slider-5.png"/></div>--}}
{{--                                    <div class="swiper-slide"><img src="Baseet/images/Slider-6.png"/></div>--}}
{{--                                    <div class="swiper-slide"><img src="Baseet/images/Slider-7.png"/></div>--}}
{{--                                </div>--}}
{{--                                <div class="swiper-pagination"></div>--}}
{{--                            </div>--}}
{{--                            <img src="Baseet/images/chaaar.png" class="char3 d-none" style="width: 33%">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--    </section>--}}

    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ"
            crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script>
        var swiper = new Swiper(".mySwiper", {
            direction: "vertical",
            allowTouchMove:true,
            scrollbar: {
                draggable: true,
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
        });
        var swiper2 = new Swiper(".mySwiper2", {
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            direction: "vertical",
            allowTouchMove:true,
            scrollbar: {
                draggable: true,
            },
        });

        // no need to uncomment
        // var swiper3 = new Swiper(".mySwiper3", {
        //     direction: "vertical",
        //     allowTouchMove:true,
        //     scrollbar: {
        //         draggable: true,
        //     },
        //     autoplay: {
        //         delay:4000,
        //         disableOnInteraction: false,
        //     },
        // });
        //to here

        var swiper4 = new Swiper(".mySwiper4", {
            allowTouchMove:true,
            scrollbar: {
                draggable: true,
            },
            loop: true,
            autoplay: {
                delay:7000,
                disableOnInteraction: false,
            },
        });
        var swiper5 = new Swiper(".mySwiper5", {
            allowTouchMove:true,
            scrollbar: {
                draggable: true,
            },
            loop: true,
            autoplay: {
                delay:7000,
                disableOnInteraction: false,
            },
        });

        $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function (e) {
            switch (e.target.id){
                case "v-pills-profile-tab":{
                    swiper2.slideTo(0,false,false);
                    $('.tab-text').removeClass('active')
                    $('.icon').removeClass('active')
                    $('#v-pills-home-tab .icon img').attr('src','Baseet/images/MessengerIcon2.svg');
                    $('#v-pills-messages-tab .icon img').attr('src','Baseet/images/WebIcon3.svg');
                    $('#v-pills-profile-tab .tab-text').addClass('active');
                    $('#v-pills-profile-tab .icon').addClass('active');
                    $('#v-pills-profile .mob-layout').addClass('w-50').removeClass('d-none')
                    $('.mySwiper2,.char2').removeClass('d-none')
                    $('.mySwiper3,.mySwiper,.char,.char3').addClass('d-none')
                    $('#v-pills-home .mob-layout ,#v-pills-messages .mob-layout').removeClass('w-50').addClass('d-none')
                    $('#v-pills-profile-tab .icon img').attr('src','Baseet/images/TelegramIcon3.svg');
                    break;
                }
                case "v-pills-home-tab":{
                    swiper.slideTo(0,false,false);
                    $('.tab-text').removeClass('active')
                    $('.icon').removeClass('active')
                    $('#v-pills-profile-tab .icon img').attr('src','Baseet/images/TelegramIcon2.svg');
                    $('#v-pills-messages-tab .icon img').attr('src','Baseet/images/WebIcon3.svg');
                    $('#v-pills-home-tab .tab-text').addClass('active');
                    $('#v-pills-home-tab .icon').addClass('active');
                    $('#v-pills-home .mob-layout').addClass('w-50').removeClass('d-none')
                    $('.mySwiper,.char').removeClass('d-none')
                    $('.mySwiper3,.mySwiper2,.char3,.char2').addClass('d-none')
                    $('#v-pills-profile .mob-layout ,#v-pills-messages .mob-layout').removeClass('w-50').addClass('d-none')
                    $('#v-pills-home-tab .icon img').attr('src','Baseet/images/MessengerIcon4.svg');
                    break;
                }
                case "v-pills-messages-tab":{
                    swiper3.slideTo(0,false,false);
                    $('.tab-text').removeClass('active')
                    $('.icon').removeClass('active')
                    $('#v-pills-messages .mob-layout').addClass('w-50').removeClass('d-none')
                    $('#v-pills-profile .mob-layout ,#v-pills-home .mob-layout').removeClass('w-50').addClass('d-none')
                    $('#v-pills-home-tab .icon img').attr('src','Baseet/images/MessengerIcon2.svg');
                    $('#v-pills-profile-tab .icon img').attr('src','Baseet/images/TelegramIcon2.svg');
                    $('#v-pills-messages-tab .tab-text').addClass('active');
                    $('#v-pills-messages-tab .icon').addClass('active');
                    $('.mySwiper3,.char3').removeClass('d-none')
                    $('.mySwiper2,.mySwiper,.char,.char2').addClass('d-none')
                    $('#v-pills-messages-tab .icon img').attr('src','Baseet/images/WebIcon4.svg');
                    break;
                }

            }
        })
    </script>
@endsection
