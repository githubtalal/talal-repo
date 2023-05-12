@extends('newDashboard.layouts.master')
@section('content')
    <!--begin::Basic info-->
    <div class="content d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div class="toolbar">
            <!--begin::Page title-->
            <div class="container-fluid">
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                    {{ __('app.sidebar.button_settings') }}
                </h1>
            </div>
        </div>
        <!--begin::Container-->
        <div class="container-xxl">
            <div class="card mb-5 mb-xl-10">
                <div class="card-body border-top p-9">
                    <form id="validatedForm" action="{{ route('buttonSettings.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf

                        @foreach ($messages as $message)
                            @php 
                                $isVaraibles = false;
                                $values = explode(" ", $message->value);
                                for ($i = 0; $i < count($values); $i++)
                                {
                                    if (preg_match('/[A-Z]/i', $values[$i]))
                                    { 
                                        $isVaraibles = true;
                                        //$values[$i] = null;
                                        
                                    } else
                                    {
                                            continue;
                                    }
                                }
                                $newValue = implode(" ", $values);
                            @endphp
                            @if (!$isVaraibles)
                                <div class="row d-flex align-items-center mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 col-form-label fw-bold fs-4" id="">
                                        {{ $message->value }}
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-6 fv-row">
                                        <input type="text" name="{{ str_replace('.', '@', $message->key) }}"
                                            class="form-control form-control-lg messageText"
                                            placeholder="" value="{{ $message->value }}"/>
                                        <!--    
                                        <textarea name="{{ $message->id }}"
                                            class="form-control form-control-lg"
                                            value="{{ $message->value }}">
                                            {{ $message->value }} </textarea> -->
                                    </div>
                                    <!--end::Col-->
                                </div>
                            @endif
                        @endforeach
                        <div class="col-lg-3">
                            <button type='submit' id="submit" class='btn btn-primary'>
                                <span class='indicator-label'>{{ __('app.button.save') }}</span>
                            </button>
                        </div>
                    </form>
                    {{-- $messages->links() --}}
                </div>
            </div>
        </div>
    </div>
@endsection
