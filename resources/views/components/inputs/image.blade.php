<!--begin::Thumbnail settings-->
<div class="card card-flush py-4">
    <!--begin::Card header-->
    <div class="card-header">
        <!--begin::Card title-->
        <div class="card-title">
            <h2>{{ __('app.image.image') }}</h2>
        </div>
        <!--end::Card title-->
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body text-center pt-0">
        <!--begin::Image input-->
        <div class="image-input image-input-empty image-input-outline mb-3" data-kt-image-input="true"
            style="background-image: url(assets/media/svg/files/blank-image.svg)">
            <!--begin::Preview existing avatar-->
            <div class="image-input-wrapper w-150px h-150px" style="background-image:url('{{ $url }}')">
            </div>
            <!--end::Preview existing avatar-->
            <!--begin::Label-->
            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                <i class="bi bi-pencil-fill fs-7"></i>
                <!--begin::Inputs-->
                <input type="file" name="{{ $name }}" accept="image/*"
                    value="{{ $url }}" />

                @error($name)
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <!--end::Inputs-->

            </label>
            <!--end::Label-->
            <!--begin::Cancel-->
            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                <i class="bi bi-x fs-2"></i>
            </span>
            <!--end::Cancel-->
            <!--begin::Remove-->
            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                <i class="bi bi-x fs-2"></i>
            </span>
            <!--end::Remove-->
        </div>
        <!--end::Image input-->
        <!--begin::Description-->
        <div class="text-muted fs-7">{{ __('app.image.description') }}</div>
        <!--end::Description-->
    </div>
    <!--end::Card body-->
</div>
<!--end::Thumbnail settings-->

@push('scripts')
    <script>
        $(`input[name="{{ $name }}"]`).change(function() {
            let file = $(this).prop('files');

            if (!file.length) return;

            file = file[0];

            if (file.size >= {{ config('validation.max_image_size') }}) {
                Swal.fire({
                    title: 'خطأ !',
                    text: 'حجم الصورة يجب أن لا يتخطى 1 ميغابايت.',
                    icon: 'error',
                })
                $(this).prop('files', new FileList())
            }
        });
    </script>
@endpush
