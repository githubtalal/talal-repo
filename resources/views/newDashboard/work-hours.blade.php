@extends('newDashboard.layouts.master')

@section('content')
@php 

$counter = 0; 

    if (isset($days['Sat']))
    {
        $satOpen = $days['Sat']['open'];
        $satClose = $days['Sat']['close'];
    }
    
    if(isset($days['Sun']))
    {
        $sunOpen = $days['Sun']['open'];
        $sunClose = $days['Sun']['close'];
    }

    if(isset($days['Mon']))
    {
        $monOpen = $days['Mon']['open'];
        $monClose = $days['Mon']['close'];
    }

    if(isset($days['Tue']))
    {
        $tueOpen = $days['Tue']['open'];
        $tueClose = $days['Tue']['close'];
    }

    if(isset($days['Wed']))
    {
        $wedOpen = $days['Wed']['open'];
        $wedClose = $days['Wed']['close'];
    }

    if(isset($days['Thu']))
    {
        $thuOpen = $days['Thu']['open'];
        $thuClose = $days['Thu']['close'];
    }

    if(isset($days['Fri']))
    {
        $friOpen = $days['Fri']['open'];
        $friClose = $days['Fri']['close'];
    }

    /*
    if (isset($days->Sat))
    {
        $satOpen = $days->Sat->open;
        $satClose = $days->Sat->close;
    }
    
    if(isset($days->Sun))
    {
        $sunOpen = $days->Sun->open;
        $sunClose = $days->Sun->close;
    }

    if(isset($days->Mon))
    {
        $monOpen = $days->Mon->open;
        $monClose = $days->Mon->close;
    }

    if(isset($days->Tue))
    {
        $tueOpen = $days->Tue->open;
        $tueClose = $days->Tue->close;
    }

    if(isset($day->Wed))
    {
        $wedOpen = $days->Wed->open;
        $wedClose = $days->Wed->close;
    }

    if(isset($days->Thu))
    {
        $thuOpen = $days->Thu->open;
        $thuClose = $days->Thu->close;
    }

    if(isset($days->Fri))
    {
        $friOpen = $days->Fri->open;
        $friClose = $days->Fri->close;
    }*/

@endphp
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div class="container-xxl">
            <div class="card mb-5 mb-xl-10">

                <!--begin::Card header-->
                <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                     data-bs-target="#kt_account_profile_details" aria-expanded="true"
                     aria-controls="kt_account_profile_details">
                    <!--begin::Card title-->
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">مواعيد العمل</h3>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Content-->
                <div id="kt_account_settings_profile_details" class="collapse show">
                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">

                        <div id="accordion">
                            <form action="{{ route('storeWorkHours') }}" method="POST" id="form" class="form"
                                  enctype="multipart/form-data">
                                @csrf

                                    <div class="col-lg-12 mb-6 heading d-flex justify-content-between">
                                        <div class="col-lg-6 d-flex align-items-center">
                                            <!--begin::Label-->
                                            <label class="col-form-label fw-bold fs-2">
                                                <strong>Sat</strong>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <div
                                                class="form-check form-switch form-switch-sm form-check-custom form-check-solid mt-2">
                                                <input class="form-check-input check" type="checkbox"
                                                        style="width: 40px; height:24px;" id="0"  />
                                            </div>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                         
                                    <div class="mb-5 fv-row d-none" id="timeRange_0">  
                                        <!--begin::Label-->                                                                          
                                        <label
                                            class="form-label">Open</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="time"
                                                    name="open_Sat"
                                                    class="form-control mb-2 data" id="data_0"
                                                    value="{!! $satOpen ?? '' !!}" style="width: 45%; height: 50px"/>
                                        <!--end::Input-->
                                        <!--begin::Label-->                                                                                
                                        <label
                                            class="form-label">Close</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="time"
                                                    name="close_Sat"
                                                    class="form-control mb-2 data" id="data_0"
                                                    value="{!! $satClose ?? '' !!}" style="width: 45%; height: 50px"/>
                                        <!--end::Input-->
                                    </div>         

                                    <div class="col-lg-12 mb-6 heading d-flex justify-content-between">
                                        <div class="col-lg-6 d-flex align-items-center">
                                            <!--begin::Label-->
                                            <label class="col-form-label fw-bold fs-2">
                                                <strong>Sun</strong>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <div
                                                class="form-check form-switch form-switch-sm form-check-custom form-check-solid mt-2">
                                                <input class="form-check-input check" type="checkbox"
                                                        style="width: 40px; height:24px;" id="1"  />
                                            </div>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                         
                                    <div class="mb-5 fv-row d-none" id="timeRange_1">  
                                        <!--begin::Label-->                                                                          
                                        <label
                                            class="form-label">Open</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="time"
                                                    name="open_Sun"
                                                    class="form-control mb-2 data" id="data_1"
                                                    value="{!! $sunOpen ?? '' !!}" style="width: 45%; height: 50px"/>
                                        <!--end::Input-->
                                        <!--begin::Label-->                                                                                
                                        <label
                                            class="form-label">Close</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="time"
                                                    name="close_Sun"
                                                    class="form-control mb-2 data" id="data_1"
                                                    value="{!! $sunClose ?? '' !!}" style="width: 45%; height: 50px"/>
                                        <!--end::Input-->
                                    </div>

                                    <div class="col-lg-12 mb-6 heading d-flex justify-content-between">
                                        <div class="col-lg-6 d-flex align-items-center">
                                            <!--begin::Label-->
                                            <label class="col-form-label fw-bold fs-2">
                                                <strong>Mon</strong>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <div
                                                class="form-check form-switch form-switch-sm form-check-custom form-check-solid mt-2">
                                                <input class="form-check-input check" type="checkbox"
                                                        style="width: 40px; height:24px;" id="2"  />
                                            </div>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                         
                                    <div class="mb-5 fv-row d-none" id="timeRange_2">  
                                        <!--begin::Label-->                                                                          
                                        <label
                                            class="form-label">Open</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="time"
                                                    name="open_Mon"
                                                    class="form-control mb-2 data" id="data_2"
                                                    value="{!! $monOpen ?? '' !!}" style="width: 45%; height: 50px"/>
                                        <!--end::Input-->
                                        <!--begin::Label-->                                                                                
                                        <label
                                            class="form-label">Close</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="time"
                                                    name="close_Mon"
                                                    class="form-control mb-2 data" id="data_2"
                                                    value="{!! $monClose ?? '' !!}" style="width: 45%; height: 50px"/>
                                        <!--end::Input-->
                                    </div>

                                    <div class="col-lg-12 mb-6 heading d-flex justify-content-between">
                                        <div class="col-lg-6 d-flex align-items-center">
                                            <!--begin::Label-->
                                            <label class="col-form-label fw-bold fs-2">
                                                <strong>Tue</strong>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <div
                                                class="form-check form-switch form-switch-sm form-check-custom form-check-solid mt-2">
                                                <input class="form-check-input check" type="checkbox"
                                                        style="width: 40px; height:24px;" id="3"  />
                                            </div>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                         
                                    <div class="mb-5 fv-row d-none" id="timeRange_3">  
                                        <!--begin::Label-->                                                                          
                                        <label
                                            class="form-label">Open</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="time"
                                                    name="open_Tue"
                                                    class="form-control mb-2 data" id="data_3"
                                                    value="{!! $tueOpen ?? '' !!}" style="width: 45%; height: 50px"/>
                                        <!--end::Input-->
                                        <!--begin::Label-->                                                                                
                                        <label
                                            class="form-label">Close</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="time"
                                                    name="close_Tue"
                                                    class="form-control mb-2 data" id="data_3"
                                                    value="{!! $tueClose ?? '' !!}" style="width: 45%; height: 50px"/>
                                        <!--end::Input-->
                                    </div>

                                    <div class="col-lg-12 mb-6 heading d-flex justify-content-between">
                                        <div class="col-lg-6 d-flex align-items-center">
                                            <!--begin::Label-->
                                            <label class="col-form-label fw-bold fs-2">
                                                <strong>Wed</strong>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <div
                                                class="form-check form-switch form-switch-sm form-check-custom form-check-solid mt-2">
                                                <input class="form-check-input check" type="checkbox"
                                                        style="width: 40px; height:24px;" id="4"  />
                                            </div>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                         
                                    <div class="mb-5 fv-row d-none" id="timeRange_4">  
                                        <!--begin::Label-->                                                                          
                                        <label
                                            class="form-label">Open</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="time"
                                                    name="open_Wed"
                                                    class="form-control mb-2 data" id="data_4"
                                                    value="{!! $wedOpen ?? '' !!}" style="width: 45%; height: 50px"/>
                                        <!--end::Input-->
                                        <!--begin::Label-->                                                                                
                                        <label
                                            class="form-label">Close</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="time"
                                                    name="close_Wed"
                                                    class="form-control mb-2 data" id="data_4"
                                                    value="{!! $wedClose ?? '' !!}" style="width: 45%; height: 50px"/>
                                        <!--end::Input-->
                                    </div>

                                    <div class="col-lg-12 mb-6 heading d-flex justify-content-between">
                                        <div class="col-lg-6 d-flex align-items-center">
                                            <!--begin::Label-->
                                            <label class="col-form-label fw-bold fs-2">
                                                <strong>Thu</strong>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <div
                                                class="form-check form-switch form-switch-sm form-check-custom form-check-solid mt-2">
                                                <input class="form-check-input check" type="checkbox"
                                                        style="width: 40px; height:24px;" id="5"  />
                                            </div>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                         
                                    <div class="mb-5 fv-row d-none" id="timeRange_5">  
                                        <!--begin::Label-->                                                                          
                                        <label
                                            class="form-label">Open</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="time"
                                                    name="open_Thu"
                                                    class="form-control mb-2 data" id="data_5"
                                                    value="{!! $thuOpen ?? '' !!}" style="width: 45%; height: 50px"/>
                                        <!--end::Input-->
                                        <!--begin::Label-->                                                                                
                                        <label
                                            class="form-label">Close</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="time"
                                                    name="close_Thu"
                                                    class="form-control mb-2 data" id="data_5"
                                                    value="{!! $thuClose ?? '' !!}" style="width: 45%; height: 50px"/>
                                        <!--end::Input-->
                                    </div>

                                    <div class="col-lg-12 mb-6 heading d-flex justify-content-between">
                                        <div class="col-lg-6 d-flex align-items-center">
                                            <!--begin::Label-->
                                            <label class="col-form-label fw-bold fs-2">
                                                <strong>Fri</strong>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <div
                                                class="form-check form-switch form-switch-sm form-check-custom form-check-solid mt-2">
                                                <input class="form-check-input check" type="checkbox"
                                                        style="width: 40px; height:24px;" id="6"  />
                                            </div>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                         
                                    <div class="mb-5 fv-row d-none" id="timeRange_6">  
                                        <!--begin::Label-->                                                                          
                                        <label
                                            class="form-label">Open</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="time"
                                                    name="open_Fri"
                                                    class="form-control mb-2 data" id="data_6"
                                                    value="{!! $friOpen ?? '' !!}" style="width: 45%; height: 50px"/>
                                        <!--end::Input-->
                                        <!--begin::Label-->                                                                                
                                        <label
                                            class="form-label">Close</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="time"
                                                    name="close_Fri"
                                                    class="form-control mb-2 data" id="data_6"
                                                    value="{!! $friClose ?? '' !!}" style="width: 45%; height: 50px"/>
                                        <!--end::Input-->
                                    </div>
                                <!--Save Button-->
                                <button type='submit' id="submit" class='btn btn-primary'>
                                    <span class='indicator-label'>{{ __('app.button.save') }}</span>
                                </button>
                            </form>
                            <!--end::Form-->
                        </div>
                    </div>
                    <!--end::Card body-->

                </div>
                <!--end::Content-->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {

            timeRanges = document.getElementsByClassName('data');
            for (i = 0; i < timeRanges.length; i++)
            {
                id = timeRanges[i].id.split("_")[1];
                value = timeRanges[i].value;
                if (value != '')
                    {
                        document.getElementById(id).checked = true;
                        $('#timeRange_' + id).removeClass('d-none');
                    }
                else
                    document.getElementById(id).checked = false;
            }   

            checkboxes = document.querySelectorAll("input[type=checkbox]");
            document.getElementById('submit').addEventListener('click', function(event){
                //event.preventDefault();
                for (i = 0; i < checkboxes.length; i++)
                {
                    if (!checkboxes[i].checked)
                    {
                        id = checkboxes[i].id;
                        $childs = $('#timeRange_' + id).children();
                        for(j = 0; j < $childs.length; j++)
                        {        
                                if($childs[j].type == 'time')
                                    $childs[j].value = '';
                        }
                    }
                }

                document.getElementById('form').submit();
            });

            for(i = 0; i < checkboxes.length; i++)
            {
                checkboxes[i].addEventListener('click', Hide_show);
            }
            
            function Hide_show(e)
            {
                id = e.target.id;
                if (e.target.checked)
                    $('#timeRange_' + id).removeClass('d-none');
                else
                    $('#timeRange_' + id).addClass('d-none');                   
            }

        });


    </script>
@endpush
