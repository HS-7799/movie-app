@extends('layouts.master')


@section('content')
<div class="container-fluid" >
    <div class="row justify-content-center">
        @forelse ($movies as $movie)
            <div class="col-lg-2 col-md-3 col-sm-4 col-7 m-1">
                <div class="movie">
                    <div class="movie-poster">
                        <img src="{{ $movie->getPosterIndex() }}" width="100%" alt="">
                    </div>
                    <div class="movie-icon">
                        <a href="{{ route('movies.show',$movie->id) }}" >
                            <i class="fas fa-play-circle"></i>
                        </a>
                    </div>
                    <h3 class="movie-title" >{{ $movie->title }}</h3>
                </div>
            </div>
        @empty
            <div class="alert alert-danger">
                No movies
            </div>
        @endforelse
    </div>
    <div class="row justify-content-center">
        {{ $movies->links() }}
    </div>

</div>
@endsection
