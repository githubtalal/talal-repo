@extends('newDashboard.layouts.master')
@section('content')
    @php $counter = 0; @endphp

    <!--begin::Post-->
    <div class="content d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div class="toolbar">
            <!--begin::Page title-->
            <div class="container-fluid">
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                    {{ __('app.FAQ.FAQ') }}
                </h1>
            </div>
        </div>
        <!--begin::Container-->
        <div class="container-xxl" style="margin-bottom: 40px">
            <!--begin::FAQ card-->
            <div class="card">
                <!--begin::Body-->
                <div class="card-body p-lg-15">
                    <!--begin::Intro-->
                    <div class="d-flex flex-stack justify-content-end">
                        <!--begin::Title-->
                        <button type="button" class="btn btn-primary add">{{ __('app.FAQ.add_question') }}</button>
                        <!--end::Title-->
                    </div>
                    <!--end::Intro-->
                    <!--begin::Row-->
                    <div class="row mb-12">
                        <!--begin::Col-->
                        <div class="col-md-6 pe-md-10 mb-10 mb-md-0">
                            <!--begin::Form-->
                            <form action="{{ route('store_question') }}" class="form mb-15 Qform" method="post"
                                id="">
                                @csrf
                                <div class="allElem">
                                    @foreach ($QObjects as $obj)
                                        @php $counter++; @endphp

                                        <div class="elem">
                                            <h3 class="fw-bolder text-dark mb-9">
                                                {{ __('app.FAQ.question') . ' ' . $counter . ' :' }}</h3>                                           

                                            <!-- add talal -->
                                            <div class="d-flex flex-column mb-5 fv-row">
                                                <div class="d-flex flex-column mb-5 fv-row">
                                                    <label for="Question" class="required form-label">{{ __('app.FAQ.question') }}</label>
                                                    <div id = "Question_{{ $counter }}" > {!! $obj->question !!}  </div>
                                                    <input type = "hidden" name = "questions[]" id = "question-{{ $counter }}" />
                                                </div> 
                                                <div class="d-flex flex-column mb-5 fv-row">
                                                    <label for="Answer" class="required form-label">{{ __('app.FAQ.answer') }}</label>
                                                    <div id = "Answer_{{ $counter }}"> {!! $obj->answer !!} </div> 
                                                    <input type = "hidden" name = "answers[]" id = "answer-{{ $counter }}" />
                                                </div>
                                            </div>
                                            <!-- end talal -->

                                            <!-- Remove button -->
                                            <div class="d-flex justify-content-end buttons">
                                                <button type="button"
                                                    class="btn btn-light me-5 remove">{{ __('app.FAQ.remove') }}</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Save button -->
                                <div class="save_button" style="display: {{ $QObjects ? '' : 'none' }}">
                                    <x-inputs.save_button></x-inputs.save_button>
                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::FAQ card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
@endsection

@push('FAQScripts')
<!-- Main Quill library -->
<script src="//cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
<link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.core.css">

    <script>
        $(document).ready(function() {


            var id = {{ $counter }}

            // add talal
            var i = 1;
            while (i <= id)
            {
                var quill = new Quill('#Question_'+i, {
                    modules: {
                        toolbar:[ 
                        ['bold', 'italic', 'underline', 'link']],
                         
                    },
                    theme: 'snow'  // or 'bubble'
                    });
                
                
                var quill = new Quill('#Answer_'+i, {
                    modules: {
                        toolbar: [
                        ['bold', 'italic', 'underline', 'link']],
                        
                    },
                    theme: 'snow'  // or 'bubble'
                    });

                i++;
            }

            // end talal

            $(document).on('click', '.add', function() {

                id++;

                var elem =
                    '<div class="elem">' +
                    '<h1 class="fw-bolder text-dark mb-9">{{ __('app.FAQ.question') }} ' + id + ':</h1>';

                // Question input
                elem += '<div class="d-flex flex-column mb-5 fv-row">';

                elem +=
                    '<label for="Question" class="required form-label">{{ __('app.FAQ.question') }}</label>';

                 /*elem +=
                    '<input type="text" name="questions[]" required class="form-control mb-2" id="Question" placeholder="{{ __('app.FAQ.question') }}"/>' +
                    '</div>';*/

                // add talal                
                var currentQ = "Question_" + id;
                elem += '<div id = ' + currentQ + ' ></div>';
                elem += '<input type = "hidden" name = "questions[]" id = "question-' + id + '" />';
                elem += '</div>';
                // end talal

                // Answer input
                elem += '<div class="d-flex flex-column mb-5 fv-row">';

                elem +=
                    '<label for="Answer" class="required form-label">{{ __('app.FAQ.answer') }}</label>';
                /*elem +=
                    '<textarea class="form-control mb-2" id="Answer" rows="6" name="answers[]" required placeholder="{{ __('app.FAQ.answer') }}" value=""></textarea>' +
                    '</div>';*/
                
                // add talal          
                var currentA = "Answer_" + id;
                elem += '<div id = ' + currentA + ' ></div>'; 
                elem += '<input type = "hidden" name = "answers[]" id = "answer-' + id + '" />';
                elem += '</div>';
                // end talal

                // Remove button
                elem += '<div class="d-flex justify-content-end buttons">' +
                    '<button type="button" style="margin-top:10px;" class="btn btn-light me-5 remove">{{ __('app.FAQ.remove') }}</button></div>' +
                    '</div>';

                $('.allElem').append(elem);

                var quill = new Quill('#Question_'+id, {
                    modules: {
                        toolbar: 
                        ['bold', 'italic', 'underline', 'link'],
                    },
                    theme: 'snow'  // or 'bubble'
                    });

                var quill = new Quill('#Answer_'+id, {
                    modules: {
                        toolbar:    
                        ['bold', 'italic', 'underline', 'link'],
                    },
                    theme: 'snow'  // or 'bubble'
                    });

                $('.save_button').show();

                document.getElementsByClassName('save_button')[0].addEventListener('click', function(event){

                    //event.preventDefault();
                    for (var i = 1; i <= id; i++)
                    {
                        var div = document.getElementById('Question_' + i);
                        if (div != null)
                        {
                            var divChild = div.firstChild;
                            var p = divChild.firstChild;
                            $('#question-' + i).val(p.innerHTML);
                        } else
                        {
                            continue;
                        }
                    
                        div = document.getElementById('Answer_' + i);
                        if(div != null)
                        {
                            divChild = div.firstChild;
                            p = divChild.firstChild;
                            $('#answer-' + i).val(p.innerHTML);
                        } else
                        {
                            continue;
                        }
                    }                    
                    document.getElementsByClassName('Qform')[0].submit();
                });

            });

            document.getElementsByClassName('save_button')[0].addEventListener('click', function(event){
                //event.preventDefault();
                for (var i = 1; i <= id; i++)
                {
                    var div = document.getElementById('Question_' + i);
                    if (div != null)
                    {
                        var divChild = div.firstChild;
                        var p = divChild.firstChild;
                        $('#question-' + i).val(p.innerHTML);
                    } else
                    {
                        continue;
                    }
                
                    div = document.getElementById('Answer_' + i);
                    if(div != null)
                    {
                        divChild = div.firstChild;
                        p = divChild.firstChild;
                        $('#answer-' + i).val(p.innerHTML);
                    } else
                    {
                        continue;
                    }
                }
                document.getElementsByClassName('Qform')[0].submit();                    
            });

            $(document).on('click', '.remove', function() {
                $(this).closest('.elem').remove();

                if ($('.allElem').children().length = 0) {
                    $('.save_button').hide();
                }
            });

            let Link = Quill.import('formats/link');
            Link.sanitize = function(value) {
            return 'customsanitizedvalue';
            }
        });

    </script>
@endpush
