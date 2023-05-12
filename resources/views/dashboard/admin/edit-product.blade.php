@extends('dashboard.layouts.master')

@section('content')
    <div class="dashboard">
        <div class="card admin-card">
            <div class="card-body">
                <h3>{{ $product->name }}</h3>
                <hr>
                <form action="{{route('products.update', $product->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 p-0-1">
                            <div class="form-floating">
                                <input value="{{ old('name') ?? $product->name }}" name="name" type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="floatingInputGrid" placeholder="الإسم المنتج">
                                <label for="floatingInputGrid">الاسم<span class="red">*</span></label>
                                @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 p-0-1">
                            <div class="form-floating">
                                <select name="category_id"
                                        required
                                        class="form-control @error('type') is-invalid @enderror"
                                        id="floatingInputGrid" placeholder="السعر">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <label for="floatingInputGrid">نوع المنتج<span class="red">*</span></label>
                                @error('type')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 p-0-1">
                            <div class="form-floating">
                                <select name="type" type="number"
                                        required
                                        class="form-control @error('type') is-invalid @enderror"
                                        id="floatingInputGrid" placeholder="السعر">
                                    <option {{ $product->type === 'product' ? 'selected' : '' }} value="product">
                                        منتج عادي
                                    </option>
                                    <option {{ $product->type !== 'product' ? 'selected' : '' }} value="reservation">
                                        حجوزات
                                    </option>
                                </select>
                                <label for="floatingInputGrid">نوع المنتج<span class="red">*</span></label>
                                @error('type')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 p-0-1 {{ $product->type != 'reservation' ? 'd-none' : ''}}" id="require_end_date">

                            <div class="form-check">
                                <input name="require_end_date" type="checkbox"
                                       {{ $product->require_end_date ? 'checked' : '' }}
                                       class="form-check-input @error('require_end_date') is-invalid @enderror"
                                       id="checkbox" placeholder="السعر">
                                <label for="checkbox" class="form-check-label">الحجز يتطلب تاريخ انتهاء ؟<span class="red">*</span></label>
                                @error('require_end_date')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="col-12 p-0-1" >
                            <div class="form-floating">
                                <select  name="unit"
                                         required
                                         class="form-control @error('unit') is-invalid @enderror"
                                         id="floatingInputGrid" placeholder="السعر">
                                    <option {{ $product->unit === 'quantity' ? 'selected' : '' }} value="quantity">قطعة</option>
                                    <option {{ $product->unit !== 'quantity' ? 'selected' : '' }} value="nights" {{ old('type') === 'reservation' ? 'disabled' : '' }}>
                                        مدة زمنية</option>
                                </select>
                                <label for="floatingInputGrid">'وحدة المنتج<span class="red">*</span></label>
                                @error('unit')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 p-0-1">
                            <div class="form-floating">
                                <input value="{{ old('price') ?? $product->price }}" name="price"
                                       required
                                       class="form-control @error('price') is-invalid @enderror"
                                       id="floatingInputGrid" placeholder="سعر الوحدة">
                                <label for="floatingInputGrid">سعر الوحدة<span class="red">*</span></label>
                                @error('price')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 p-0-1" style="margin-top: 1.5em">
                            <label for="Image" class="form-label">إرفع الصورة الخاصة بالمنتج</label>
                            <input class="form-control" type="file" name="image_url" id="formFile" onchange="preview(this)">
                            {{--                            <input--}}
                            {{--                                required--}}
                            {{--                                class="form-control" name="image_url" id="file-upload" type="file"--}}
                            {{--                                accept=".png,,.jpeg,.jpg"/>--}}
                            @error('image_url')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12" style="margin-top: 1.5em;text-align: center">
                            <img id="frame" src="{{ $product->image_url ? \Illuminate\Support\Facades\Storage::url($product->image_url) : '' }}" class="img-fluid" />

                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary submit-btn btn-block bg-blue">إنشاء</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function preview(event) {
            $('#frame').attr('src', URL.createObjectURL(event.files[0]));
        }
        $(document).ready(function () {

            $('select[name="type"]').change(function() {
                if ($(this).val() === 'reservation')
                    $('#require_end_date').removeClass('d-none');
                else
                    $('#require_end_date').addClass('d-none');

            });

            $('#require_end_date input').change(function () {
                if ($(this).prop('checked')) {
                    $('select[name="unit"] option').eq(1).prop('disabled', false);
                } else {
                    $('select[name="unit"] option').eq(1).prop('disabled', true);
                }
            })
        });
    </script>
@endpush
