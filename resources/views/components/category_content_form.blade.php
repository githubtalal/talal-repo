@php
    $labels = [__('app.categories.info.Name')];
    $name_value = old('name');
    $image = '';
    $image_settings = '';
    $active = 1;

    $image_status = false;

    if ($category) {
        $name_value = $category->name;
        $image = \Illuminate\Support\Facades\Storage::url($category->image);
        $imageUrl = $category->image;
        $image_settings = $category->image_settings;
        $active = $category->active;

        // check if status is existed in the settings array because we have categries stored previously without status
        if ($image_settings && array_key_exists('status', $image_settings) && $image_settings['status']) {
            $image_status = true;
        }
    }

    $current_route = Route::currentRouteName();
    if ($current_route === 'category.create') {
        $route = route('category.store');
    } else {
        $route = route('category.update', [$category]);
    }
@endphp

<!--begin::Form-->
<form class="form d-flex flex-column flex-lg-row" action="{{ $route }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class=" {{ $current_route }} d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
        <x-inputs.image :name="'image'" :url="$image"></x-inputs.image>
        <input type="hidden" name="previous_image" value="{{ $imageUrl ?? '' }}" />
    </div>
    <!--begin::Main column-->
    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
        <!--begin::Tab content-->
        <div class="tab-content">
            <!--begin::Tab pane-->
            <div class="tab-pane fade show active" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">

                    <!--begin::General options-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{ __('app.categories.category_info') }}</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">

                            <!--begin::Name-->
                            <div class="mb-10 fv-row col-lg-6">
                                <x-inputs.text_input :label="$labels[0]" :name="'name'" :type="'category'"
                                    :value="$name_value">
                                </x-inputs.text_input>
                            </div>
                            <!--end::Name-->

                            <!--begin::Active-->
                            <div class="col-lg-12 d-flex align-items-center mb-8">
                                <!--begin::Label-->
                                <label class="col-lg-4 form-label">
                                    حالة الفئة
                                </label>
                                <!--end::Label-->
                                <!--begin::Switch-->
                                <div
                                    class="form-check form-switch form-switch-sm form-check-custom form-check-solid m-1">
                                    <input class="form-check-input" type="checkbox" value="1" name="active"
                                        style="width: 40px; height:24px;" {{ $active ? 'checked' : 'unchecked' }} />
                                </div>
                                <!--end::Switch-->
                            </div>
                            <!--end::Active-->

                            <!--Image Settings-->
                            <div class="d-flex align-items-center mb-8">
                                <h4 class="col-lg-4">إعدادات الصورة :</h4>

                                <!--begin::Switch-->
                                <div
                                    class="form-check form-switch form-switch-sm form-check-custom form-check-solid m-1">
                                    <input class="form-check-input" type="checkbox" value="1"
                                        name="image_settings[status]" style="width: 40px; height:24px;"
                                        {{ $image_status ? 'checked' : 'unchecked' }} />
                                </div>
                                <!--end::Switch-->
                            </div>

                            <div class="mb-10 fv-row d-flex align-items-center">
                                <label class="form-label col-lg-3" for="font_color">اختر لون الخط :</label>
                                <input type="color" class="" id="font_color" name="image_settings[font_color]"
                                    value="{{ $image_settings ? $image_settings['font_color'] : '#FFFFFF' }}">
                            </div>

                            <div class="mb-10 fv-row d-flex align-items-center">
                                <label class="form-label col-lg-3" for="background_color">اختر لون الخلفية
                                    :</label>
                                <input type="color" class="" id="background_color"
                                    name="image_settings[background_color]"
                                    value="{{ $image_settings ? $image_settings['background_color'] : '' }}">
                            </div>

                            <div class="mb-10 fv-row">
                                <label class="form-label col-lg-3">اختر مقدار الشفافية :</label>
                                <input type="range" min="0" max="1" step="0.1"
                                    value="{{ $image_settings ? $image_settings['opacity'] : '0.5' }}"
                                    class="slider mt-4 mb-4" id="myRange" name="image_settings[opacity]">
                                <p>القيمة :<span id="value"></span></p>
                            </div>

                            <!--Buttons-->
                            <div class="d-flex justify-content-end">
                                <!--Save button-->
                                <x-inputs.save_button></x-inputs.save_button>
                                <!--cancel button-->
                                <a href="{{ route('category.index') }}"
                                    class="btn btn-light ms-3">{{ __('app.button.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                    <!--end::General options-->
                </div>
            </div>
            <!--end::Tab pane-->
        </div>
        <!--end::Tab content-->
    </div>
    <!--end::Main column-->
</form>
<!--end::Form-->

<style>
    input[type='color'] {
        padding: 0;
        height: 30px;
        width: 30px;
        border: 1px;
    }

    .slider {
        -webkit-appearance: none;
        width: 50%;
        height: 8px;
        border-radius: 3px;
        background: #d3d3d3;
        outline: none;
        -webkit-transition: .2s;
        transition: opacity .2s;
        display: block;
    }

    .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        background: #009ef7;
        cursor: pointer;
    }

    .slider::-moz-range-thumb {
        width: 15px;
        height: 15px;
        border-radius: 50%;
        background: #009ef7;
        cursor: pointer;
    }
</style>

<script>
    var slider = document.getElementById("myRange");
    var output = document.getElementById("value");
    output.innerHTML = slider.value;

    slider.oninput = function() {
        output.innerHTML = this.value;
    }
</script>
