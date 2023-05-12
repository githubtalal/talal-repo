@extends('dashboard.layouts.master')

@section('content')
    <div class="dashboard">
        <div class="container">
            <div class="card">
                <div class="card-body p-2-4">
                    <div class="row">
                        <div class="row">
                            <div class="col col-xs-6"
                                 style="display: flex;justify-content: end;align-items: center;">
                                <a href="{{route('category.create')}}">
                                    <button type="button" class="btn btn-sm btn-primary btn-create bg-blue">
                                        إنشاء خطة جديدة
                                    </button>
                                </a>
                            </div>
                            <div class="col-md-12 col-md-offset-1">

                                <div class="panel panel-default panel-table">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col col-xs-6">
                                                <h3 class="panel-title">
                                                    الخطط
                                                </h3>
                                            </div>
                                            {{--                                        <div class="col col-xs-6"--}}
                                            {{--                                             style="display: flex;justify-content: end;align-items: center;">--}}
                                            {{--                                            <a href="{{route('create-product')}}">--}}
                                            {{--                                                <button type="button" class="btn btn-sm btn-primary btn-create bg-blue">إنشاء منتج جديد</button>--}}
                                            {{--                                            </a>--}}
                                            {{--                                        </div>--}}
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-striped table-bordered table-list">
                                            <thead>
                                            <tr>
                                                <th scope="col">الأسم</th>
                                                <th scope="col"><em class="fa fa-cog"
                                                                    style="display: flex; justify-content: center"></em>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($plans as $plan)
                                                <tr>
                                                    <td>{{ $plan->name }}</td>
                                                    <td align="center">
                                                        <a class="btn btn-default"
                                                           href="{{route('category.edit',['category' => $plan->id])}}"><em
                                                                class="fa fa-eye"></em></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        {{-- Pagination --}}
                                        <div class="d-flex justify-content-center">
                                            {!! $plans->links() !!}
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
