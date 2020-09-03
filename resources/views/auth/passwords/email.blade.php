@extends('layouts.master')

@section('content')
<div class="form-banner" >
    <div class="form" style="min-height : 100px" >
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" class="mt-5" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <input  placeholder="E-Mail Address" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group text-center mt-3">
                    <button type="submit" class="btn ">
                        {{ __('Send Password Reset Link') }}
                    </button>
            </div>
        </form>
@endsection
