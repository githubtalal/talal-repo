@extends('newDashboard.layouts.master')
@section('content')
    @php
        $items = ['', __('app.messenger_bot.page_id'), __('app.messenger_bot.page_name')];
    @endphp

    <!--begin::Post-->
    <div class="content d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div class="toolbar">
            <!--begin::Page title-->
            <div class="container-fluid">
                <h1 class="text-dark fw-bolder fs-3 my-1">
                    {{ __('app.messenger_bot.settings') }}
                </h1>
            </div>
        </div>
        <!--begin::Container-->
        <div class="container-xxl">
            <div class="card card-flush">
                <!--begin::items info-->
                <div class="card-body pt-0">
                    <h5 class="text-dark fw-bolder mt-5 my-1">
                        {{ __('app.messenger_bot.add_new_page_message') }}
                    </h5>
                    <div class="fb-login-button mt-3 mb-8" data-width="300" data-size="large" data-button-type="continue_with"
                        data-scope="public_profile,email,pages_show_list,pages_read_user_content,pages_messaging"
                        data-onlogin="checkLoginStatus" data-layout="default" data-auto-logout-link="false"
                        data-use-continue-as="true">
                    </div>

                    <form action="{{ route('bot.messenger.set_pages') }}" method="post">
                        @csrf
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    @foreach ($items as $item)
                                        <th class="min-w-100px">{{ $item }}</th>
                                    @endforeach
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-bold text-gray-600">

                                @foreach ($managedPages as $page)
                                    <!--begin::Table row-->
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox"
                                                value="{{ $page->access_token }}"
                                                {{ store_has_bot($page->id, 'facebook') ? 'checked' : '' }} name="pages[]"
                                                id="flexCheckChecked">
                                            <input type="hidden" name="page_ids[]" value="{{ $page->id }}">
                                        </td>
                                        <td>
                                            <a href="" class="text-gray-800 fw-bolder">{{ $page->id }}</a>
                                        </td>
                                        <td>
                                            <a href="" class="text-gray-800 fw-bolder">{{ $page->name }}</a>
                                        </td>
                                @endforeach

                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                        <div class="d-flex justify-content-end">
                            <x-inputs.save_button></x-inputs.save_button>
                        </div>
                    </form>
                </div>
                <!--end::items info-->
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
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
                    success: function(response) {
                        if (response.success)
                            window.location.href = '{{ route('bot.messenger.list_pages') }}'
                    }
                });
            }
        }
    </script>
@endpush
