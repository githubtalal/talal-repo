 <!--begin::Label-->
 <label for="Number" class="required form-label">{{ $label }}</label>
 <!--end::Label-->

 <!--begin::Input-->
 <input type="number" name="{{ $name }}" value="{{ $price }}" id="Number"
     class="form-control mb-2 @error($label) is-invalid @enderror" placeholder="{{ $label }}" />

 @error($name)
     <div class="alert alert-danger">{{ $message }}</div>
 @enderror

 <!--end::Input-->
