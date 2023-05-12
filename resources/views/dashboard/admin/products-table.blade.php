@extends('dashboard.layouts.master')

@section('content')
    <div class="dashboard">
        <div class="container">
            <div class="card">
                <div class="card-body p-2-4">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col col-xs-6">
                                <h2>المنتجات</h2>
                            </div>
                        </div>
                    </div>
                    {{--                    <form action="{{route('claim-table')}}" method="post">--}}
                    {{--                        @csrf--}}
                    {{--                        <div class="row">--}}
                    {{--                            <div class="col-lg-2 col-6">--}}
                    {{--                                <div class="form-floating">--}}
                    {{--                                    <input name="code" type="text" class="form-control" id="floatingInputGrid"--}}
                    {{--                                           placeholder="رقم الشكوى">--}}
                    {{--                                    <label for="floatingInputGrid">رقم الشكوى</label>--}}
                    {{--                                </div>--}}
                    {{--                            </div>--}}
                    {{--                                <div class="col-lg-3 col-6">--}}
                    {{--                                    <div class="form-floating">--}}
                    {{--                                        <select name="governorate" class="form-select" id="gov"--}}
                    {{--                                                aria-label="Floating label select example">--}}
                    {{--                                            <option value=""> جميع المحافظات</option>--}}
                    {{--                                        </select>--}}
                    {{--                                        <label for="floatingSelectGrid">المحافظات</label>--}}
                    {{--                                    </div>--}}
                    {{--                                </div>--}}

                    {{--                            <div class="col-lg-3 col-6">--}}
                    {{--                                <div class="form-floating">--}}
                    {{--                                    <select name="department" class="form-select" id="dep"--}}
                    {{--                                            aria-label="Floating label select example">--}}
                    {{--                                        <option value=""> جميع المناطق</option>--}}
                    {{--                                    </select>--}}
                    {{--                                    <label for="floatingSelectGrid">المناطق</label>--}}
                    {{--                                </div>--}}
                    {{--                            </div>--}}
                    {{--                            <div class="col-lg-3 col-6">--}}
                    {{--                                <div class="form-floating">--}}
                    {{--                                    <select name="claim_type" class="form-select" id="floatingSelectGrid"--}}
                    {{--                                            aria-label="Floating label select example">--}}
                    {{--                                        <option value=''>جميع الشكاوي</option>--}}
                    {{--                                    </select>--}}
                    {{--                                    <label for="floatingSelectGrid">نوع الشكوى</label>--}}
                    {{--                                </div>--}}
                    {{--                            </div>--}}
                    {{--                            <div class="col-lg-1 col-12"--}}
                    {{--                                 style="align-items: center;justify-content: center;display: flex; margin-bottom: 1em">--}}
                    {{--                                <button type="submit" tabindex="-1" class="btn btn-primary bg-blue"--}}
                    {{--                                        style="margin-top: 1em; font-weight:bold">تصنيف--}}
                    {{--                                </button>--}}
                    {{--                            </div>--}}
                    {{--                            <div class="col-lg-1 col-12"--}}
                    {{--                                 style="align-items: center;justify-content: center;display: flex; margin-bottom: 1em">--}}
                    {{--                                <button name="action" value="export" type="submit" tabindex="-1" class="btn btn-primary bg-green"--}}
                    {{--                                        style="margin-top: 1em; font-weight:bold">تصدير--}}
                    {{--                                </button>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    </form>--}}
                    <div class="row">
                        <div class="col col-xs-6"
                             style="display: flex;justify-content: end;align-items: center;">
                            <a href="{{route('products.create')}}">
                                <button type="button" class="btn btn-sm btn-primary btn-create bg-blue">إنشاء منتج
                                    جديد
                                </button>
                            </a>
                        </div>
                        <div class="col-md-12 col-md-offset-1">

                            <div class="panel panel-default panel-table">
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered table-list">
                                        <thead>
                                        <tr>
                                            <th class="hidden-xs "></th>
                                            <th scope="col">اسم المنتج</th>
                                            <th scope="col">السعر</th>
                                            {{--                                    <th scope="col">المنطقة</th>--}}
                                            {{--                                    <th scope="col">نوع الشكوى</th>--}}
                                            {{--                                    <th scope="col">حالة الشكوى</th>--}}
                                            <th scope="col"><em class="fa fa-cog"
                                                                style="display: flex; justify-content: center"></em>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($products as $product)
                                            <tr>
                                                <th scope="row" class="hidden-xs img-th"><img class="img"
                                                                                              src="{{\Illuminate\Support\Facades\Storage::url($product->image_url)}}"/>
                                                </th>
                                                <td class="pad-t-3">{{$product->name}} </td>
                                                <td class="pad-t-3"> {{$product->price}}</td>
                                                {{--                                        <td></td>--}}
                                                {{--                                        <td></td>--}}
                                                {{--                                        <td></td>--}}
                                                <td align="center">
                                                    <a class="btn btn-default pad-t "
                                                       href="{{route('products.edit', $product->id)}}"><em
                                                            class="fa fa-eye"></em></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    {{-- Pagination --}}
                                    <div class="d-flex justify-content-center">
                                        {!! $products->links() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        {{--let gov = @json($govers);--}}
        {{--let dep = '{{ $filters['department'] ?? false }}';--}}
        {{--$('select[name="governorate"]').change(function() {--}}
        {{--    $('#dep').empty();--}}
        {{--    let option = new Option('جميع المناطق', '', true, true);--}}
        {{--    $('#dep').append(option)--}}
        {{--    $(gov[$(this).val()].deps).each(dep => {--}}
        {{--        dep = gov[$(this).val()].deps[dep]--}}
        {{--        let option = new Option(dep.name, dep.id, false);--}}
        {{--        $('#dep').append(option)--}}
        {{--    })--}}
        {{--})--}}


    </script>
@endpush
