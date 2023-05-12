@extends('layouts.app')

@section('content')


<style>

    .wrapper-1{
        width:100%;
        height:100vh;
        display: flex;
        flex-direction: column;
    }
    .wrapper-2{
        padding :30px;
        text-align:center;
    }
    h1{
        font-family: 'Bahij-Bold', cursive;
        font-size:4em;
        color:#EA6625 ;
        margin:0;
        margin-bottom:20px;
    }
    .wrapper-2 p{
        margin:0;
        font-size:1.3em;
        color:#aaa;
        font-family: 'Bahij-SemiBold', sans-serif;
    }
    .go-home{
        color:#fff;
        background:#EA6625;
        border:none;
        padding:10px 50px;
        margin:30px 0;
        border-radius:30px;
        text-transform:capitalize;
        box-shadow: 0 10px 16px 1px rgba(174, 199, 251, 1);
    }


    @media (min-width:360px){
        h1{
            font-size:4.5em;
        }
        .go-home{
            margin-bottom:20px;
        }
    }

    @media (min-width:600px){
        .content{
            max-width:1000px;
            margin:0 auto;
        }
        .wrapper-1{
            height: initial;
            max-width:620px;
            margin:0 auto;
            margin-top:50px;
            box-shadow: 4px 8px 40px 8px rgba(88, 146, 255, 0.2);
        }

    }
</style>
<link href="https://fonts.googleapis.com/css?family=Kaushan+Script|Source+Sans+Pro" rel="stylesheet">

<section id="thanks">
    <div class=content>
        <div class="wrapper-1">
            <div class="wrapper-2">
                <h1 class="dir-rtl">شكرا لك !</h1>
                <p>نخنخحثقنبخحنصحنخحل</p>
                <p>نخنخحثقنبخحنصحنخحل</p>
                <button class="go-home">
                    إغلاق
                </button>
            </div>
        </div>
    </div>


</section>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
        integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script>

    $(window).on("load", function () {

        setTimeout(function () {
            $('.done').addClass("drawn");
        }, 500)

    });
</script>
@endsection
