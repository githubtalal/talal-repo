<?php
    $data = decrypt(request('ref'));
    $data = json_decode($data, true);
?>

@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-center align-items-center">
        <div class="card otp-card">
            <div class="card-body">
                <div class="row dir-rtl">
                    <form action="{{ $data['callback_url'] ?? route('register.call-back') }}" method="POST">
                        @foreach ($data ?? [] as $key => $input)
                            @if (is_array($input))
                                @foreach ($input as $key2 => $input2)
                                    <input type="hidden" name="{{ $key2 }}" value="{{ $input2 }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $input }}">
                            @endif
                        @endforeach
                        @csrf
                        <div class="col-12 d-flex justify-content-center">
                            <img style="width:30%" src="Baseet/images/SyriatelLogo.svg"/>
                        </div>
                        <div class="col-12">
                            <label class="w-100 otp-label">الرقم</label>
                            <p class="otp-p">
                                {{ $data['customer_gsm'] ?? '' }}
                            </p>
                        </div>
                        <div class="col-12">
                            <label class="otp-label">رمز التحقق</label><br>
                            <input class="otp-input" type="text" name="otp_code">
                        </div>
{{--                        <div class="col-12 d-flex justify-content-start">--}}
{{--                            <button type="button" onclick="openLoader()">إعادة إرسال</button>--}}
{{--                        </div>--}}
                        <div class="col-12">
                            <button class="w-100 otp-btn-1" type="submit">إتمام العملية</button>
                        </div>
                            <div class="col-12">
                                <button type="button" onclick="cancelPayment()" class="w-100 otp-btn-2">إلغاء</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="loader" class="loader-back d-none">
        <div class="row loader-pos">
            <div class="col-12 text-center">
                <div class="loader4"></div>
            </div>
        </div>
    </div>
    <script>
        function openLoader(){
            document.getElementById('loader').classList.remove('d-none');
            setTimeout(alert,5000);
            function alert(){
                document.getElementById('loader').classList.add('d-none')
                if(true){
                    Swal.fire({
                        title: '',
                        html: 'تمت إعادة إرسال الرمز بنجاح',
                        icon: 'success',
                    })
                } else {
                    Swal.fire({
                        title: '',
                        text: "حدث خطئ في عملة الإرسال الرجاء إعادة المحاولة لاحقا",
                        icon: 'error',
                    })
                }
            }
        }
    </script>

    <script>
        function cancelPayment() {
            window.location.href = `{{ route('payment_processor.redirect', ['payment_ref' => $data['payment_ref']]) }}`
        }
    </script>
@endsection
