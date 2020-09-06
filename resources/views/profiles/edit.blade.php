@extends('layouts.master')


@section('content')
<div class="form-banner" >
    <div class="form" style="width: 70%;top:40%">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form method="POST" action="{{ route('profiles.update',$user->id) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <input type="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" required  placeholder="Name">
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" required  placeholder="E-Mail Address">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>


            <div class="form-group text-center mt-3">
                <button type="submit" class="btn">
                    {{ __('Update') }}
                </button>
            </div>
        </form>
        <hr>
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ route('profiles.updatePassword',$user->id) }}" method="post">
            @csrf
            @method('PUT')
            @csrf
            <div class="form-group">
                <input type="password" name="current-password" class="form-control @error('current-password') is-invalid @enderror" placeholder="Current passowrd" required>
                @error('current-password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <input type="password" name="new-password" class="form-control @error('new-password') is-invalid @enderror" placeholder="New passowrd" required>
                @error('new-password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <input type="password" name="new-password_confirmation" class="form-control" placeholder="Confirm new passowrd" required>
            </div>
            <div class="form-group text-center mt-3">
                <button type="submit" class="btn">
                    {{ __('Update password') }}
                </button>
            </div>
        </form>

    </div>


</div>
@endsection
