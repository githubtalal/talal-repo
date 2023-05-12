@extends('layouts.master')

@section('content')
    <div class="card claim-card">
        <div class="card-body">
            <form id="claim-form" action="{{route('claim')}}" method="post">
                @csrf
                <div class="row g-2">
                    <div class="col-12" style="margin-top: 1em;text-align: center;color:#047835;">
                        <h2 style="position: relative;top: 10px;">تسجيل شكوى</h2>
                    </div>
                    <hr class="hr">
                    <div class="col-12" style="margin: 1em;margin-bottom: 0;color:#0E497D;">
                        <h5>معلومات صاحب الشكوى</h5>
                    </div>
                    <div class="col-lg-6 col-12 p-0-1">
                        <div class="form-floating form-claim">
                            <input name="name" type="text"
                                   class="form-input name_input form-control @error('name') is-invalid @enderror"
                                   id="name" placeholder="الإسم الكامل" required>
                            <label for="floatingInputGrid">الإسم<span class="red">*</span></label>
                            @error('name')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-12 p-0-1">
                        <div class="form-floating form-claim">
                            <input name="phone_number" type="text"
                                   class="form-input phone_input form-control @error('phone_number') is-invalid @enderror"
                                   id="floatingInputGrid" placeholder="0999999999" minlength="10" maxlength="10"
                                   required>
                            <label for="floatingInputGrid">رقم الجوال<span class="red">*</span></label>
                            @error('phone_number')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12" style="margin-top: .5em ">
                        <p style="font-size:0.9em;text-align:start;margin-right: 1em">ملاحظة: حفاظآ على خصوصيتكم لن يتم
                            مشاركة المعلومات مع المشتكى عليه او موظفي التموين</p>
                    </div>

                    <div class="col-12" style="margin: 1em;margin-bottom: 0;color:#0E497D;">
                        <h5>معلومات المشتكى عليه</h5>
                    </div>
                    <div class="col-lg-6 col-12 p-0-1" style="margin-left: 1em">
                        <div class="form-floating form-claim">
                            <input name="store_name" type="text"
                                   class="form-input store_input form-control @error('store_name') is-invalid @enderror"
                                   id="floatingInputGrid" placeholder="اسم المتجر" required>
                            <label for="floatingInputGrid">اسم المتجر (كما مسجل على الآرمة)<span
                                    class="red">*</span></label>
                            @error('store_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-12 p-0-1">
                        <div class="form-floating form-claim">
                            <select name="governorate"
                                    class="form-input governorate_input form-select @error('governorate') is-invalid @enderror"
                                    id="gover" aria-label="Floating label select example" required>
                                <option disabled selected value> -- إختر المحافظة --</option>
                                @foreach($govers as $gover)
                                    <option value="{{$gover['id']}}">{{$gover['name']}}</option>
                                @endforeach
                            </select>
                            <label for="floatingSelectGrid">المحافظات<span class="red">*</span></label>
                            @error('governorate')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-12 p-0-1">
                        <div class="form-floating form-claim">
                            <select id="dep" name="department"
                                    class="form-input department_input form-select @error('department') is-invalid @enderror"
                                    aria-label="Floating label select example" required>
                                <option disabled selected value> -- إختر المنطقة --</option>
                            </select>
                            <label for="floatingInputGrid">المنطقة<span class="red">*</span></label>
                            @error('department')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 p-0-1">
                        <div class="form-floating form-claim">
                            <textarea name="address"
                                      class="form-input address_input form-control @error('address') is-invalid @enderror"
                                      placeholder="العنوان" id="floatingTextarea2" style="height: 70px" required
                            ></textarea>
                            <label for="floatingTextarea2">العنوان بالتفصيل<span class="red">*</span></label>
                            @error('address')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12" style="margin: 1em;margin-bottom: 0;color:#0E497D;">
                        <h5>معلومات الشكوى</h5>
                    </div>
                    {{--                    <div class="col-lg-6 col-12 p-0-1">--}}
                    {{--                        <div class="form-floating form-claim">--}}
                    {{--                            <select name="claim_type"--}}
                    {{--                                    class="form-input claim_type_input form-select @error('claim_type') is-invalid @enderror"--}}
                    {{--                                    id="floatingSelectGrid" aria-label="Floating label select example"--}}
                    {{--                                    style="margin-top: 0" required>--}}
                    {{--                                <option disabled selected value> -- اختر نوع الشكوى --</option>--}}
                    {{--                                @foreach(\App\Models\Claim::$types as $key => $value)--}}
                    {{--                                    <option value="{{$key}}">{{$value}}</option>--}}
                    {{--                                @endforeach--}}
                    {{--                            </select>--}}
                    {{--                            <label for="floatingSelectGrid"> نوع الشكوى<span class="red">*</span></label>--}}
                    {{--                            @error('claim_type')--}}
                    {{--                            <div class="alert alert-danger">{{ $message }}</div>--}}
                    {{--                            @enderror--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    <div class="col-12 p-0-1">
                        <div class="form-floating form-claim">
                            <textarea name="claim"
                                      class="form-input claim_input form-control @error('claim') is-invalid @enderror"
                                      placeholder="الشكوى" id="claim" style="height: 70px" required
                            ></textarea>
                            <label for="floatingTextarea2">تفاصيل الشكوى<span class="red">*</span></label>
                            @error('claim')
                            <div class="alert alert-danger">{{ __("validation.$message") }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-8 col-12" style="margin: 1.5em 1em;margin-left: 0">
                        <p style="font-size:0.9em;text-align: start">يرجى إرفاق أي صورة أو دلالة مرتبطة بالشكوة المقدممة
                            (إن وجدت)</p>
                    </div>
                    <div class="col-lg-3 p-0-1" style="margin-top: 1.5em">
                        <label for="file-upload" class="custom-file-upload"
                               style="font-weight:bold;border-radius: 8px;background-color: #f4f4f4">
                            <i class="fa fa-cloud-upload"></i> المرفقات
                        </label>
                        <input class="url_input" name="attachment_url" id="file-upload" type="file"
                               accept=".png,.pdf,.jpeg,.jpg"/>
                    </div>

                </div>
                <button type="submit" class="btn btn-primary claim-btn bg-blue">إرسال</button>

            </form>
            <div class="loader-back d-none" id="loading">
                <div class="cm-spinner"></div>
            </div>
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{route('checkOtp')}}" id="otp-form" method="post">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">رمز التأكيد</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="col-12" style="margin-bottom: 0">
                                    <p style="font-size:0.9em;text-align:start;margin-right: 1em"> ستصلكم رسالة نصية على
                                        الرقم المدون أعلى برمز خاص قبل تثبيت الشكوى</p>
                                </div>
                                <div class="col-12 p-0-1">
                                    <div class="form-floating">
                                        <input name="otp" type="text"
                                               class="form-control otp_input"
                                               id="floatingInputGrid" placeholder="5555" required>
                                        <label for="floatingInputGrid">الرمز</label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                            <button type="submit" class="btn btn-primary bg-blue">إرسال</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(() => {
            $('#file-upload').change(function () {
                var i = $(this).prev('label').clone();
                var file = $('#file-upload')[0].files[0].name;
                $(this).prev('label').text(file);
            });
            let gov = @json($govers);
        $('#gover').on('change',function (){
            let gover = $('#gover').val()
            console.log(gov, gover)
            $('#dep').empty();
            $(gov[gover].deps).each(dep => {
                dep = gov[gover].deps[dep]
                let option = new Option(dep.name, dep.id, false);
                $('#dep').append(option)
                console.log(option)
            })
        })
            $("#claim-form").on('submit', function (e) {
                $("#loading").removeClass('d-none').addClass('d-flex');
                $(document).ajaxStop(function () {
                    $("#loading").addClass('d-none').removeClass('d-flex');
                });
                e.preventDefault();
                let claim_validator = $("#claim-form").validate()
                if (claim_validator) {

                    var formElement = document.getElementById("claim-form");
                    var formData = new FormData(formElement);
                    $.ajax({
                        url: "/claim/sendOtp",
                        type: 'POST',
                        datatype: 'json',
                        enctype: 'multipart/form-data',
                        contentType: false,
                        processData: false,
                        cache: false,
                        data: formData,
                        success: function (response) {
                            $('#exampleModal').modal('show');
                            console.log(response.otp)
                        },
                        error: function () {
                            alert('الرجاء المحاولة مرة أخرى')
                            $("#loading").addClass('d-none').removeClass('d-flex');
                        }
                    })
                    $("#otp-form").on('submit', function (e) {
                        e.preventDefault();
                        let otp_validator = $("#otp-form").validate()
                        if (otp_validator) {
                            let otp = $('.otp_input').val();
                            $.ajax({
                                url: "/claim/checkOtp" + '?_token=' + '{{ csrf_token() }}',
                            method: "POST",
                            dataType: "json",
                            data: {"_token": "{{ csrf_token() }}",'otp': otp},
                            success: function (response) {
                                if (response.success) {
                                    window.location.href = response.redirect;
                                }
                                otp_validator.showErrors({
                                    "otp": response.error
                                })
                            }
                        })

                    }
                })
            } else {
                claim_validator.showErrors({
                    "name": "الرجاء  التحقق من الإسم الكامل",
                    "phone_number": "الرجاء التحقق من رقم الجوال",
                    "governorate": "الرجاء إختيار محافظة",
                    "department": "الرجاء إختيار منطقة",
                    "address": "الرجاء  التحقق من العنوان المفصل",
                    "claim_type": "الرجاء إختيار نوع الشكوى",
                    "claim": "الرجاء التحقق من الشكوى",
                })
                $("html, body").animate({scrollTop: 0}, 200);
            }
        })
        });
    </script>
@endpush

