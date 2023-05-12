@extends('dashboard.layouts.master')

@section('content')
    <div class="dashboard">
        <div class="container">
            <div class="card">
                <div class="card-body p-2-4">
                    <div class="row">
                        <form action="{{ route('bot.messenger.set_pages') }}" method="post">
                            @csrf
                            <div class="col-md-12 col-md-offset-1">

                                <div class="panel panel-default panel-table">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col col-xs-6">
                                                <h3 class="panel-title">الصفحات</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row" id="login-section">
                                            لإضافة صفحة جديدة الرجاء الضغط على زر تسجيل الدخول
                                            <div class="fb-login-button mt-1 mb-3"
                                                 data-width="300"
                                                 data-size="large"
                                                 data-button-type="continue_with"
                                                 data-scope="public_profile,email,pages_show_list,pages_read_user_content,pages_messaging"
                                                 data-onlogin="checkLoginStatus"
                                                 data-layout="default"
                                                 data-auto-logout-link="false"
                                                 data-use-continue-as="true"></div>
                                        </div>
                                        <table class="table table-striped table-bordered table-list">
                                            <thead>
                                            <tr>
                                                <th scope="col"></th>
                                                <th scope="col">أسم الصفحة</th>
                                                <th scope="col">ID</th>
                                                <th scope="col"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($managedPages as $page)
                                                <tr>
                                                    <td><img src="{{ $page->image ?? '' }}" alt=""></td>
                                                    <td>{{ $page->name }}</td>
                                                    <td>{{ $page->id }}</td>
                                                    <td>
                                                        <input type="checkbox" {{ store_has_bot($page->id, 'facebook') ? 'checked' : '' }} name="pages[]" value="{{ $page->access_token }}">
                                                        <input type="hidden" name="page_ids[]" value="{{ $page->id }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <button type="submit">حفظ</button>
                        </form>
                    </div>
                </div>
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
