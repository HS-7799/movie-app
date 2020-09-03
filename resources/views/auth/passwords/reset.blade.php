@extends('layouts.master')

@section('content')
<div class="form-banner" >
    <div class="form">
        <form method="POST" class="mt-5" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <input placeholder="E-Mail Address" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <input type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                    <input  placeholder="Confirm Password" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="form-group text-center mt-3">
                <button type="submit" class="btn">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
