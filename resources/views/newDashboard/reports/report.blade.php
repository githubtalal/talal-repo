@extends('newDashboard.layouts.master')
@section('content')
    @php
        $path = explode('/', url()->current());
        $type = $path[count($path) - 2];
        
        $columns = $report->getColumns();
        $records = $report->getPaginatedData();
        $tableName = $report->getTableName();
        
        foreach ($columns as $column) {
            $heads[] = $column['name'];
        }
    @endphp

    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div class="toolbar">
            <!--begin::Container-->
            <div class="container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                        {{ __('app.listing', ['name' => $tableName]) }}
                    </h1>
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" style="margin-bottom: 30px">
            <!--begin::Container-->
            <div class="container-xxl">
                <!--begin::Card header-->
                <div class="card card-flush">

                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-title">
                            <form id="orderByForm" action="{{ route('report', [$type, 0]) }}" method="get"
                                enctype="multipart/form-data">

                                <select class="form-select w-200px orderBySelect" data-control="select2"
                                    data-hide-search="true" data-placeholder="Order by" name="order" id="">
                                    <option disabled selected>{{ __('app.reports.orderBy') }}</option>
                                    <option value="orders_count">{{ __('app.reports.orders_count') }}</option>
                                    <option value="orders_total">{{ __('app.reports.orders_values') }}</option>
                                </select>

                            </form>
                        </div>
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                            <form action="{{ route('report', [$type, 1]) }}" method="GET" id=""
                                enctype="multipart/form-data">
                                <input type="hidden" name="order" value="{{ request('order') }}" />
                                <button type="submit" class="btn btn-primary">Export</button>
                            </form>
                        </div>
                        <!--end::Card toolbar-->
                    </div>

                    {{--                    <div class="mb-10 fv-row col-lg-3 filtration"> --}}
                    {{--                        <form action="{{ route('report', [$type, 0]) }}" method="GET" id="" --}}
                    {{--                            enctype="multipart/form-data"> --}}
                    {{--                            @csrf --}}
                    {{--                            <div id="inputsDiv"> --}}

                    {{--                                <select class="form-select mb-2 filterSelect" data-control="select2" data-hide-search="true" --}}
                    {{--                                    data-placeholder="Select an option" name="" id=""> --}}
                    {{--                                    <option disabled selected>Select an option</option> --}}
                    {{--                                    @foreach ($report->getFilters() as $filter) --}}
                    {{--                                        <option class="{{ $filter['type'] }}" value="{{ $filter['name'] }}"> --}}
                    {{--                                            {{ $filter['name'] }} --}}
                    {{--                                        </option> --}}
                    {{--                                    @endforeach --}}
                    {{--                                </select> --}}

                    {{--                            </div> --}}

                    {{--                            <button type="submit" class="btn btn-primary submitFilter d-none">filter</button> --}}
                    {{--                        </form> --}}
                    {{--                    </div> --}}
                    <!--end::Card header-->
                    <!--begin::items info-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    @foreach ($columns as $column)
                                        <th class="min-w-100px">{{ $column['name'] }}</th>
                                    @endforeach
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-bold text-gray-600">
                                @foreach ($records as $item)
                                    <tr>
                                        @foreach ($item as $key => $value)
                                            <td>
                                                <span class="text-gray-800 fw-bolder">{{ $value }}</span>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                        <div>
                            {!! $records->links() !!}
                        </div>
                    </div>
                    <!--end::items info-->
                </div>
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
    <!--end::Content-->
@endsection


<style>
    .filtration {
        margin: 30px
    }
</style>

@push('scripts')
    <script>
        $(document).ready(function() {

            $(".orderBySelect").change(function() {
                $("#orderByForm").submit();
            });

            /*
                        {{-- $('.filterSelect').on('change', function() { --}}

                        {{--    var originOption = $('.filterSelect').val(); --}}

                        {{--    var type = $('.filterSelect option:selected').attr('class'); --}}

                        {{--    var option = originOption.replace(/\./g, '_'); --}}

                        {{--    if ($('div.' + option).length === 0) { --}}
                        {{--        if (type == 'string') --}}
                        {{--            $('#inputsDiv').append( --}}
                        {{--                '<div class="fv-row ' + option + ' ">' + --}}
                        {{--                '<label class="form-label">Relation:</label>' + --}}
                        {{--                '<select id="" name="filters[' + originOption + --}}
                        {{--                '][relation]" class="form-select mb-2">' + --}}
                        {{--                '<option selected value="and">and</option>' + --}}
                        {{--                '<option value="or">or</option>' + --}}
                        {{--                '</select>' + --}}
                        {{--                '<label class="form-label">' + option + '</label>' + --}}
                        {{--                '<select id="operator" name="filters[' + originOption + --}}
                        {{--                '][op]" class="form-select mb-2 operatorSelect ' + type + '">' + --}}
                        {{--                '<option disabled selected>Select an operator</option>' + --}}
                        {{--                '<option value="{{ '=' }}">=</option>' + --}}
                        {{--                '<option value="{{ '!=' }}">=!</option>' + --}}
                        {{--                '</select>' + --}}
                        {{--                '</div>' --}}
                        {{--            ) --}}
                        {{--        else if (type == 'date' || type == 'number') --}}
                        {{--            $('#inputsDiv').append( --}}
                        {{--                '<div class="fv-row ' + option + ' " style="">' + --}}
                        {{--                '<label class="form-label">Relation:</label>' + --}}
                        {{--                '<select id="" name="filters[' + originOption + --}}
                        {{--                '][relation]" class="form-select mb-2">' + --}}
                        {{--                '<option selected value="and">and</option>' + --}}
                        {{--                '<option value="or">or</option>' + --}}
                        {{--                '</select>' + --}}
                        {{--                '<label class="form-label">' + option + '</label>' + --}}
                        {{--                '<select id="operator" name="filters[' + originOption + --}}
                        {{--                '][op]" class="form-select mb-2 operatorSelect ' + type + '">' + --}}
                        {{--                '<option disabled selected>Select an operator</option>' + --}}
                        {{--                '<option value="{{ '<' }}"><</option>' + --}}
                        {{--                '<option value="{{ '>' }}">></option>' + --}}
                        {{--                '<option value="{{ '>' . '=' }}">=<</option>' + --}}
                        {{--                '<option value="{{ '=' . '>' }}">=></option>' + --}}
                        {{--                '<option value="{{ '=' }}">=</option>' + --}}
                        {{--                '<option value="{{ '!=' }}">=!</option>' + --}}
                        {{--                '<option value="{{ 'between' }}">between</option>' + --}}
                        {{--                '</select>' + --}}
                        {{--                '</div>' --}}
                        {{--            ) --}}
                        {{--    } --}}

                        {{--    $('.operatorSelect.' + type + '').on('change', function() { --}}
                        {{--        if ($('.operatorSelect.' + type + '').val() === 'between') { --}}
                        {{--            if ($('.operator.between.' + option).length === 0) { --}}
                        {{--                console.log(option); --}}
                        {{--                $('#inputsDiv').append( --}}
                        {{--                    '<div class="operator between ' + option + --}}
                        {{--                    '" >' + --}}
                        {{--                    '<input type="' + type + --}}
                        {{--                    '" class="form-control" name="filters[' + --}}
                        {{--                    originOption + '][fromVal]" required />' + --}}
                        {{--                    '<input id="' + originOption + --}}
                        {{--                    '" type="' + type + '" class="form-control" name="filters[' + --}}
                        {{--                    originOption + '][toVal]" required />' + --}}
                        {{--                    '</div>' --}}
                        {{--                ); --}}
                        {{--                $('.operator.val.' + option).remove(); --}}
                        {{--                $('.submitFilter').removeClass("d-none"); --}}
                        {{--            } --}}
                        {{--        } else { --}}
                        {{--            if ($('.operator.val.' + option).length === 0) { --}}
                        {{--                $('#inputsDiv').append( --}}
                        {{--                    '<div class="operator val ' + option + --}}
                        {{--                    '" >' + --}}
                        {{--                    '<input type="text" class="form-control" name="filters[' + --}}
                        {{--                    originOption + '][value]" required />' + --}}
                        {{--                    '</div>' --}}
                        {{--                ) --}}
                        {{--                $('.operator.between.' + option).remove(); --}}
                        {{--                $('.submitFilter').removeClass("d-none"); --}}
                        {{--            } --}}
                        {{--        } --}}

                        {{--    }); --}}
                        {{-- }); --}}*/
        });
    </script>
@endpush
