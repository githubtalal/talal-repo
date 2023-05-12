<!DOCTYPE html>
<html lang="en" dir="rtl">

<!--begin::Head-->

<head>
    <base href="">
    <title>
        eCart - إي كارت
    </title>
    <meta charset="utf-8" />
    <meta name="description"
        content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 94,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue &amp; Laravel versions. Grab your copy now and get life-time updates for free." />
    <meta name="keywords"
        content="Metronic, bootstrap, bootstrap 5, Angular, VueJs, React, Laravel, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title"
        content="Metronic - Bootstrap 5 HTML, VueJS, React, Angular &amp; Laravel Admin Dashboard Theme" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <meta property="og:url" content="https://keenthemes.com/metronic" />
    <meta property="og:site_name" content="Keenthemes | Metronic" />
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
    <link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Page Vendor Stylesheets(used by this page)-->
    <link href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
        type="text/css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
    <!--end::Page Vendor Stylesheets-->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.rtl.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" id="theme-styles">
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css"/>

    <style>
        body {
            font-family: 'Cairo', sans-serif !important;
        }

        .card-flush {
            height: 100%;
        }

        .mob {
            display: none !important;
        }

        @media (max-width: 1200px) {
            .mob-dash {
                width: 100%;
                right: 200px;
                position: relative;
            }

            .desk {
                display: none !important;
            }

            .mob {
                display: flex !important;
            }


        }
    </style>
    @stack('styles')
    <!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_body"
    class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed"
    style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">

    @include('newDashboard.layouts.root')

    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Global Javascript Bundle(used by all pages)-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Page Vendors Javascript(used by this page)-->
    <script src="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <!--end::Page Vendors Javascript-->
    <!--begin::Page Custom Javascript(used by this page)-->
    <script src="{{ asset('assets/js/widgets.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/custom/widgets.js') }}"></script>
    <script src="{{ asset('assets/js/custom/apps/chat/chat.js') }}"></script>
    <script src="{{ asset('assets/js/custom/apps/chat/chat.js') }}"></script>
    <script src="{{ asset('assets/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
    <script src="{{ asset('assets/js/custom/utilities/modals/create-app.js') }}"></script>
    <script src="{{ asset('assets/js/custom/utilities/modals/users-search.js') }}"></script>
    <script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v14.0&appId={{ config('hooks.facebook_app_id') }}&autoLogAppEvents=1"
        nonce="fXWikoZ0"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="{{ asset('notifications/notification.js') }}"></script>

    @stack('scripts')
    @stack('script')
    @stack('FAQScripts')
    <!--end::Page Custom Javascript-->
    <!--end::Javascript-->
    @if (session()->has('success_message'))
        <script>
            Swal.fire({
                title: '',
                text: '{{ session()->get('success_message') }}',
                icon: 'success',
            })
        </script>
    @endif

    @if (session()->has('error_message'))
        <script>
            Swal.fire({
                title: 'خطأ !',
                text: `{{ session()->get('error_message') }}`,
                icon: 'error',
            })
        </script>
    @endif

    @if (session()->has('warning_message'))
        <script>
            Swal.fire({
                title: 'تنبيه !',
                text: `{{ session()->get('warning_message') }}`,
                icon: 'warning',
            })
        </script>
    @endif

    <script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js"></script>


    <script>

        var user_id = {{ auth()->user()->id }} ;
        var interest = 'App.User.' + user_id;

        if('serviceWorker' in navigator){
            navigator.serviceWorker.register('/service-worker.js');
        }

        window.navigator.serviceWorker.ready.then(serviceWorkerRegistration => {
        const beamsClient = new PusherPushNotifications.Client({
        instanceId: '{{ env("PUSHER_BEAMS_INSTANCE_ID") }}',
        serviceWorkerRegistration: serviceWorkerRegistration,
        });
        beamsClient
        .start()
        .then((beamsClient) => beamsClient.getDeviceId())
        // .then((deviceId) =>
        //     console.log("Successfully registered with Beams. Device ID:", deviceId)
        // )
        .then(() => beamsClient.addDeviceInterest(interest))
        .then(() => beamsClient.getDeviceInterests())
        //.then((interests) => console.log("Current interests:", interests))
        .catch(console.error);

        }
    );

    const channel = new BroadcastChannel('sw-messages');
    channel.addEventListener('message', event => {

        //console.log(event.data);


        // push notification to the system
        let permission = Notification.permission;

        var body = event.data.notification.body;
        var icon = event.data.notification.icon;
        var url =  `{{ url('/') }}` + '/dashboard/notifications/show_notification/' + event.data.data.id   ;

        //console.log(url);

        if(!window.closed){

        toastr.info(body,'تنبيه', {timeOut: 0,extendedTimeOut: 0,closeButton: true,closeHtml: '<button><i class="mb-1 icon-off"></i></button>' ,onclick : function(){ window.location = url; }})

        }



        var element =   ' <a href="'+url+'">'+
                            '<div class="d-flex flex-stack py-4">'+
                                '<div class="d-flex align-items-center">'+
                                    '<div class="symbol symbol-35px me-4">'+
                                        '<span class="symbol-label bg-light-primary">'+
                                            '<i class="las la-bell fs-1 text-primary"></i>'+
                                        '</span>'+
                                    '</div>'+
                                    '<div class="mb-0 me-2">'+
                                        '<div class="text-gray-600 fs-5">'+body+'</div>'+
                                    '</div>'+
                                '</div>'+
                                '<span class="badge badge-light fs-8">الآن</span>'+
                            '</div>'+
                            '</a>';

        document.querySelector("#notification_list").insertAdjacentHTML('afterbegin',element);
        $("#bullet").removeClass("d-none");


        

    });


 
    </script>


</body>
<!--end::Body-->

</html>
