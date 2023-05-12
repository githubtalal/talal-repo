@extends('newDashboard.layouts.master')
@section('content')
    <x-add_edit_item :name="'إضافة حجز جديد'">
        <!--begin::Form-->
        <form class="form d-flex flex-column flex-lg-row reservationForm" action="{{ route('reservations.store') }}"
            method="post" enctype="multipart/form-data">
            @csrf
            <!--begin::Main column-->
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <!--begin::Tab content-->
                <div class="tab-content">
                    <!--begin::Tab pane-->
                    <div class="tab-pane fade show active" role="tab-panel">
                        <div class="d-flex flex-column gap-7 gap-lg-10">

                            <!--begin::General options-->
                            <div class="card card-flush py-4">
                                <!--begin::Card header-->
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>معلومات الحجز</h2>
                                    </div>
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body pt-0">

                                    <div class="mb-10 row d-flex">
                                        <!--begin::Phone Number-->
                                        <div class="col-lg-4 me-10">
                                            <label for="phone" class="required form-label">رقم الهاتف</label>
                                            <input type="phone" name="phone" value="" id="phone"
                                                class="form-control mb-2 @error('phone') is-invalid @enderror"
                                                placeholder="رقم الهاتف" />

                                            @error('phone')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--end::Phone Number-->
                                        <!--begin::Name-->
                                        <div class="col-lg-4">
                                            <x-inputs.text_input :label="'الاسم الكامل'" :name="'name'" :value="''">
                                            </x-inputs.text_input>
                                        </div>
                                        <!--end::Name-->
                                    </div>

                                    <div class="row d-flex">

                                        @if ($steps)

                                            @if (array_key_exists('governorate', $steps))
                                                <!--begin::Governorates-->
                                                <div class="col-lg-4 me-10 mb-10">
                                                    <label class="form-label">اختر محافظة</label>
                                                    <select class="form-select mb-2" data-control="select2"
                                                        data-hide-search="true" data-placeholder="Select an option"
                                                        name="governorate" id="">
                                                        @foreach (get_states() as $key => $value)
                                                            <option value="{{ $key }}"
                                                                {{ 'damascus' == $key ? 'selected' : '' }}>
                                                                {{ $value }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <!--end::Governorates-->
                                            @endif

                                            @if (array_key_exists('address', $steps))
                                                <!--begin::Address-->
                                                <div class="col-lg-4 me-10 mb-10">

                                                    <label for="address" class="form-label">
                                                        العنوان</label>

                                                    <input type="text" name="address" class="form-control mb-2"
                                                        id="" placeholder="العنوان" value="" />

                                                </div>
                                                <!--end::Address-->
                                            @endif

                                            @if (array_key_exists('notes', $steps))
                                                <!--begin::Notes-->
                                                <div class="col-lg-4 me-10 mb-10">
                                                    <label for="notes" class="form-label">
                                                        {{ $notesLabel }}</label>

                                                    <input type="text" name="notes" class="form-control" id=""
                                                        placeholder="{{ $notesLabel }}" value="" />
                                                </div>
                                                <!--end::Notes-->
                                            @endif

                                            @if (array_key_exists('question1', $steps))
                                                <!--begin::question1-->
                                                <div class="col-lg-4 me-10 mb-10">
                                                    <label for="" class="form-label">
                                                        {{ $q1Value }}</label>

                                                    <input type="text" name="question1" class="form-control"
                                                        id="" placeholder="{{ $q1Value }}" value="" />
                                                </div>
                                                <!--end::question1-->
                                            @endif

                                            @if (array_key_exists('question2', $steps))
                                                <!--begin::question2-->
                                                <div class="col-lg-4 me-10 mb-10">
                                                    <label for="" class="form-label">
                                                        {{ $q2Value }}</label>

                                                    <input type="text" name="question2" class="form-control"
                                                        id="" placeholder="{{ $q2Value }}" value="" />
                                                </div>
                                                <!--end::question2-->
                                            @endif

                                        @endif

                                        <!--begin::Payment Methods-->
                                        <div class="col-lg-4 me-10 mb-10">
                                            <label class="form-label">{{ $payment_method_message }}</label>
                                            <select class="form-select" data-control="select2" data-hide-search="true"
                                                data-placeholder="Select an option" name="payment_method" id="">
                                                @foreach ($methods as $method)
                                                    <option value="{{ $method->getKey() }}">
                                                        {{ $method->getLabel() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!--end::Payment Methods-->
                                    </div>

                                    <div class="mb-10 row d-flex">
                                        <!--begin::Products-->
                                        <div class="col-lg-4 me-10">
                                            <label class="form-label">المنتجات</label>
                                            <select class="form-select products @error('products') is-invalid @enderror"
                                                name="products[]">
                                                @foreach ($products as $product)
                                                    <option id="{{ $product->id }}"
                                                        class="{{ $product->require_end_date == 1 ? 'require_end_date' : '' }}"
                                                        value="{{ $product->id }}">
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('products')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--end::Payment Methods-->
                                    </div>

                                    <div class="accordion col-lg-8" id="accordionExample">
                                    </div>

                                    <!-- <div class="modal fade" id="currencyDifferentModal" tabindex="-1" role="dialog"
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <div class="modal-dialog" role="document">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <div class="modal-content">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <div class="modal-header">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <h5 class="modal-title" id="exampleModalLabel">تنبيه</h5>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <div class="modal-body">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <h5 class="" id="">يوجد لديك منتجات ذات عملات مختلفة</h5>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <div class="modal-footer">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <button type="submit" class="btn btn-primary">إضافة طلب
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            جديد</button>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            onclick="$('#currencyDifferentModal').modal('hide');">إلغاء</button>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </div>-->

                                    <!--Buttons-->
                                    <div class="d-flex justify-content-end">
                                        <!--Save button-->
                                        <x-inputs.save_button></x-inputs.save_button>
                                        <!--cancel button-->
                                        <a href="{{ route('reservations.index') }}" class="btn btn-light ms-3">إلغاء</a>
                                    </div>
                                </div>
                            </div>
                            <!--end::General options-->
                        </div>
                    </div>
                    <!--end::Tab pane-->
                </div>
                <!--end::Tab content-->
            </div>
            <!--end::Main column-->
        </form>
        <!--end::Form-->
    </x-add_edit_item>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            var currency;
            var ifCurrencyDifferent;
            var products_ids = [];

            $('.products').select2({
                placeholder: "ابحث عن منتج",
                allowClear: true,
                multiple: true,
            });

            if ($('.products').hasClass("select2-hidden-accessible")) {
                $('.products').val(null).trigger('change');
            }

            var count = 1;

            $(".products").on("select2:select", function(e) {
                var products = $(e.currentTarget).val();

                productName = $('select #' + products[0]).text();

                ifHourEnabled = '{{ ifHourEnabled() }}';
                if (ifHourEnabled)
                    hourFormat = 'H:i';
                else
                    hourFormat = '';

                var item = "<div class='card card" + count + "' id='" + count + "'>" +
                    "<div class='card-header head" + count + "' id=" + count + " style='padding:25px'>" +
                    "<h5 class='mb-0'>" +
                    "<button class='btn' type='button' data-toggle='collapse'" +
                    "data-target='#collapse" + count + "'" +
                    " aria-expanded='true'" +
                    "aria-controls='#collapse" + count + "'>" +
                    productName +
                    "</button>" +
                    "</h5>" +
                    "</div>" +
                    "<div id='collapse" + count + "'" +
                    " class='collapse show' aria-labelledby='product'" +
                    "data-parent='#accordionExample'>" +
                    "<div class='card-body'>" +
                    "<input type='hidden' class='product_id' id='" + products[0] + "' name='products[" +
                    count + "][product_id]' value='" +
                    products[0] +
                    "'/>" +
                    "<div class='start" + count + " d-flex row mb-6'>" +
                    "<div class='col-lg-6'>" +
                    "<label for='start_date' class='required form-label'>" +
                    "تاريخ البداية</label>" +
                    "<input class='form-control start_date single_date_picker' required " +
                    "name='products[" + count +
                    "][start_date]' hourEnabled='" + ifHourEnabled + "' value='' hourFormat='" +
                    hourFormat + "' id='" + count + "'/>" +
                    "</div>" +
                    "</div>";

                if ($('select #' + products[0]).hasClass('require_end_date')) {

                    item += "<div class='end" + count + " d-flex row mb-6'>" +
                        "<div class='col-lg-6'>" +
                        "<label for='end_date' class='required form-label'>" +
                        "تاريخ النهاية</label>" +
                        "<input class='form-control end_date single_date_picker' required " +
                        "name='products[" + count + "][end_date]' hourEnabled='" + ifHourEnabled +
                        "' value='' id='' hourFormat='" +
                        hourFormat + "' />" +
                        "</div>" +
                        "</div>";
                }

                item += "</div>" +
                    "</div>" +
                    "</div>";

                var deleteButton = "<button type='button' class='btn btn-light delete" + count +
                    "' onclick=$('.card" +
                    count +
                    "').remove();>حذف</button>";

                $('.products').val(null).trigger('change');
                $('.accordion').append(item);
                $('.head' + count).append(deleteButton);

                count++;
            });

            $("#phone").on("change", function(e) {
                number = $("#phone").val();

                $.ajax({
                    url: "{{ route('reservations.get_customer_details') }}",
                    type: 'GET',
                    data: {
                        number: number
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('input[name=name]').val(response.first_name +
                            ' ' + response.last_name);
                    }
                });
            });

            $('body').on('focus', '.single_date_picker', function() {
                $(this).flatpickr({
                    enableTime: Boolean($(this).attr('hourEnabled')),
                    dateFormat: "Y-m-d " + $(this).attr('hourFormat'),
                    disableMobile: "true"
                });
            });

            $('#submit').on('click', function() {

                $('.card').each(function() {
                    var id = $(this).attr('id');
                    $('.start' + id + ' input').each(function() {
                        if ($(this).prop('required')) {
                            if (!$(this).val()) {
                                $('#collapse' + id).removeClass('collapsed')
                                    .attr('aria-expanded', 'true');
                                $('#collapse' + id).addClass('show');
                            }
                        }
                    });

                    $('.end' + id + ' input').each(function() {
                        if ($(this).prop('required')) {
                            if (!$(this).val()) {
                                $('#collapse' + id).removeClass('collapsed')
                                    .attr('aria-expanded', 'true');
                                $('#collapse' + id).addClass('show');
                            }
                        }
                    });
                });
            });

            jQuery('.reservationForm').validate({
                rules: {
                    start_date: {
                        required: true,
                    },
                    end_date: {
                        required: true,
                    },
                }
            });
        });
    </script>
@endpush
