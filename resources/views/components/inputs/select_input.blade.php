   <!--begin::Label-->
   <label class="required form-label">{{ $label }}</label>
   <!--end::Label-->

   <!--begin::Select-->
   <select class="form-select mb-2 @error($name) is-invalid @enderror" data-control="select2" data-hide-search="true"
       data-placeholder="Select an option" name="{{ $name }}" id="">
       @foreach ($items as $key => $value)
           <option value="{{ $key }}" {{ $selected == $key ? 'selected' : '' }}>
               {{ $value }}
           </option>
       @endforeach
   </select>

   @error($name)
       <div class="alert alert-danger">{{ $message }}</div>
   @enderror
   <!--end::Select-->
