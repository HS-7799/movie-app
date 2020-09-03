@extends('layouts.master')

@section('content')
<div class="form-banner">
    <div class="form" style="min-height: 150px" >
        {{ __('Please confirm your password before continuing.') }}

        <form method="POST" class="mt-5" action="{{ route('password.confirm') }}">
            @csrf

            <div class="form-group">
                <input  type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group text-center mt-3">
                <button type="submit" class="btn btn-primary">
                                    {{ __('Confirm Password') }}
                </button>
                @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
