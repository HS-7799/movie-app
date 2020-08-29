@extends('layouts.app')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/video.js/5.10.2/alt/video-js-cdn.css" rel="stylesheet">

@endsection

@section('content')



    <div class="col-md-3 col-sm-4 col-7">
        <img src="{{ $movie->getPosterShow()}}"  class="img-fluid"   alt="">
    </div>
    <div class="col-lg-7 col-md-8">
            <h1>
                {{ $movie->title }}
            </h1>
            @can('update_movie')

            <div class="float-right" >
                <a href="/dashboard/movies/{{ $movie->id }}/edit" target="_blank" >edit</a>
            </div>
            @endcan


            <span class="text-center px-2" style='font-size:20px;border-right:1px solid black' >
                <i class='fas fa-star' style='color: rgb(239, 216, 9);'></i>
                 {{ $movie->rating }}
            </span>
                @foreach ($movie->categories as $category)
                    <span class="p-1 mx-1">{{ $category->name }}</span>
                @endforeach
            <span class="text-center px-2" style='border-left:1px solid black' >

                    Release date : {{ $movie->release_year }}
            </span>


            <p style="text-align: justify">
                {{ $movie->description }}
            </p>
            <div>
                <a class="btn btn-primary " href="#video">
                    WATCH NOW
                </a>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#movieTrailer">
                    Watch trailer
                </button>
            </div>
    </div>



          <!-- Modal -->
          <div class="modal" id="movieTrailer" tabindex="-1" role="dialog" aria-labelledby="movieTrailerTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-body">
                    <iframe width="100%" height="315" src="https://www.youtube.com/embed/{{ $movie->trailer }}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
              </div>
            </div>
          </div>

        <div class="col-10 ">
            <video id="video" class="video-js" preload="none" poster="/storage/{{ $movie->thumbnail }}" crossorigin="true" controls width="940" height="368" controls>
                <source src="/storage/videos/{{ $movie->id }}/{{ $movie->id }}.m3u8" type='application/x-mpegURL'>
            </video>
            <div class="mt-3" >
                <app-vote entity-id="{{  $movie->id }}"
                    :votes="{{ $movie->votes }}">
                </app-vote>
            </div>
            <p>Views : {{$movie->views}}</p>
        </div>

        <app-comments movie-id="{{ $movie->id }}" ></app-comments>


@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/5.10.2/video.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/3.0.2/videojs-contrib-hls.js"></script>
<script>
    window.movieId = '{{ $movie->id }}'
</script>
<script src="{{ asset('js/video.js') }}" ></script>
@endsection

