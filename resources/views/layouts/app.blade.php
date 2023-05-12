<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta content="" name="description"/>
    <meta content="" name="keywords"/>
    <title>eCart - إي كارت</title>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
    <link rel="stylesheet" href="{{ asset('Baseet/style.css') }}"/>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" href="{{ asset('Baseet/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('Baseet/w3.css') }}">
    <link rel="stylesheet" href="{{asset('css.css')}}">
    <link rel="stylesheet" href="{{ asset('Baseet/anime.scss') }}">
    <link href="Baseet/aos.css" rel="stylesheet"/>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" id="theme-styles">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" id="theme-styles">

</head>
<body id="body">
@php
    $currentURL = Request::url();
@endphp
<header id="header" class="">
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid mtop-2 mob">
            {{--            <span><img class="fatoura-logo" src="{{asset('Baseet/images/FatoraLogo.svg')}}" alt=""></span>--}}
            <div class="" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    @if(isset($logo))
                        <a class="navbar-brand mob w-100">
                            <img src="{{ isset($logo) ? $logo : asset('Baseet/images/fatora-logo.png')}}" alt="" id="fatora">
                        </a>
                        <a class="navbar-brand mob w-100" href="{{route('home')}}">
                            <img src="{{asset('Baseet/images/EcartLogo.svg')}}" alt="" id="logo">
                        </a>
                    @elseif(Request::getPathInfo() === "/login")
                        <a class="nav-link w-100 p-0" href="{{route('register')}}"> <span>إشترك الآن</span></a>
                        <a class="nav-link active w-100" aria-current="page" href="/"> <span>الرئيسية</span> </a>
                        <a class="navbar-brand mob w-100" href="{{route('home')}}">
                            <img src="{{asset('Baseet/images/EcartLogo.svg')}}" alt="" id="logo">
                        </a>

                    @else
                    @if (isset(auth()->user()->name))
                            <div class="dropdown nav-link">
                                <a class="dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <span>{{auth()->user()->name}}</span> 
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('dashboard') }}"><span>لوحة التحكم</span></a></li>
                                    <li><a class="dropdown-item" href="{{ route('accountSettings.view') }}"><span>{{ __('app.sidebar.account_settings') }}<span></a></li>
                                    <li><a class="dropdown-item" href="{{ route('logout') }}"><span>{{ __('app.logout') }}</span></a></li>
                                </ul>
                            </div>
                        @else
                            <a class="nav-link w-100 p-0" href="{{route('login')}}"> <span> تسجيل الدخول</span></a>
                            <a class="nav-link active w-100" aria-current="page" href="/"> <span>الرئيسية</span> </a>
                            <a class="navbar-brand mob w-100" href="{{route('home')}}">
                                <img src="{{asset('Baseet/images/EcartLogo.svg')}}" alt="" id="logo">
                            </a>
                        @endif

                    @endif

                    {{--              <a class="nav-link draw-outline-3" href="{{route('register')}}"> <span>تجربة المنصة</span> </a>--}}
                    {{--              <a class="nav-link draw-outline-3" href="{{route('blog')}}"> <span>المدونة</span> </a>--}}
                    {{--              <a class="nav-link draw-outline-3" href="{{Route('price')}}"> <span>الأسعار</span> </a>--}}

                </div>
            </div>
        </div>
        <div class="container-fluid desk">
            <div class="" id="navbarNavAltMarkup">
                <div class="navbar-nav pleft-4">
                    @if(Request::getPathInfo() === "/gateway/callback" || Request::getPathInfo() === "/thanks")
                        <a class="navbar-brand desk">
                            <img src="{{isset($logo) ? $logo : asset('Baseet/images/fatora-logo.png')}}" alt="" id="fatora">
                        </a>
                </div>
            </div>
            <a class="navbar-brand desk" href="{{route('home')}}">
                <img src="{{asset('Baseet/images/EcartLogo.svg')}}" alt="" id="">
            </a>
            @elseif(Request::getPathInfo() === "/login")
                <a class="nav-link draw-outline-3 w-100 p-0" href="{{route('register')}}"
                   style="padding: 0 0.5em !important;"> <span>إشترك الآن</span></a>
                <a class="nav-link active draw-outline-3 " aria-current="page" href="/"> <span>الرئيسية</span> </a>
        </div>
        </div>
        <a class="navbar-brand desk" href="{{route('home')}}">
            <img src="{{asset('Baseet/images/EcartLogo.svg')}}" alt="" id="logo">
        </a>
        @else
            @if (isset(auth()->user()->name))
                    <div class="dropdown nav-link">
                        <a class="dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                         <span>{{auth()->user()->name}}</span> 
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}"><span>لوحة التحكم</span></a></li>
                            <li><a class="dropdown-item" href="{{ route('accountSettings.view') }}"><span>{{ __('app.sidebar.account_settings') }}<span></a></li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}"><span>{{ __('app.logout') }}</span></a></li>
                        </ul>
                    </div>
            @else

            <a class="nav-link draw-outline-3 w-100 p-0" href="{{route('login')}}" style="padding: 0 0.5em !important;">
                <span> تسجيل الدخول</span></a>
            @endif
            <a class="nav-link active draw-outline-3 " aria-current="page" href="/"> <span>الرئيسية</span> </a>
            </div>
            </div>
            <a class="navbar-brand desk" href="{{route('home')}}">
                <img src="{{asset('Baseet/images/EcartLogo.svg')}}" alt="" id="logo">
            </a>
            @endif
            {{--              <a class="nav-link draw-outline-3" href="{{route('register')}}"> <span>تجربة المنصة</span> </a>--}}
            {{--              <a class="nav-link draw-outline-3" href="{{route('blog')}}"> <span>المدونة</span> </a>--}}
            {{--              <a class="nav-link draw-outline-3" href="{{Route('price')}}"> <span>الأسعار</span> </a>--}}

            </div>
    </nav>
</header>
<script src="Baseet/aos.js"></script>
<script src="Baseet/script.js" defer></script>
<script src="anime.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ"
        crossorigin="anonymous"></script>
@yield('content')

@if (session()->has('success_message'))
    <script>
        Swal.fire({
            title: '',
            html: `{!!  session()->get('success_message') !!}`,
            icon: 'success',
        })
    </script>
@endif

@if (session()->has('error_message'))
    <script>
        Swal.fire({
            title: '',
            text: `{{ session()->get('error_message') }}`,
            icon: 'error',
        })
    </script>
@endif
</body>
</html>
