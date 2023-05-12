@extends('layouts.master')

@section('content')
    <div class="dashboard">
        <div class="card admin-view-card">
            <div class="card-body p-res">

                <div class="row">
                    <div class="col-6"><h3>إستعراض الشكوى</h3></div>
                    <div class="col-3">
                        <a href="{{ route('print', $claims->id) }}" type="button" class="btn btn-primary claim-btn bg-blue"
                           data-bs-dismiss="modal" aria-label="Close" style="margin: 0em 1em ;">
                            تصدير
                        </a>
                    </div>
                    <div class="col-3">
                        <button onclick="window.history.back()" type="button" class="btn btn-primary claim-btn bg-green"
                                data-bs-dismiss="modal" aria-label="Close" style="margin: 0em 1em ;">الرجوع
                        </button>
                    </div>

                    <div class="col-6 custom-div">
                        <p>رقم الشكوى</p>
                    </div>
                    <div class="col-6 custom-div">
                        <p>{{$claims->code}}</p>
                    </div>
                    @if(auth()->user()->role === 1)
                        <div class="col-6 custom-div">
                            <p>الأسم</p>
                        </div>
                        <div class="col-6 custom-div">
                            <p>{{$claims->name}}</p>
                        </div>
                        <div class="col-6 custom-div">
                            <p>رقم الجوال</p>
                        </div>
                <div class="col-6 custom-div">
                    <p>{{$claims->phone_number}}</p>
                </div>
            @endif
            @php
                $dep = \Illuminate\Support\Facades\DB::table('district')->find($claims->department);
                $gov = \Illuminate\Support\Facades\DB::table('city')->find($claims->governorate);
            @endphp
                    <div class="col-6 custom-div">
                        <p>المحافظة</p>
                    </div>
                    <div class="col-6 custom-div">
                        <p>{{$gov->name}}</p>
                    </div>
                    <div class="col-6 custom-div bg-f4">
                        <p>الدائرة</p>
                    </div>
                    <div class="col-6 custom-div bg-f4">
                        <p>{{$dep->name}}</p>
                    </div>
                    <div class="col-6 custom-div">
                        <p>نوع الشكوى</p>
                    </div>
                    <div class="col-6 custom-div">
                        <p>{{$claims->type->name ?? '---'}}</p>
                    </div>
                    @if(auth()->user()->role === 1)
                        <div class="col-6 custom-div bg-f4">
                            <p>العنوان</p>
                        </div>
                        <div class="col-6 custom-div bg-f4">
                            <p>{{$claims->address}}</p>
                        </div>
                    @endif
                    <div class="col-6 custom-div bg-f4">
                <p>الشكوى</p>
            </div>
            <div class="col-6 custom-div bg-f4">
                <p>{{$claims->claim}}</p>
            </div>
            <div class="col-6 custom-div">
                <p>المرفقات</p>
            </div>
            <div class="col-6 custom-div">
                @if(!$claims->attachment_url == null)
                <a href="{{asset($url)}}" download><i class="fa fa-download" aria-hidden="true"></i></a>
                @endif
            </div>
            <div class="col-6 custom-div bg-f4">
                <p>حالة الشكوى</p>
            </div>
            <div class="col-6 custom-div bg-f4">
                <p>{{$claims->status}}</p>
            </div>
            <div class="col-6 custom-div">
                <p>المرفقات الموظف</p>
            </div>
            <div class="col-6 custom-div">
                @if(!$claims->attachment == null)
                    <a href="{{asset($url_2)}}" download><i class="fa fa-download" aria-hidden="true"></i></a>
                @endif
            </div>
            @if(auth()->user()->role === 1)
                <div class="col-6 custom-div bg-f4">
                    <p>اسم الموظف </p>
                </div>
                        @if(!($logs ===null))
                            @php
                                $log = \Illuminate\Support\Facades\DB::table('users')->find($logs->user_id);
                            @endphp

                            <div class="col-6 custom-div bg-f4">
                                <p>{{$log->name}}</p>
                            </div>
                        @endif
                        @if($logs===null)
                            <div class="col-6 custom-div bg-f4">
                                <p></p>
                            </div>
                        @endif
                        <div class="col-6 custom-div bg-f4">
                            <p>تاريخ و توقيت حفظ الطلب</p>
                        </div>
                        @if($logs===null)
                            <div class="col-6 custom-div bg-f4">
                                <p></p>
                            </div>
                        @endif
                        @if(!($logs ===null))
                            <div class="col-6 custom-div bg-f4">
                                <p>{{$logs->created_at}}</p>
                            </div>
                        @endif
                    @endif
                </div>
                @if(auth()->user()->role == 1)
                    <form id="result-form" action="{{route('admin-view', ['claims' => $claims->id])}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 p-0-1">
                                <div class="form-floating">
                                    <select name="status"
                                            class="status  form-select @error('status') is-invalid @enderror"
                                            id="floatingSelectGrid" aria-label="Floating label select example">
                                        <option value="قيد المعالجة">قيد المعالجة</option>
                                        <option value="تمت المعالجة ويوجد مخالفة تم تنظيم ضبط">تمت المعالجة ويوجد مخالفة
                                            تم تنظيم ضبط
                                        </option>
                                        <option value="تمت المعالجة ولا يوجد مخالفة تم إعداد تقرير دورية">تمت المعالجة
                                            ولا يوجد مخالفة تم إعداد تقرير دورية
                                        </option>
                                    </select>
                                    <label for="floatingSelectGrid">حالة الشكوى<span class="red">*</span></label>
                                    @error('status')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-floating form-claim">
                                        <select name="claim_type"
                                                class="claims-types form-input claim_type_input form-select @error('claim_type') is-invalid @enderror"
                                                id="floatingSelectGrid" aria-label="Floating label select example"
                                                style="margin-top: 0" required>
                                            <option disabled selected value> -- اختر نوع الشكوى --</option>
                                            @foreach($options as $option)
                                                <option value="{{$option->id}}">{{$option->name}}</option>
                                            @endforeach
                                        </select>
                                        <label class="selcet2-claims-type" for="floatingSelectGrid"> نوع الشكوى<span
                                                class="red">*</span></label>
                                        @error('claim_type')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div id="claim_note_div" class="form-floating d-none">
                                <textarea id="claim_note" name="claim_note" class="form-control"
                                          placeholder="نتيجة المعالجة" style="height: 70px"></textarea>
                                        <label for="floatingTextarea2">نتيجة المعالجة</label>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <label for="file-upload" class="custom-file-upload"
                                           style="font-weight:bold;border-radius: 8px;background-color: #f4f4f4">
                                        <i class="fa fa-cloud-upload"></i> المرفقات
                                    </label>
                                    <input class="url_input" name="attachment" id="file-upload" type="file"/>
                                </div>
                                <div class="col-lg-12" style="display:flex;justify-content: end">
                                    <button type="submit" class="btn btn-primary claim-btn bg-blue">حفظ</button>
                                </div>
                                {{--                        <!-- Modal -->--}}
                                {{--                        <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
                                {{--                            <div class="modal-dialog">--}}
                                {{--                                <div class="modal-content">--}}
                                {{--                                    <div class="modal-header">--}}
                                {{--                                        <h5 class="modal-title" id="exampleModalLabel">إنشاء شكوى جديد<span class="red">*</span></h5>--}}
                                {{--                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
                                {{--                                    </div>--}}
                                {{--                                    <div class="modal-body">--}}
                                {{--                                        <div class="col-12" style="margin-bottom: 0">--}}
                                {{--                                            <p style="font-size:0.9em;text-align:start;margin-right: 1em">أدخل إسم الشكوى الجديد</p>--}}
                                {{--                                        </div>--}}
                                {{--                                        <div class="col-12 p-0-1">--}}
                                {{--                                            <div class="form-floating">--}}
                                {{--                                                <input id="new-claim-type" type="text"--}}
                                {{--                                                       class="form-control otp_input"--}}
                                {{--                                                       placeholder="اسم الشكوى">--}}
                                {{--                                                <label for="floatingInputGrid">الشكوى</label>--}}
                                {{--                                            </div>--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}
                                {{--                                    <div class="modal-footer">--}}
                                {{--                                        <button type="button" class="btn btn-primary bg-blue" id="option-but">حفظ</button>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                {{--                            </div>--}}
                                {{--                        </div>--}}
                            </div>
                        </div>
                    </form>
                @endif
                @if(auth()->user()->role == 0)
                    @if($claims->status == "معلقة")
                        <form id="result-form" action="{{route('admin-view', ['claims' => $claims->id])}}"
                              method="post">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 p-0-1">
                                    <div class="form-floating">
                                        <select name="status"
                                                class="status form-select @error('status') is-invalid @enderror"
                                                id="floatingSelectGrid" aria-label="Floating label select example">
                                            <option value="قيد المعالجة">قيد المعالجة</option>
                                            <option value="تمت المعالجة ويوجد مخالفة تم تنظيم ضبط">تمت المعالجة ويوجد
                                                مخالفة تم تنظيم ضبط
                                            </option>
                                            <option value="تمت المعالجة ولا يوجد مخالفة تم إعداد تقرير دورية">تمت
                                                المعالجة ولا يوجد مخالفة تم إعداد تقرير دورية
                                            </option>
                                        </select>
                                        <label for="floatingSelectGrid">حالة الشكوى</label>
                                        @error('status')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-floating form-claim">
                                            <select name="claim_type"
                                                    class="claims-types form-input claim_type_input form-select @error('claim_type') is-invalid @enderror"
                                                    id="floatingSelectGrid" aria-label="Floating label select example"
                                                    style="margin-top: 0" required>
                                                <option disabled selected value> -- اختر نوع الشكوى --</option>
                                                @foreach($options as $option)
                                                    <option value="{{$option->id}}">{{$option->name}}</option>
                                                @endforeach
                                            </select>
                                            <label class="selcet2-claims-type" for="floatingSelectGrid"> نوع الشكوى<span
                                                    class="red">*</span></label>
                                            @error('claim_type')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div id="claim_note_div" class="form-floating d-none">
                            <textarea required name="claim_note" class="form-control " placeholder="الملاحظة"
                                      id="claim_note" style="height: 70px"></textarea>
                                            <label for="floatingTextarea2">نتيجة المعالجة</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="file-upload" class="custom-file-upload">
                                            <i class="fa fa-cloud-upload"></i> المرفقات
                                        </label>
                                        <input class="url_input" name="attachment" id="file-upload" type="file"/>
                                    </div>
                                    <div class="col-lg-12" style="display:flex;justify-content: end">
                                        <button id="modal-button" type="button"
                                                class="btn btn-primary claim-btn bg-blue">حفظ
                                        </button>
                                    </div>
                                    <div class="modal fade" id="exampleModal" tabindex="-1"
                                         aria-labelledby="exampleModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">هل أنت متأكد؟</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p style="margin:1em 0">يرجى اخذ العلم أنه لا يمكنك تغير حالة الشكوى بعد حفظ
                                        الطلب</p>
                                </div>
                                <div class="modal-footer" style="display: flex;justify-content: center">
                                    <button type="button" class="btn btn-primary claim-btn bg-green"
                                            data-bs-dismiss="modal" aria-label="Close" style="margin: 0.5em 1em ;">
                                        الرجوع
                                    </button>
                                    <button type="submit" class="btn btn-primary claim-btn bg-blue"
                                            style="margin: 0.5em 1em ;">حفظ
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                            </div>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $('.status').change(function () {
                if ($('.status').val() === 'قيد المعالجة') {
                    $('#claim_note_div').addClass('d-none')
                } else {
                    $('#claim_note_div').removeClass('d-none')
                }
            })
            $('.claims-types').select2({
                dropdownCssClass: "claim_type_input",
                placeholder: 'إختر نوع الشكوى',
                language: {
                    noResults: () => {
                        return $('<button>').addClass('btn btn-primary bg-blue').attr('onclick', 'addOption()').attr('type', 'button').html('إضافة');
                    },
                },
                escapeMarkup: (markup) => {
                    return markup;
                },
            });
            let claim_type_input = $('.claim_type_input .select2-search .select2-search__field');

            window.addOption = function () {
                console.log(claim_type_input.val())
                $.ajax({
                    url: "/admin/admin-view/addOption",
                    method: "POST",
                    dataType: "json",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'name': $('.claims-types').data("select2").dropdown.$search.val()
                    },
                    success: function (response) {
                        console.log(response);
                        if (response.success) {
                            let option = new Option(response.option.name, response.option.id, true, true)
                            $('.claims-types').append($(option)).trigger('change');
                            // $('#exampleModal2').modal('hide');
                            $('.claims-types').select2('close')
                        }
                    }
                })
                // $('#option-but').on('click',function
                // });
            }

            $('#file-upload').change(function () {
                var i = $(this).prev('label').clone();
                var file = $('#file-upload')[0].files[0].name;
                $(this).prev('label').text(file);
            });
            $("#modal-button").on('click', function (e) {
                let note = $('#claim_note').val();
                console.log(note)
                if (note === '') {
                    let result_validator = $("#result-form").validate()
                    result_validator.showErrors({})
                } else {
                    $('#exampleModal').modal('show');
                }
            })
            $("#result-form").on('submit', function (e) {
                e.preventDefault();
                let result_validator = $("#result-form").validate()
                if (result_validator) {
                    let id = {{$claims->id}};
                    var formElement = document.getElementById("result-form");
                    var formData = new FormData(formElement);
                    $.ajax({
                        url: id,
                        type: 'POST',
                        datatype: 'json',
                        enctype: 'multipart/form-data',
                        contentType: false,
                        processData: false,
                        cache: false,
                        data: formData,
                        success: function (response) {
                            if (response.success) {
                                window.location.href = response.redirect;
                            }
                        }
                    })
                    result_validator.showErrors({})
                }
            })
        })
    </script>
@endpush
