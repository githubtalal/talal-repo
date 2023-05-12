@extends('dashboard.layouts.master')

@section('content')
    <div class="dashboard">
        <div class="card admin-card">
            <div class="card-body">
                <h3>
                    إعدادات بوت مسنجر
                </h3>
                <hr>
                <form action="{{ route('settings.update') }}" method="POST" class="login" enctype="multipart/form-data">
                @csrf
                <!-- to error: add class "has-danger" -->
                    <div class="row" id="login-section">
                        <div class="fb-login-button"
                             data-width="300"
                             data-size="large"
                             data-button-type="continue_with"
                             data-scope="public_profile,email,pages_show_list,pages_read_user_content,pages_messaging,instagram_basic,instagram_manage_messages,pages_manage_metadata "
                             data-onlogin="checkLoginStatus"
                             data-layout="default"
                             data-auto-logout-link="false"
                             data-use-continue-as="true"></div>
                        {{--                        <fb:login-button--}}
                        {{--                            scope="public_profile,email,pages_manage_engagement,pages_manage_posts,pages_messaging,pages_messaging,pages_read_user_content"--}}
                        {{--                            onlogin="checkLoginState">--}}
                        {{--                        </fb:login-button>--}}
                    </div>
                    <div class="row d-none" id="pages-section">
                        <table id="pages-table">
                            <thead>
                            <tr>
                                اسم الصفحة
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>

                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <button type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>

        function checkLoginStatus(response) {
            if (response.status === 'connected') {
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
                    success: function (response) {
                        if (response.success)
                            window.location.href = '{{ route('bot.messenger.list_pages') }}'
                    }
                });
            }
        }
    </script>
@endpush
