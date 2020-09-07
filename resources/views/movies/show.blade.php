@extends('layouts.master')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/video.js/5.10.2/alt/video-js-cdn.css" rel="stylesheet">

@endsection

@section('content')

    <div class="container-fluid" >

        {{-- movie detail --}}
        <div class="row justify-content-center mb-5">
            <div class="movie-details" >
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-7 col-sm-10  col-12">
                        <img src="{{ $movie->getPosterShow()}}" class="rounded" width="100%" alt="{{ $movie->title }}'s poster'">
                    </div>
                    <div class="col-lg-8 col-12 pt-5" class="movie-details-info">
                        <h2 class="title" >{{ $movie->title }}</h2>
                        @can('update_movie')

                        <div class="float-md-right" >
                            <a href="/dashboard/movies/{{ $movie->id }}/edit" class="btn btn-light" target="_blank" >
                                Edit
                            </a>
                        </div>
                        @endcan
                        <div class="movie-details-categories" >
                            <span id="bar" ></span>
                            <p>
                                @foreach ($movie->categories as $category)
                                    | <span>{{ $category->name }}</span>
                                @endforeach
                            </p>

                        </div>
                        <p>
                            <span class="info-title">
                                <i class='fas fa-star' style='font-size:19px;color: #FFD700;'></i>
                                Rating:
                            </span>
                            {{ $movie->rating }}

                        </p>
                        <p>
                            <span class="info-title">
                                Release:
                            </span>
                            {{ $movie->release_year }}
                        </p>
                        <p class="movie-description" >
                            {{ $movie->description }}
                        </p>
                        <div class="movie-watch-links" >
                            <a href="#video" class="btn" id="watch-now">
                                Watch Now
                            </a>
                            <a href="https://www.youtube.com/watch?v={{ $movie->trailer }}" class="btn popup-youtube" id="watch-trailer">
                                Watch trailer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- cast --}}
        @if ($movie->actors->count() > 0)

            <div class="row" >
                <div class="cast">
                    <h1><span id="bar" ></span> Cast</h1>
                    <div class="row p-4" >
                        @foreach ($movie->actors as $actor)
                            <div class="col-lg-3 col-md-4 col-sm-6 col-10 text-center my-2">
                                <a href="{{ route('actors.show',$actor->slug) }}" class="actor-link" >
                                    <img src="{{ $actor->photo }}" width="100%" alt="{{ $actor->name }}'s poster'" class="rounded mb-2">
                                    <h5>{{ $actor->name }}</h5>
                                    <p class="text">
                                        <span class="actor-role" >{{ $actor->pivot->role }}</span>
                                    </p>
                                </a>
                            </div>
                        @endforeach

                    </div>
                </div>

            </div>

        @endif
        <hr/>
        {{-- video --}}
        <div class="row justify-content-center">
            <div class="videocontent" >
                {{-- component --}}
                <app-add-to-favourite
                movie-id="{{ $movie->id }}"
                is-favorite="{{ $isFavourite }}"
                 ></app-add-to-favourite>
                <video  id="video" class="video-js vjs-default-skin vjs-16-9 mb-3" preload="none" poster="/storage/{{ $movie->thumbnail }}"  crossorigin="true" controls  width="640" height="320" controls>
                    <source src="/storage/videos/{{ $movie->id }}/{{ $movie->id }}.m3u8" type='application/x-mpegURL'>
                </video>
                <app-vote entity-id="{{  $movie->id }}"
                    :votes="{{ $movie->votes }}">
                </app-vote>
                <p>Views : {{$movie->views}}</p>
            </div>

        </div>

        {{-- comments --}}
        <div class="row justify-content-center">
            <h3 class="col-8" style="color: white" ><span id="bar" ></span> Comments</h1>
            <app-comments movie-id="{{ $movie->id }}" ></app-comments>
        </div>
    </div>
@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/5.10.2/video.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/3.0.2/videojs-contrib-hls.js"></script>
<script>
    window.movieId = '{{ $movie->id }}'
</script>
<script src="{{ asset('js/video.js') }}" ></script>
@endsection

