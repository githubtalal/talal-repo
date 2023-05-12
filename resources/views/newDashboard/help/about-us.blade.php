@extends('newDashboard.layouts.master')
@section('content')
    <!--begin::Post-->
    <div class="content d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div class="toolbar">
            <!--begin::Page title-->
            <div class="container-fluid">
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                    {{ __('app.about_us') }}
                </h1>
            </div>
        </div>
        <!--begin::Container-->
        <div class="container-xxl" style="margin-bottom: 40px">
            <!--begin::About us card-->
            <div class="card">
                <!--begin::Body-->
                <div class="card-body p-lg-15">
                    <!--begin::Row-->
                    <div class="row mb-12">
                        <!--begin::Col-->
                        <div class="col-md-6 pe-md-10 mb-10 mb-md-0">
                            <!--begin::Form-->
                            <form action="{{ route('store_about_us') }}" class="form mb-15" method="post">
                                @csrf

                                <div class="d-flex flex-column mb-5 fv-row">
                                    <!-- comment talal <textarea class="form-control mb-2" rows="6" name="about_us" placeholder="{{ __('app.about_us') }}">{{ $value }}</textarea> end -->
                                    <div id="about-us" class="min-h-200px"></div>
                                    <textarea class="d-none" id="about_us" name="about_us" placeholder="{{ __('app.about_us') }}"></textarea>
                                </div>

                                <x-inputs.save_button></x-inputs.save_button>

                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::About us card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
@endsection

@push('script')
<script>

    $(document).ready(function(){
        quill = new Quill('#about-us', {
                modules: {
                    toolbar: [
                        [{
                            header: [1, 2, false]
                        }],
                        ['bold', 'italic', 'underline', 'link'],
                        ['align', {
                            align: 'center'
                        }],
                        ['align', {
                            align: 'right'
                        }],
                    ]
                },
                theme: 'snow',
            });

            realHTML = `{!! $value !!}`;
            quill.clipboard.dangerouslyPasteHTML(realHTML);

            quill.on('text-change', function(delta, oldDelta, source) {
                $("#about_us").html(quill.root.innerHTML);
            });
    });
</script>
@endpush
