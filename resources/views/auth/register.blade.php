@extends('layouts.master')

@section('content')
<div class="form-banner" >
    <div class="form">
        <div class="row justify-content-center">
            <div class="form-links " >
                <a href="/login" class="login " >Log in</a>
                <a href="#" class="register active" >Register</a>
            </div>
        </div>
        <form method="POST" class="mt-5" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <input type="text" placeholder="Name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <input placeholder="E-Mail Address" type="email" placeholder="" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <input  type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <input id="password-confirm" type="password" placeholder="Confirm Password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="form-group text-center mt-3">
                <button type="submit" class="btn ">
                    {{ __('Register') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
