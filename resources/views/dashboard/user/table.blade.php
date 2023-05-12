@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body p-2-4">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col col-xs-6">
                            <h3 class="panel-title">الشكاوي</h3>
                        </div>
                    </div>
                </div>
                {{--        <form action="{{route('table')}}" method="post">--}}
                {{--            @csrf--}}
                {{--        <div class="row">--}}
                {{--            <div class="col-lg-2 col-6">--}}
                {{--                <div class="form-floating">--}}
                {{--                    <input name="code" type="text" class="form-control" id="floatingInputGrid" placeholder="رقم الشكوى">--}}
                {{--                    <label for="floatingInputGrid">رقم الشكوى</label>--}}
                {{--                </div>--}}
                {{--            </div>--}}
                {{--            <div class="col-lg-3 col-6">--}}
                {{--                <div class="form-floating">--}}
                {{--                    <select name="governorate" class="form-select" id="floatingSelectGrid"--}}
                {{--                            aria-label="Floating label select example">--}}
                {{--                        <option value=""> جميع المحافظات</option>--}}
                {{--                        @foreach($govers as $gover)--}}
                {{--                            <option--}}
                {{--                                {{($filters['governorate'] ?? '') ==$gover['id'] ? 'selected' : ''}} value="{{$gover['id']}}">{{$gover['name']}}</option>--}}
                {{--                        @endforeach--}}
                {{--                    </select>--}}
                {{--                    <label for="floatingSelectGrid">المحافظات</label>--}}
                {{--                </div>--}}
                {{--            </div>--}}
                {{--            <div class="col-lg-3 col-6">--}}
                {{--                <div class="form-floating">--}}
                {{--                    <input name="department" type="text" class="form-control" id="floatingInputGrid"--}}
                {{--                           placeholder="المنطقة" value="{{($filters['department'] ?? '')}}">--}}
                {{--                    <label for="floatingInputGrid">المنطقة</label>--}}
                {{--                </div>--}}
                {{--            </div>--}}
                {{--            <div class="col-lg-3 col-6">--}}
                {{--                <div class="form-floating">--}}
                {{--                    <select name="claim_type" class="form-select" id="floatingSelectGrid"--}}
                {{--                            aria-label="Floating label select example">--}}
                {{--                        <option value=""> جميع الشكاوي</option>--}}
                {{--                        @foreach(\App\Models\Claim::$types as $key => $value)--}}
                {{--                            <option--}}
                {{--                                {{($filters['claim_type'] ?? '') == $key ? 'selected' : ''}} value="{{$key}}">{{$value}}</option>--}}
                {{--                        @endforeach--}}
                {{--                    </select>--}}
                {{--                    <label for="floatingSelectGrid">نوع الشكوى</label>--}}
                {{--                </div>--}}
                {{--            </div>--}}
                {{--            <div class="col-lg-1 col-12"--}}
                {{--                 style="align-items: center;justify-content: center;display: flex; margin-bottom: 1em">--}}
                {{--                <button type="submit" tabindex="-1" class="btn btn-primary bg-blue"--}}
                {{--                        style="margin-top: 1em; font-weight:bold">تصنيف--}}
                {{--                </button>--}}
                {{--            </div>--}}
                {{--        </div>--}}
                {{--        </form>--}}
                <div class="row">

                    <div class="col-md-12 col-md-offset-1">

                        <div class="panel panel-default panel-table">
                            <div class="panel-body">
                                <table class="table table-striped table-bordered table-list">
                                    <thead>
                                    <tr>
                                        <th class="hidden-xs">رقم الشكوى</th>
                                        <th scope="col">المحافظة</th>
                                        <th scope="col">المنطقة</th>
                                        <th scope="col">حالة الشكوى</th>
                                        <th scope="col"><em class="fa fa-cog"
                                                            style="display: flex; justify-content: center"></em></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($claims as $claim)
                                        @php
                                            $dep = \Illuminate\Support\Facades\DB::table('district')->find($claim->department);
                                            $gov = \Illuminate\Support\Facades\DB::table('city')->find($claim->governorate);
                                        @endphp
                                        <tr>
                                            <th scope="row" class="hidden-xs">{{ $claim->code }}</th>
                                            <td>{{$gov->name}}</td>
                                            <td>{{$dep->name}}</td>
                                            <td>{{$claim->status}}</td>
                                            <td align="center">
                                                <a class="btn btn-default"
                                                   href="{{route('old-view',['claims' => $claim->id])}}">
                                                    <em class="fa fa-eye"></em>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                                {{-- Pagination --}}
                                <div class="d-flex justify-content-center">
                                    {!! $claims->links() !!}
                                </div>
                            </div>
                </div>
            </div>
        </div>
      </div>
      </div>
    </div>
@endsection
