<?php
    $store = \App\Models\Store::find(session('store_id'));

    $storeInfo = new \App\Http\Resources\Store($store);
    $storeInfo = \App\Http\Resources\Store::make($store);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta content="" name="description" />
    <meta content="" name="keywords" />
    <title>
        eCart
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
{{--    <link href="{{ asset('store_assets/style.css') }}" rel="stylesheet">--}}
    <style>
        html, body{
            touch-action: manipulation;
        }
    </style>
</head>
<body data-store-id="{{ session('store_id') }}"
      data-theme="{{ getThemeForStore(session('store_id')) }}"
      data-user-id="{{ session('user_id') }}"
      data-csrf-token="{{ csrf_token() }}"
      data-store-info="{{ $storeInfo->toJson() }}"
      style="padding-bottom: 50px"
>
<div id="app" data-store-slug="{{ $store->slug }}"></div>
<script src="{{ mix('js/main.js') }}"></script>
<script>
    document.ondblclick = function(e) {
        e.preventDefault();
    }
</script>
</body>
</html>
