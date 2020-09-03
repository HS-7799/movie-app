@extends('layouts.master')

@section('content')
<div class="container-fluid" >
    <div class="row justify-content-center">
        <div class="col-md-3 col-sm-4 col-7">
            <img src="{{ $actor->getFirstMediaUrl() }}"  class="img-fluid"   alt="">
        </div>
        <div class="col-lg-7 col-md-8">
            <h1 style="color: white" >
                {{ $actor->name }}
            </h1>
            <p style="text-align: justify;color:white;font-size:20px">
                {{ $actor->biographie }}
            </p>

            <div class="row">
                @foreach ($actor->movies as $movie)
                    <div class="col-lg-3 col-md-4 col-6">
                        <a href="{{ route('movies.show',$movie->id) }}" class="text-dark">
                            <img src="{{ $movie->getPosterIndex() }}" width="100%" alt="">
                            <h5 class="movie-title" >{{ $movie->title }}</h5>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    <div>
    </div>
@endsection
