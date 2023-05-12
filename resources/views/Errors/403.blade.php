@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-center align-items-center">
        <div class="row">
            <div class="col-12">
                <p class="text-center" style="font-size: 80px"> error 403</p>
            </div>
            <div class="col-12">

                <p class="text-center" style="font-size: 40px">{{ $exception->getMessage() ?? 'Not Authorized' }}</p>
            </div>
        </div>
    </div>
@endsection
