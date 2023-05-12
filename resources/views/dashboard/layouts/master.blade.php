<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>بسيط يرحب بكم يا غوالي</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Includes -->
    @include('dashboard.layouts.styles')
    @include('dashboard.layouts.scripts')

</head>
<body>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v14.0&appId={{ config('hooks.facebook_app_id') }}&autoLogAppEvents=1" nonce="fXWikoZ0"></script>
 <header>
   @include('dashboard.layouts.header')
 </header>
 @if(! \Illuminate\Support\Facades\Auth::guest())
     @include('dashboard.layouts.sidebar')
 @endif
 @yield('content')

 @stack('scripts')

</body>
</html>
