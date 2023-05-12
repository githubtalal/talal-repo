@extends('newDashboard.layouts.master')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div class="toolbar">
            <!--begin::Page title-->
            <div class="container-fluid">
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                    {{ __('app.messenger_bot.settings') }}
                </h1>
            </div>
        </div>
        <!--begin::Basic info-->
        <div class="card mb-5 mb-xl-10">

            <!--begin::Content-->
            <div id="" class="collapse show">

                <!--begin::Card body-->
                <div class="card-body border-top p-9">

                    <div class="fb-login-button" data-width="300" data-size="large" data-button-type="continue_with"
                        data-scope="public_profile,email,pages_show_list,pages_read_user_content,pages_messaging,instagram_basic,instagram_manage_messages,pages_manage_metadata"
                        data-onlogin="checkLoginStatus" data-layout="default" data-auto-logout-link="false"
                        data-use-continue-as="true"></div>

                </div>
                <!--end::Card body-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Basic info-->
    </div>
@endsection

@push('scripts')
    <script>
        function checkLoginStatus(response) {
            if (response.status === 'connected') {
                console.log(response);
                permissions = [
                    'public_profile',
                    'email',
                    'pages_show_list',
                    'pages_read_user_content',
                    'pages_messaging',
                    'instagram_basic',
                    'instagram_manage_messages',
                    'pages_manage_metadata',
                ];

                FB.api('/me/permissions', function(permissionsResponse) {
                    if (permissionsResponse && permissionsResponse.data && permissionsResponse.data.length) {
                        user_permissions = permissionsResponse.data;

                        op = true;
                        console.log(permissionsResponse, permissions)
                        // if (permissions.length == user_permissions.length) {
                        //     for (i = 0; i < permissions.length; i++) {
                        //         for (j = 0; j < user_permissions.length; j++) {
                        //             if ((permissions[i] == user_permissions[j].permission) &&
                        //                 user_permissions[j].status == 'granted')
                        //                 op = true;
                        //         }
                        //     }
                        // }
                        console.log(op);
                        if (op == false) {
                            console.log(1);
                            Swal.fire({
                                title: '',
                                text: '{{ __('app.responses_messages.error_message') }}',
                                icon: 'error',
                            });
                        } else {
                            let data = {
                                access_token: response.authResponse.accessToken,
                                expires_in: response.authResponse.expiresIn,
                                user_id: response.authResponse.userID,
                                _token: '{{ csrf_token() }}'
                            };

                            $.ajax({
                                url: '{{ route('bot.messenger.create') }}',
                                type: 'POST',
                                data: data,
                                success: function(response) {
                                    if (response.success) {
                                        window.location.href =
                                            '{{ route('bot.messenger.list_pages') }}'
                                    }
                                }
                            });
                        }
                    }
                });
            } else {
                Swal.fire({
                    title: '',
                    text: '{{ __('app.responses_messages.error_message') }}',
                    icon: 'error',
                });
            }
        }
    </script>
@endpush
