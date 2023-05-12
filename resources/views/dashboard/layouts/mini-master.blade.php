<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>منصة الشكاوي التمونية</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Includes -->
    @include('layouts.styles')

</head>
<body>

@yield('content')
@include('layouts.scripts')
</body>
</html>
