 <label for="{{ $label }}" class="required form-label"> {{ $label }}</label>

 <input type="text" name="{{ $name }}" class="form-control mb-2 @error($name) is-invalid @enderror"
     id="{{ $label }}" placeholder="{{ $label }}" value="{{ $value ?? '' }}" {{ $attributes }} />

 @error($name)
     <div class="alert alert-danger">{{ $message }}</div>
 @enderror
