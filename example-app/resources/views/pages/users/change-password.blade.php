@extends('layouts.app')

@section('content')
<div class="container">
<div class="flex-row-fluid ml-lg-8">
    <!--begin::Form-->
    <form class="form" method="POST" action="{{ route('putChangePassword') }}" >
        @csrf
        @method('put')

        @if (session('alert'))
        <div class="alert alert-{{ session('alert.type') }}">
            {{ session('alert.message') }}
        </div>
        @endif

        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-xl-3 col-lg-3 col-form-label text-alert font-weight-bolder">Mật khẩu hiện tại*</label>
                    <div class="col-lg-9 col-xl-6">
                        <input required type="password" name="current_password" class="form-control form-control-lg form-control-solid mb-2 @error('current_password')is-invalid border-danger @enderror" value="" autocomplete="new-password">
                        @error('current_password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-xl-3 col-lg-3 col-form-label text-alert font-weight-bolder">Mật khẩu mới*</label>
                    <div class="col-lg-9 col-xl-6">
                        <input required type="password" name="password" class="form-control form-control-lg form-control-solid @error('password')is-invalid border-danger @enderror" value="" autocomplete="new-password">
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-xl-3 col-lg-3 col-form-label text-alert font-weight-bolder">Xác nhận mật khẩu mới*</label>
                    <div class="col-lg-9 col-xl-6">
                        <input required type="password" name="password_confirmation" class="form-control form-control-lg form-control-solid @error('password_confirmation')is-invalid border-danger @enderror" value="" autocomplete="new-password">
                        @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="card-footer py-3 text-center">
                <div class="card-toolbar">
                    <button type="submit" class="btn btn-success mr-2">Lưu</button>
                </div>
            </div>
        </div>
    </form>
    <!--end::Form-->
</div>
@endsection
