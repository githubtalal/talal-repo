@extends('dashboard.layouts.master')

@section('content')
    <div class="dashboard">
        <div class="card admin-card">
            <div class="row">
                <div class="card-body">
                    <h3>
                        إعدادات بوت مسنجر
                    </h3>
                    <hr>
                    <form action="{{ route('bot.telegram.update') }}" method="POST" class="login"
                          enctype="multipart/form-data">
                    @csrf
                    <!-- to error: add class "has-danger" -->
                        <div class="row">
                            <div class="col-12 p-0-1">
                                <div class="form-floating">
                                    <input value="{{ old('token') ?? $bot->token ?? '' }}" name="token" type="text"
                                           class="form-control @error('token') is-invalid @enderror"
                                           id="floatingInputGrid" placeholder="Telegram token">
                                    <label for="floatingInputGrid">Telegram token<span class="red">*</span></label>
                                    @error('token')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary submit-btn btn-block bg-blue">Save</button>
                    </form>
                </div>
            </div>

            @if ($bot->token ?? null)
                <div class="card-body">
                    <h3>
                        رسائل ترويجية
                    </h3>
                    <hr>
                    <form action="{{ route('bot.telegram.broadcast') }}" method="POST" class="login"
                          enctype="multipart/form-data">
                    @csrf
                    <!-- to error: add class "has-danger" -->
                        <div class="row">
                            <div class="col-12 p-0-1">
                                <div class="form-floating">
                                    <div class="form-floating">
                                        <input name="channel_id" type="text"
                                               class="form-control @error('token') is-invalid @enderror"
                                               id="floatingInputGrid" placeholder="Channel username">
                                        <label for="floatingInputGrid">Channel username</label>
                                        @error('channel_id')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <select class="form-control" name="product_id" id="product_select">
                                        @foreach($bot->store->products as $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="use_custom_message"
                                               value="1"
                                               id="use_custom_message">
                                        <label class="form-check-label" for="use_custom_message">
                                            إنشاء رسالة مخصصة
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div  id="custom_message" class="col-12 d-none">
                                <div class="form-floating">
                                    <textarea class="form-control" name="message" placeholder="نص الرسالة" id="floatingTextarea2" style="height: 100px"></textarea>
                                    <label for="floatingTextarea2">نص الرسالة</label>
                                </div>

                                <div class="col-12 p-0-1" style="margin-top: 1.5em">
                                    <label for="Image" class="form-label">الصورة</label>
                                    <input class="form-control" type="file" name="image" id="formFile"
                                           onchange="preview(this)">
                                    {{--                            <input--}}
                                    {{--                                required--}}
                                    {{--                                class="form-control" name="image_url" id="file-upload" type="file"--}}
                                    {{--                                accept=".png,,.jpeg,.jpg"/>--}}
                                    @error('image_url')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12" style="margin-top: 1.5em;text-align: center">
                                    <img id="frame" src="" class="img-fluid"/>

                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary submit-btn btn-block bg-blue">Save</button>
                    </form>
                </div>
            @endif
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        $(document).ready(() => {
            $('#product_select').select2();
        });
        $('#use_custom_message').change(function () {
            if ($(this).is(':checked')) {
                $('#custom_message').removeClass('d-none');
            } else {
                $('#custom_message').addClass('d-none');
            }
        })
        function preview(event) {
            $('#frame').attr('src', URL.createObjectURL(event.files[0]));
        }

    </script>
@endpush
