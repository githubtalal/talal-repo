@extends('dashboard.layouts.master')

@section('content')
    <style>
        .pre-wrapper {
            position: relative;
        }

        .pre-wrapper pre {
            padding-top: 25px;
        }

        .pre-wrapper .copy-snippet {
            border-radius: 0;
            min-width: 55px;
            background: none repeat scroll 0 0 transparent;
            border: 1px solid #bbb;
            color: #26589F;
            font-family: 'HELEVETICA', sans-serif;
            font-size: 12px;
            font-weight: normal;
            line-height: 1.42rem;
            margin: 0;
            padding: 0px 5px;
            text-align: center;
            text-decoration: none;
            text-indent: 0;
            position: absolute;
            background: #ccc;
            top: 0;
            right: 0;
        }

        .pre-wrapper .copy-snippet:disabled {
            color: #555;
        }
    </style>
    <div class="dashboard">
        <div class="card admin-card">
            <div class="card-body">
                <h3>
                    الإعدادات
                </h3>
                <hr>
                <form action="{{ route('settings.update') }}" method="POST" class="login" enctype="multipart/form-data">
                    @csrf
                    <!-- to error: add class "has-danger" -->
                    <div class="row">
                        <div class="col-12">
                            <div class="row">


                                <div class="col-12 p-0-1">
                                    <div class="form-floating">
                                        <input name="name" type="text" value="{{ $store->name ?? '' }}"
                                            class="login-input form-control @error('name') is-invalid @enderror"
                                            id="floatingInputGrid" placeholder="btn name">
                                        <label for="floatingInputGrid">أسم المتجر</label>
                                        @error('name')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-12 p-0-1">
                                    <div class="form-floating">
                                        <input name="btn_text" type="text" value="{{ $store->button_text ?? '' }}"
                                            class="login-input form-control @error('btn_text') is-invalid @enderror"
                                            id="floatingInputGrid" placeholder="btn name">
                                        <label for="floatingInputGrid">نص الزر</label>
                                        @error('btn_text')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="mb-5">
                                    <label for="Image" class="form-label">شعار المتجر</label>
                                    <input class="form-control" type="file" name="logo" id="formFile"
                                        onchange="preview(this)">
                                </div>
                                <img id="frame"
                                    src="{{ $store->logo ? \Illuminate\Support\Facades\Storage::url($store->logo) : '' }}"
                                    class="img-fluid" />


                                {{-- <div class="col-12 p-0-1"> --}}
                                {{-- <div class="form-floating"> --}}
                                {{-- <input name="btn-style" type="email" --}}
                                {{--  --}}
                                {{-- class="login-input form-control @error('btn-style') is-invalid @enderror" --}}
                                {{-- id="floatingInputGrid" placeholder="btn-style"> --}}
                                {{-- <label for="floatingInputGrid">btn-style</label> --}}
                                {{-- @error('btn-style') --}}
                                {{-- <div class="alert alert-danger">{{ __("validation.email") }}</div> --}}
                                {{-- @enderror --}}
                                {{-- </div> --}}
                                {{-- </div> --}}

                            </div>

                        </div>
                        <hr>
                        <div class="col-12">
                            <div class="col-12 p-0-1">
                                <div class="form-floating">
                                    <input type="text" disabled value="{{ $store->token ?? '' }}"
                                        class="disabled login-input form-control" id="floatingInputGrid"
                                        placeholder="domain">
                                    <label for="floatingInputGrid">المفتاح</label>
                                </div>
                            </div>
                            <div class="col-12 p-0-1">
                                <label for="">انسخ الكود </label>
                                <div class="form-floating">
                                    <pre><code
                                        style="text-align: left;white-space: pre-line;"
                                        class="form-control"
                                        id="floatingInputGrid" placeholder="btn name">
                                      import('{{ asset('js/store.js') }}').then(() => {
                                                Sallaty.init({
                                                    token: "{{ $store->token }}"
                                              });
                                              }).catch(err => console.error('Error'));
                                </code></pre>
                                    {{-- <label for="floatingInputGrid">انسخ الكود</label> --}}
                                    @error('btn-text')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="btn-div">
                                <button type="submit" class="btn btn-primary btn-block bg-blue">
                                    حفظ
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function preview(event) {
            console.log(URL.createObjectURL(event.files[0]))
            $('#frame').attr('src', URL.createObjectURL(event.files[0]));
        }
        jQuery(document).ready(function($) {
            var copyid = 0;
            $('pre').each(function() {
                copyid++;
                $(this).attr('data-copyid', copyid).wrap('<div class="pre-wrapper"/>');
                $(this).parent().css('margin', $(this).css('margin'));
                $('<button class="copy-snippet">نسخ !</button>').insertAfter($(this)).data('copytarget',
                    copyid);
            });

            $('body').on('click', '.copy-snippet', function(ev) {
                ev.preventDefault();

                var $copyButton = $(this);

                $pre = $(document).find('pre[data-copyid=' + $copyButton.data('copytarget') + ']');
                if ($pre.length) {
                    var textArea = document.createElement("textarea");

                    // Place in top-left corner of screen regardless of scroll position.
                    textArea.style.position = 'fixed';
                    textArea.style.top = 0;
                    textArea.style.left = 0;

                    // Ensure it has a small width and height. Setting to 1px / 1em
                    // doesn't work as this gives a negative w/h on some browsers.
                    textArea.style.width = '2em';
                    textArea.style.height = '2em';

                    // We don't need padding, reducing the size if it does flash render.
                    textArea.style.padding = 0;

                    // Clean up any borders.
                    textArea.style.border = 'none';
                    textArea.style.outline = 'none';
                    textArea.style.boxShadow = 'none';

                    // Avoid flash of white box if rendered for any reason.
                    textArea.style.background = 'transparent';

                    //Set value to text to be copied
                    textArea.value = $pre.html();

                    document.body.appendChild(textArea);
                    textArea.select();

                    try {
                        document.execCommand('copy');
                        $copyButton.text('تم النسخ !').prop('disabled', true);;
                    } catch (err) {
                        $copyButton.text('FAILED: Could not copy').prop('disabled', true);;
                    }
                    setTimeout(function() {
                        $copyButton.text('نسخ').prop('disabled', false);;
                    }, 3000);
                }
            });
        });
    </script>
@endpush
