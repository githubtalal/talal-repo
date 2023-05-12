<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta content="" name="description"/>
    <meta content="" name="keywords"/>
    <title>Baseet</title>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
    <link rel="stylesheet" href="Baseet/style.css"/>
    <link rel="icon" href="Baseet/images/Group 438.png"/>
    <link rel="stylesheet" href="Baseet/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="Baseet/w3.css">
    <link rel="stylesheet" href="Baseet/anime.scss">
</head>
<style>
    .border3 ::placeholder {
        bottom: 70px;
    }

    ::placeholder {
        position: relative;
        right: 10px;
    }
</style>
<body>
<section id="contact-with-us" class="d-flex justfiy-content-center align-items-center" style="position: relative">
    <img class="position-absolute why-back" src="Baseet/images/HomeBackgroundImg.svg"/>
    <div class="container">
        <div class="row" id="contact-row">
            <div class="col-6" id="desk-contact">
                <img class="img-fluid" src="Baseet/images/ContactUsDesktopImg.svg" id="mail">
                <h3 class="h3-contact-1">Can you <br> help me?</h3>
                <h3 class="h3-contact-2">Hi!</h3>
                <h3 class="h3-contact-3">Hello!</h3>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <h1>تواصل معنا</h1>
                <img class="img-fluid" src="Baseet/images/UnderlineImg3.svg" id="contact-underline">
                <img class="img-fluid" src="Baseet/images/ContactUsMobImg.svg" id="mob-contact-img">
                <h2>نرحب دوماً بأسئلتكم واستفساراكم</h2>
                <form id="form" action="{{ route('home.contact_us') }}" method="post">
                    @csrf
                    <div class="mb-4 border1">
                        <input dir="rtl" type="text" name="name" id="name" placeholder="الاسم"
                               class=" @error('name') border-red-500 @enderror" value="{{old('name')}}" required>
                        @error ('name')
                        <div class="text-red-500 mr-24 text-sm text-right">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-4 border2">
                        <input dir="rtl" type="email" name="email" id="email" placeholder="البريد الالكتروني"
                               class=" @error('email') border-red-500 @enderror" value="{{old('email')}}" required>
                        @error ('email')
                        <div class="text-red-500 mr-24 text-sm text-right">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-4 border3">
                        <textarea dir="rtl" type="text" name="details" id="details" placeholder="التفاصيل"
                               class="  @error('details') border-red-500 @enderror" required></textarea>
                        @error ('details')
                        <div class="text-red-500 mr-24 text-sm text-right">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="w-100 d-flex justify-content-end align-center btn-sub" style="margin-left: -0.2em">
                        <button type="submit" class="bot-2" style="width:120px">إرسال</button>
                    </div>

                    <br/>

                </form>
            </div>
        </div>
    </div>
</section>
@yield('contactUs')
</body>
</html>
