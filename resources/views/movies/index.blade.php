@extends('layouts.app')


@section('content')
    @forelse ($movies as $movie)
        @if($movie->percentage === 100)
        <div id="next" class="col-lg-2 col-md-3 col-sm-4 col-7 m-1">
            <div class="movie">
                <div class="movie_overlay">
                    <div class="movie_overlay-categories" >
                        @foreach ($movie->categories as $category)
                            <span class="badge badge-light">
                                {{ $category->name }}
                                <i class='fas fa-play'></i>
                            </span>
                        @endforeach

                    </div>
                    <div class="movie_overlay-watch " >
                        <a href="{{ route('movies.show',$movie->id) }}">
                            <i class='fas fa-play-circle' style='font-size:60px;color:white'></i>
                        </a>
                    </div>

                </div>

                <img src="{{ $movie->getPosterIndex() }}"  class="img-fluid movie_img"   alt="">


                <h5 class="movie_title">
                    {{ $movie->title }}
                </h5>
            </div>
        </div>
        @endif

    @empty
        <div class="alert alert-danger">
            No movies
        </div>
    @endforelse

    <div class="col-12" >
        {{ $movies->links() }}
    </div>

@endsection
