<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
{{--        additional       --}}
        .btn-payment-bemo {
            /*background-color: hsl(245, 75%, 52%);*/
            background-color: white;
            border: 2px solid #00a0df;
            padding: 1em;
            color: #00a0df;
            border-radius: 10px;
            margin-bottom: 25px;
            /*color: #fff;*/
            transition: 0.3s ease-in;
            transition-property: background;
            /*box-shadow: 0 18px 14px 0px rgba(0, 0, 0, 0.2);*/
        }
        .additional .accordion-item{
            border: none;
        }
        .additional .accordion-header .btn{
            width: -webkit-fill-available;
            box-shadow: 0 0 0 0.25rem rgb(13 110 253 / 0%);
        }
        .m-53{
            margin: 0 53px;
        }
        .btn-payment-others:hover {
            color: #00a0df;
        }
        .btn-payment-others {
            /*background-color: hsl(245, 75%, 52%);*/
            background-color: white;
            border: 2px solid #00a0df!important;
            padding: 1em;
            color: #00a0df;
            border-radius: 10px;
            margin-bottom: 25px;
            /*color: #fff;*/
            transition: 0.3s ease-in;
            transition-property: background;
            /*box-shadow: 0 18px 14px 0px rgba(0, 0, 0, 0.2);*/
        }
        @font-face {
            font-family: din;
            src: url(font/DINNextLTArabic-Regular-3.ttf);
        }

        .navbar-brand img {
            width: 120px;
            margin: 0 2em;
        }

        body {
            background-color: #d7d7d7;
        }

        .navbar {
            margin-bottom: 2em;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            direction: rtl;
            font-family: din, sans-serif;
        }

        a {
            text-align: center;
        }

        label {
            font-weight: 500;
        }

        select {
            color: rgba(0, 0, 0, 0.5) !important;
        }

        .main-container {
            min-width: 98vw;
            min-height: 100vh;
            display: flex;
            align-content: center;
            justify-content: center;
            font-family: 'Red Hat Display', sans-serif;
            background: url('../images/pattern-background-desktop.svg') no-repeat top;
            background-color: #d7d7d7;
        }

        .container {
            width: 500px;
            min-height: 550px;
            margin: auto;
            box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
            transition: all 0.3s;
            padding: 0;
        }

        .top-part {
            height: 221px;
            background: url('../images/illustration-hero.svg') no-repeat center;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }

        .bottom-part {
            background-color: #fff;
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px;
        }

        .word-section {
            text-align: center;
            padding-top: 22px;
        }

        .word-section h1 {
            font-size: 28px;
            padding: 12px;
            color: hsl(223, 47%, 23%);
            font-weight: 700;
        }

        .word-section p {
            font-size: 17px;
            letter-spacing: 0.4px;
            line-height: 23px;
            font-weight: 600;
            color: hsl(226, 20%, 71%);
            padding: 12px;
            margin-bottom: 15px;
        }

        .plan-section {
            /*background-color: hsl(225, 100%, 98%);*/
            display: flex;
            flex-direction: row;
            padding: 0 1em;
            border-radius: 12px;
            align-items: center;
            margin: 0 40px;
            margin-bottom: 32px;
        }

        .plan-section .img img {
            margin-right: 15px;
        }

        .plan-section .annual-plan h2 {
            color: hsl(223, 47%, 23%);
            font-size: 20px;
        }

        .plan-section .annual-plan p {
            color: hsl(226, 20%, 71%);
            font-weight: 600;
        }

        .plan-section .change {
            margin: auto;
            margin-right: 0px;
        }

        .plan-section .change a {
            color: hsl(245, 75%, 52%);
            text-decoration: underline;
            font-weight: 600;
            transition: 0.3s ease-out;
            transition-property: color, text;
        }

        .plan-section .change a:hover {
            color: #766CF1;
            text-decoration: none;
        }

        .btn-section {
            display: flex;
            flex-direction: column;
            margin-top: 8px;
            padding-bottom: 32px;
        }

        .btn {
            border: none;
            width: auto;
            margin: 0 53px;
            font-size: 14px;
            padding: 14px 0;
            border-radius: 7px;
            font-family: 'Red Hat Display', sans-serif;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-payment-fatora {
            /*background-color: hsl(245, 75%, 52%);*/
            background-color: white;
            border: 2px solid #035AA7;
            padding: 1em;
            color: #035AA7;
            border-radius: 10px;
            margin-bottom: 25px;
            /*color: #fff;*/
            transition: 0.3s ease-in;
            transition-property: background;
            /*box-shadow: 0 18px 14px 0px rgba(0, 0, 0, 0.2);*/
        }

        .btn-payment-bemo {
            /*background-color: hsl(245, 75%, 52%);*/
            background-color: white;
            border: 2px solid #00a0df;
            padding: 1em;
            color: #00a0df;
            border-radius: 10px;
            margin-bottom: 25px;
            /*color: #fff;*/
            transition: 0.3s ease-in;
            transition-property: background;
            /*box-shadow: 0 18px 14px 0px rgba(0, 0, 0, 0.2);*/
        }

        .btn-payment-bemo:hover {
            color: #00a0df;
        }

        .btn-payment-fatora:hover {
            color: #035AA7;
        }

        /*.btn-payment:hover{*/
        /*    background-color: #766CF1;*/
        /*}*/

        .btn-submit {
            color: hsl(226, 20%, 71%);
            background-color: #fff;
            transition: 0.3s ease-in;
            transition-property: color;
        }

        .btn-submit:hover {
            color: hsl(223, 47%, 23%);
        }

        .form-floating > label {
            left: 275px;
        }

        .form-floating > .name-label {
            left: 300px;
        }

        /* Media Queries */

        @media (min-width: 1440px) {
            .main-container {
                background-image: none;
            }
        }

        @media (max-height: 700px) {
            .main-container {
                padding: 35px 35px;
            }
        }

        @media (max-width: 500px) {
            .main-container {
                padding: 35px 35px;
            }

            .plan-section .annual-plan h2 {
                font-size: 16px;
            }

            .plan-section .annual-plan p {
                font-size: 14px;
            }

            .plan-section .change a {
                font-size: 14px;
            }

            .form-floating > label {
                left: 200px;
            }

            .form-floating > .name-label {
                left: 230px;
            }
        }

        @media (max-width: 375px) {
            .main-container {
                background-image: url('../images/pattern-background-mobile.svg');
                padding: 25px 25px;
            }

            .container {
                max-width: 350px !important;
            }

            br {
                display: none;
            }

            .word-section p {
                margin: 0 18px;
            }

            .plan-section {
                margin: 0 20px;
            }

            .annual-plan {
                margin-right: 15px;
            }
        }

        .red {
            color: red;
        }

        option {
            font-weight: bold;
        }

        footer {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: 2em;
            align-items: end;
        }

        footer p {
            color: #035AA7;
            font-weight: bold;
        }

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        .card-plan {
            display: flex;
            flex-direction: row;
            align-items: center;
            column-gap: 15px;
            background: #f5f7ff;
            border-radius: 10px;
            padding: 15px;
            box-sizing: border-box;
            width: 100%;
        }

        .card-plan .card-plan-img {
            flex-grow: 1;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
        }

        .card-plan .card-plan-text {
            flex-grow: 6;
            display: flex;
            flex-direction: column;
            row-gap: 4px;
        }

        .card-plan .card-plan-text .card-plan-title {
            color: #1f2f56;
            font-weight: 900;
            font-size: 20px;
        }

        .card-plan .card-plan-text .card-plan-price {
            color: #7280a7;
            font-size: 18px;
        }

        .card-plan .card-plan-link {
            flex-grow: 1;
        }

        .card-plan .card-plan-link a {
            color: #3829e0;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
        }

        .card-plan .card-plan-link a:hover {
            color: #766cf1;
            text-decoration: none;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><img height="70px" src="{{ asset('store_assets/Group 4910.svg') }}"></a>
        <?php
            $store = \App\Models\Store::find(session('store_id'));
        ?>
        <a class="navbar-brand" href="#"><img height="80px" src="{{ \Illuminate\Support\Facades\Storage::url($store->logo) }}"></a>
    </div>
</nav>
<div class="main-container">
    <div class="container">
        <div class="bottom-part">
            <div class="word-section">
                <h1>
                    تفاصيل الطلبية
                </h1>
            </div>
            <form action="{{ route('store.saveOrder') }}" method="post">
                @csrf
                <div class="plan-section">
                    <div class="col-12">
                        <div class="form-floating">
                            <input name="first_name" type="text"
                                   class="form-control "
                                   id="floatingInputGrid" placeholder="الإسم">
                            <label class="name-label" for="floatingInputGrid">الإسم<span class="red">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="plan-section">
                    <div class="col-12">
                        <div class="form-floating">
                            <input name="last_name" type="text"
                                   class="form-control "
                                   id="floatingInputGrid" placeholder="الكنية">
                            <label class="name-label" for="floatingInputGrid">الكنية<span class="red">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="plan-section">
                    <div class="col-12">
                        <div class="form-floating">
                            <input name="phone_number" type="number"
                                   class="form-control phone_number "
                                   id="floatingInputGrid" placeholder="الموبايل">
                            <label class="name-label" for="floatingInputGrid">الموبايل<span class="red">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="plan-section">
                    <div class="col-12 ">
                        <div class="form-floating form-claim">
                            <select name="governorate"
                                    class="form-input governorate_input form-select "
                                    id="gover" aria-label="Floating label select example">
                                <option disabled selected value> -- إختر المحافظة --</option>
                                <option value="دمشق">دمشق</option>
                                <option value="ريف دمشق">ريف دمشق</option>
                                <option value="الاذقية">الاذقية</option>
                                <option value="حمص">حمص</option>
                                <option value="حماة">حماة</option>
                                <option value="درعا">درعا</option>
                                <option value="القنيطرة">القنيطرة</option>
                                <option value="السويداء">السويداء</option>
                                <option value="الرقة">الرقة</option>
                                <option value="الحسكة">الحسكة</option>
                                <option value="دير الزور">دير الزور</option>
                                <option value="إدلب">إدلب</option>
                                <option value="حلب">حلب</option>
                                <option value="طرطوس">طرطوس</option>

                            </select>
                            <label for="floatingSelectGrid">المحافظات<span class="red">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="plan-section">
                    <div class="col-12">
                        <div class="form-floating form-claim">
                            <textarea name="note"
                                      class="form-input claim_input form-control"
                                      placeholder="ملاحظاتكم" id="claim" style="height: 70px" required
                            ></textarea>
                            <label for="floatingTextarea2">ملاحظاتكم<span class="red">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="plan-section">
                    <div class="card-plan">
                        <div class="card-plan-text ">
                            <div class="row">
                                <div class="card-plan-title col-12 mb-3">التفاصيل</div>
                                <div class="card-plan-price col-12">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col" class="text-center">اسم المنتج</th>
                                            <th scope="col" class="text-center">السعر</th>
                                            <th scope="col" class="text-center">حذف</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cart->items as $item)
                                                <tr>
                                                    <td>
                                                        <p class="text-center">
                                                            {{ $item->product->name }}
                                                        </p>
                                                        <p class="text-center">
                                                            {{ \Carbon\Carbon::make($item->additional['checkin'])->diff($item->additional['checkout'])->days }} ليلة</p>
                                                    </td>
                                                    <td class="text-center">
                                                        <p class="text-center">
                                                            {{ price_format($item->price) }}
                                                        </p>
                                                    </td>
                                                    <td class="text-center"><a href="{{ route('store.remove_from_cart', $item->product->id) }}"><img src="{{asset('Baseet/images/trash.png')}}"/></a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="card-plan-title col-4">المجموع</div>
                                <div class="card-plan-price col-8">
                                    {{ price_format($cart->total) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn-section">
                    <button class="btn-payment-fatora btn" type="submit">
                        <img src="{{asset('Baseet/images/fatora-icon.png')}}" style="width:30px">
                        ادفع بواسطة فاتورة
                    </button>
                    <button class="btn-payment-bemo btn" style="border-color: #38b5e6;" type="button">
                        <img src="{{asset('store_assets/bemo.png')}}" style="width:30px">
                        ادفع بواسطة بيمو
                    </button>
                    <div class="accordion additional" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button style="border-color: #d7d7d7 !important;" class="btn-payment-others btn collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    طرق دفع أخرى
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body m-53 px-0 d-flex flex-row justify-content-evenly align-items-center my-3">
                                    <a href="#">
                                        <img src="{{asset('store_assets/sama-pay.png')}}" style="width:65px;height:40px">
                                    </a>
                                    <a href="#">
                                        <img src="{{asset('store_assets/e-lira.png')}}" style="width:60px;height:24px">
                                    </a>
                                    <a href="#">
                                        <img src="{{asset('store_assets/syriatel.png')}}" style="width:60px;height:24px">
                                    </a>
                                    <a href="#">
                                        <img src="{{asset('store_assets/mtn.png')}}" style="width:60px;height:24px">
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
{{--                    <a href="/addToCart/Products.html">--}}
                        <button onclick="window.close()" class="btn-submit btn" type="submit">
                            إلغاء الطلب
                        </button>
{{--                    </a>--}}
                </div>
            </form>
        </div>
    </div>
</div>
<footer>
    <p>Powered by Fatora</p>
</footer>

<script>
    document.querySelector(".phone_number").addEventListener("keypress", function (evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>
</html>
