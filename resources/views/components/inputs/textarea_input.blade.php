 <!--begin::Label-->
 <label for="{{ $label }}}" class="required form-label">{{ $label }}</label>
 <!--end::Label-->

 <!--begin::Input-->
 <textarea class="form-control mb-2" id="{{ $label }}" rows="6" name="{{ $name }}"
     placeholder="{{ $label }}" {{ $attributes }}>{{ $value }}</textarea>
 <!--end::Input-->
