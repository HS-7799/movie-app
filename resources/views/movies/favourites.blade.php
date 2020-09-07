@extends('layouts.master')

@section('content')
<div class="container-fluid" >
    <div class="row">
        <h5  class="text-light col-10 offset-md-2" >
            <span id="bar" ></span> Favourites movies
        </h5>
    </div>
    <div class="row justify-content-center">
        @forelse ($movies as $movie)
            <div class="col-lg-2 col-md-3 col-sm-4 col-7 m-1">
                <div class="movie">
                    <a href="{{ route('movies.show',$movie->id) }}" >
                        <div class="movie-poster">
                            <img src="{{ $movie->getPosterIndex() }}" width="100%" alt="{{ $movie->title }}'s poster'">
                        </div>
                        <h3 class="movie-title" >{{ $movie->title }}</h3>
                    </a>
                </div>
            </div>
        @empty
            <h3 style="color:white" >
                You do not have any favourites movies!
            </h3>
        @endforelse
    </div>
    <div class="row justify-content-center">
        {{ $movies->links() }}
    </div>


</div>
@endsection
