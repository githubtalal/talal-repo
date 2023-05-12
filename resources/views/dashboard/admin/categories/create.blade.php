@extends('dashboard.layouts.master')

@section('content')
    <div class="dashboard">
        <div class="card admin-card">
            <div class="card-body">
                <h3>إنشاء فئة جديد</h3>
                <hr>
                <form action="{{route('category.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 p-0-1">
                            <div class="form-floating">
                                <input value="{{ old('name') }}" name="name" type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="floatingInputGrid" placeholder="الإسم المنتج">
                                <label for="floatingInputGrid">الاسم<span class="red">*</span></label>
                                @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
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
@endpush
