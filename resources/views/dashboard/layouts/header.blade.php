<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container-fluid head-desk" >
        @if(Auth::guest())
            <a class="navbar-brand" href="{{route('welcome')}}"><img src="{{asset('store_assets/Group 4910.svg')}}" style="width:100%;margin-right: 4em" /></a>
        @endif
        @if(!Auth::guest())
            <a class="navbar-brand" href="{{route('dashboard')}}"><img src="{{asset('store_assets/Group 4910.svg')}}" style="width: 70%;margin-right: 3em"/></a>
        @endif
{{--        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"--}}
{{--                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">--}}
{{--            <span class="navbar-toggler-icon"></span>--}}
{{--        </button>--}}
            @if(Auth::guest())
            <form id="search" method="POST" action="{{route('view')}}" style="width:100%">
                @csrf
                <div class="row row-header">
                    <div class="col-lg-10 cus-p">
                        <div class="form-floating header">
                            <input name="id" type="text" class="input-header form-control @error('id') is-invalid @enderror"
                                   id="floatingInputGrid" placeholder="رقم الشكوى">
                            <label for="floatingInputGrid">رقم الشكوى</label>
                            @error('id')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <button type="submit" class="btn btn-primary btn-block search-btn bg-blue">بحث</button>
                    </div>
                </div>
            </form>
            @endif
            <div class="up-ul">
                <ul id="list" class="navbar-nav nav-pills">
                    @if(Auth::guest())
                        {{--                    <li id="home" class="nav-item nav-active">--}}
                        {{--                        <a class="nav-link" href="{{route('table')}}" style="color: rgba(0,0,0,0.5)">--}}
                        {{--                            عرض الشكاوي--}}
                        {{--                        </a>--}}
                        {{--                    </li>--}}
                        <li id="claim" class="nav-item">
                            <a class="nav-link" href="{{route('claim')}}" style="color: rgba(0,0,0,0.5)">
                                تسجيل شكوى
                            </a>
                        </li>
                        <li id="search-link" class="nav-item">
                            <a class="nav-link" href="{{route('search')}}" style="color: rgba(0,0,0,0.5)">
                                بحث عن شكوى
                            </a>
                        </li>
                @endif
                @if(!Auth::guest())
                    <li class="nav-item">
                        <div class="dropdown">
                            <a href="#"
                               class="d-flex align-items-center blue text-decoration-none dropdown-toggle"
                               id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                <strong>{{auth()->user()->name}}</strong>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-dark text-small shadow"
                                aria-labelledby="dropdownUser1" style="right: -50px;">
{{--                                <li><a class="dropdown-item" href="{{route('profile',['user' => auth()->user()->id])}}">معلومات المستخدم</a></li>--}}
                                <li><a class="dropdown-item" href="{{route('logout')}}">تسجيل الخروج</a></li>
                            </ul>
                        </div>
                    </li>
                @endif
            </ul>
            @if(!Auth::guest())
                <button class="btn btn-primary collapse-btn bg-blue" onclick="sidebar()">&#9776;</button>
            @endif
        </div>
    </div>
</nav>
<script>
    function sidebar(){
        $('#sidebar').toggleClass('d-flex')
    }

    $(document).ready(() => {

        $('#search').removeClass('d-none')
        $('#list').removeClass('d-none')
        var pathname = window.location.pathname;
        if (pathname === "/") {
            $('#search').addClass('d-none')
            $('#list').addClass('d-none')
        }
        if (pathname === "/search" || pathname === "/view") {
            $('#search').addClass('d-none')
            $('#search-link').addClass('nav-active')
            $('#home').removeClass('nav-active')
            $('#claim').removeClass('nav-active')
        }
        if (pathname === "/claim") {
            $('#search-link').removeClass('nav-active')
            $('#home').removeClass('nav-active')
            $('#claim').addClass('nav-active')
        }
        if (pathname === "/table") {
            $('#search-link').removeClass('nav-active')
            $('#claim').removeClass('nav-active')
            $('#home').addClass('nav-active')
        }
    })
</script>
