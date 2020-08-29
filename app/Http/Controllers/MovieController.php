<?php

namespace App\Http\Controllers;

use App\Movie;
use Illuminate\Http\Request;
use App\Jobs\video\TakeThumbnail;
use App\Jobs\video\ConvertForStreaming;
use App\Http\Requests\movie\UploadMovieRequest;

class MovieController extends Controller
{
    public function __construct()
    {


       $this->middleware('auth')->only(['post','update','delete']);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(request()->wantsJson())
        {
            return Movie::latest()->paginate(5);

        }

        return view('movies.index',[
            'movies' => Movie::orderBy('release_year','desc')->paginate(10)
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadMovieRequest $request)
    {
        $movie = Movie::create([
            'title' => request('title'),
            'path' => request('video')->store("/movies")
        ]);

        $this->dispatch(new TakeThumbnail($movie));
        $this->dispatch(new ConvertForStreaming($movie));

        return $movie;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function show(Movie $movie)
    {
        if(request()->wantsJson())
        {
            return $movie;
        }

        return view('movies.show',compact('movie'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movie $movie)
    {
        $this->authorize('update_movie');

        $movie->update(request()->validate([
            'title' => 'required|string',
            'description' => 'required|min:100',
            'rating' => 'required|numeric|max:10|min:0',
            'trailer' => 'required|min:11|max:11',
            'release_year' => 'required|digits:4|max:'.date('y'),
            'cateogries' => 'exists:categories,id'

        ]));



        // associate movie with cateogries many to many
        $existingIds = $movie->categories()->pluck('id')->unique()->toArray();
        if(array_diff(request('categories'),$existingIds) === request('categories') )
        {
            $remove = array_merge(request('categories'),$existingIds);
        }
        else
        {


            if(sizeof(request('categories')) > sizeof($existingIds))
            {

                $remove = array_diff(request('categories'),$existingIds);

            }
            else
            {
                $remove = array_diff($existingIds,request('categories'));
            }

        }
        $movie->categories()->toggle($remove);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie)
    {
        $this->authorize('delete_movie');
        $movie->clearMediaCollection('posters');
        return $movie->delete();
    }

    public function updateViews(Movie $movie)
    {
        $movie->increment('views');
    }

    public function updatePoster(Request $request,Movie $movie)
    {
        $this->authorize('update_movie');
        request()->validate([
            'poster' => 'required|file|image'
        ]);

        $movie->clearMediaCollection('posters');

        $movie->addMedia(request('poster'))->toMediaCollection('posters');

        $url = $movie->getPosterIndex();
        $movie->update([
            'poster' => $url
        ]);

        return $url;
    }
}
