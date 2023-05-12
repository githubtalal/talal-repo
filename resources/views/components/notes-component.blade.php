<div class="card">
    <div class="card-header pt-5 gap-2 gap-md-5 flex-column">
        <h2 class="">الملاحظات</h2>
    </div>
    <div class="card-body">
        @foreach ($notes as $note)
            <!--begin::Note-->
            <div class="d-flex flex-wrap gap-2 flex-stack cursor-pointer">
                <!--begin::User-->
                <div class="d-flex align-items-center">
                    <!--begin::Avatar-->
                    <div class="symbol symbol-50 me-4">
                        <span class="symbol-label"
                            style="background-image:url('{{ Storage::url($note->user->store->logo) }}')"></span>
                    </div>
                    <!--end::Avatar-->
                    <div class="pe-5">
                        <!--begin::User details-->
                        <div class="d-flex align-items-center flex-wrap gap-1">
                            <a href="#" class="fw-bolder text-dark text-hover-primary">{{ $note->user->name }}</a>
                        </div>
                        <!--end::User details-->

                        <!--begin::Comment-->
                        <div class="text-gray-800 fw-bold mw-450px">
                            {!! $note->body !!}
                        </div>
                        <!--end::Comment-->
                    </div>
                </div>
                <!--end::User-->
            </div>
            <!--end::Note-->
            <div class="separator my-6"></div>
        @endforeach

        <form class="form" id="noteForm" action="{{ $route }}" method="post" enctype="multipart/form-data">
            @csrf
            <!-- Create the editor container -->
            <div id="editor" class="min-h-200px"></div>
            <textarea name='body' id="body" class="d-none @error('body') is-invalid @enderror"></textarea>

            @error('body')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-primary my-4">إضافة
                ملاحظة</button>
        </form>
    </div>
</div>
