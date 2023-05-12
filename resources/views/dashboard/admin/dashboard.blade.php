@extends('dashboard.layouts.master')

@section('content')
    <div class="dashboard">
        <div class="card">
            <div class="card-body">
                <h1>لوحة التحكم</h1>

                <div class="container">
                    <div class="row">
                        <div class="col-md-5 col-xl-5 mr-mob" style="margin-right: 2em">
                            <div class="card bg-c-blue order-card">
                                <div class="card-block">
                                    <h6 class="m-b-20">عدد </h6>
                                    <h2 class="text-right"><i class="fa fa-cart-plus f-left"></i><span></span>
                                    </h2>
                                    {{-- <p class="m-b-0">Completed Orders<span class="f-right">351</span></p> --}}
                                </div>
                            </div>
                        </div>


                        <div class="col-md-5 col-xl-5 mr-mob" style="margin-right: 6em">
                            <div class="card bg-c-green order-card">
                                <div class="card-block">
                                    <h6 class="m-b-20"> </h6>
                                    <h2 class="text-right"><i class="fa fa-rocket f-left"></i><span></span>
                                    </h2>
                                    {{-- <p class="m-b-0">Completed Orders<span class="f-right">351</span></p> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h4> </h4>
                        </div>
                        <div class="col-md-4 col-xl-3">
                            <div class="card bg-c-blue order-card">
                                <div class="card-block">
                                    <h6 class="m-b-20"> </h6>
                                    <h2 class="text-right"><i class="fa fa-cart-plus f-left"></i><span></span>
                                    </h2>
                                    {{-- <p class="m-b-0">Completed Orders<span class="f-right">351</span></p> --}}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-xl-3">
                            <div class="card bg-c-green order-card">
                                <div class="card-block">
                                    <h6 class="m-b-20"> </h6>
                                    <h2 class="text-right"><i class="fa fa-rocket f-left"></i><span></span>
                                    </h2>
                                    {{-- <p class="m-b-0">Completed Orders<span class="f-right">351</span></p> --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-xl-3">
                            <div class="card bg-c-yellow order-card">
                                <div class="card-block">
                                    <h6 class="m-b-20"> </h6>
                                    <h2 class="text-right"><i class="fa fa-refresh f-left"></i><span></span>
                                    </h2>
                                    {{-- <p class="m-b-0">Completed Orders<span class="f-right">351</span></p> --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-xl-3">
                            <div class="card bg-c-pink order-card">
                                <div class="card-block">
                                    <h6 class="m-b-20"></h6>
                                    <h2 class="text-right"><i class="fa fa-credit-card f-left"></i><span></span>
                                    </h2>
                                    {{-- <p class="m-b-0">Completed Orders<span class="f-right">351</span></p> --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-xl-4 mr-mob" style="margin-right:21em;">
                            <div class="card bg-c-dark order-card">
                                <div class="card-block">
                                    <h6 class="m-b-20">غير ذلك</h6>
                                    <h2 class="text-right"><i class="fa fa-credit-card f-left"></i><span></span>
                                    </h2>
                                    {{-- <p class="m-b-0">Completed Orders<span class="f-right">351</span></p> --}}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- <div class="container"> --}}
                {{-- <div class="row"> --}}
                {{-- <div class="col-md-5 col-xl-5 mr-mob" style="margin-right: 2em"> --}}
                {{-- <div class="card bg-c-blue order-card"> --}}
                {{-- <div class="card-block"> --}}
                {{-- <h6 class="m-b-20">عدد الشكاوي</h6> --}}
                {{-- <h2 class="text-right"><i --}}
                {{-- class="fa fa-cart-plus f-left"></i><span></span> --}}
                {{-- </h2> --}}
                {{--  --}}{{-- <p class="m-b-0">Completed Orders<span class="f-right">351</span></p> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}


                {{-- <div class="col-md-5 col-xl-5 mr-mob" style="margin-right: 6em"> --}}
                {{-- <div class="card bg-c-green order-card"> --}}
                {{-- <div class="card-block"> --}}
                {{-- <h6 class="m-b-20">عدد الشكاوي التي تم حلها</h6> --}}
                {{-- <h2 class="text-right"><i --}}
                {{-- class="fa fa-rocket f-left"></i><span></span> --}}
                {{-- </h2> --}}
                {{--  --}}{{-- <p class="m-b-0">Completed Orders<span class="f-right">351</span></p> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- <div class="container"> --}}
                {{-- <div class="row"> --}}
                {{-- <div class="col-12"> --}}
                {{-- <h4>أنواع الشكاوي</h4> --}}
                {{-- </div> --}}
                {{-- <div class="col-md-4 col-xl-3"> --}}
                {{-- <div class="card bg-c-blue order-card"> --}}
                {{-- <div class="card-block"> --}}
                {{-- <h6 class="m-b-20">بضائع غير صالحة</h6> --}}
                {{-- <h2 class="text-right"><i --}}
                {{-- class="fa fa-cart-plus f-left"></i><span>}</span> --}}
                {{-- </h2> --}}
                {{--  --}}{{-- <p class="m-b-0">Completed Orders<span class="f-right">351</span></p> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}

                {{-- <div class="col-md-4 col-xl-3"> --}}
                {{-- <div class="card bg-c-green order-card"> --}}
                {{-- <div class="card-block"> --}}
                {{-- <h6 class="m-b-20">عدم توافر فاتورة</h6> --}}
                {{-- <h2 class="text-right"><i --}}
                {{-- class="fa fa-rocket f-left"></i><span></span> --}}
                {{-- </h2> --}}
                {{--  --}}{{-- <p class="m-b-0">Completed Orders<span class="f-right">351</span></p> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- <div class="col-md-4 col-xl-3"> --}}
                {{-- <div class="card bg-c-yellow order-card"> --}}
                {{-- <div class="card-block"> --}}
                {{-- <h6 class="m-b-20">سعر زائد</h6> --}}
                {{-- <h2 class="text-right"><i --}}
                {{-- class="fa fa-refresh f-left"></i><span></span> --}}
                {{-- </h2> --}}
                {{--  --}}{{-- <p class="m-b-0">Completed Orders<span class="f-right">351</span></p> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- <div class="col-md-4 col-xl-3"> --}}
                {{-- <div class="card bg-c-pink order-card"> --}}
                {{-- <div class="card-block"> --}}
                {{-- <h6 class="m-b-20">إحتكار</h6> --}}
                {{-- <h2 class="text-right"><i --}}
                {{-- class="fa fa-credit-card f-left"></i><span></span> --}}
                {{-- </h2> --}}
                {{--  --}}{{-- <p class="m-b-0">Completed Orders<span class="f-right">351</span></p> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- <div class="col-md-4 col-xl-4 mr-mob" style="margin-right:21em;"> --}}
                {{-- <div class="card bg-c-dark order-card"> --}}
                {{-- <div class="card-block"> --}}
                {{-- <h6 class="m-b-20">غير ذلك</h6> --}}
                {{-- <h2 class="text-right"><i --}}
                {{-- class="fa fa-credit-card f-left"></i><span></span> --}}
                {{-- </h2> --}}
                {{--  --}}{{-- <p class="m-b-0">Completed Orders<span class="f-right">351</span></p> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}

                {{-- </div> --}}
                {{-- </div> --}}
            </div>
        </div>
    </div>
@endsection
