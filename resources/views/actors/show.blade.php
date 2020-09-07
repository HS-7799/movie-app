@extends('layouts.master')

@section('content')
<div class="container-fluid" >
    <div class="row justify-content-center">
        <div class="col-md-3 col-sm-4 col-7">
            <img src="{{ $actor->getFirstMediaUrl() }}"  class="rounded img-fluid"   alt="{{ $actor->name }}'s photo'">
        </div>
        <div class="col-lg-7 col-md-8" style="color: white">
            <h1>
                {{ $actor->name }}
            </h1>
            <h3 ><span id="bar"></span> Biographie</h3>
            <p class="p-3" style="text-align: justify;font-size:20px;box-shadow:1px 1px 5px lightgray;overflow:auto">
                {{ $actor->biographie }}
            </p>

            <div class="row">
                @foreach ($actor->movies as $movie)
                    <div class="col-lg-3 col-md-4 col-6">
                        <a href="{{ route('movies.show',$movie->id) }}" class="text-dark">
                            <img src="{{ $movie->getPosterIndex() }}" class="rounded" width="100%" alt="">
                            <h5 class="movie-title" >{{ $movie->title }}</h5>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    <div>
    </div>
@endsection
