@extends('layouts.master')

@section('content')
<div class="container-fluid" >
    <div class="row justify-content-center">
        @forelse ($actors as $actor)
            <div class="col-lg-2 col-md-3 col-sm-4 col-10 text-center">
                <a href="{{ route('actors.show',$actor->slug) }}" class="text-dark" >
                    <img src="{{ $actor->photo }}" class="rounded" alt="{{ $actor->name }}'s photo'" >
                    <h5 class="movie-title">{{ $actor->name }}</h5>
                </a>
            </div>
        @empty
        <h1>No actors</h1>
        @endforelse


    </div>
    <div class="row justify-content-center" >
        {{ $actors->links() }}
    </div>
</div>
@endsection
