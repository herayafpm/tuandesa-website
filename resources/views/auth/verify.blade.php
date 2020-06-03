@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verifikasi email anda terlebih dahulu') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Email baru sudah dikirimkan ke email anda') }}
                        </div>
                    @endif

                    {{ __('Sebelum melanjutkan, harap memverifikasi email anda terlebih dahulu') }}
                    {{ __('Jika anda tidak menerima email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('Klik disini untuk mengirim lagi') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
