<?php

namespace App\Http\Controllers;

use App\Actor;
use App\Movie;
use Illuminate\Http\Request;

class ActorMovieController extends Controller
{
    public function store(Request $request,Movie $movie,Actor $actor)
    {
        $this->authorize('update_movie');
        request()->validate([
            'role' => 'required'
        ]);


        if(request('existingActors'))
        {
            $ids = $movie->actors()->pluck('id')->unique();
            $difference = explode(',',request('existingActors'));
            foreach($difference as $id)
            {
                if($ids->contains($id))
                $movie->actors()->detach($id);
            }
        }

        if($movie->actors->contains($actor))
        {
            $movie->actors()->toggle($actor,['role' => ucwords(request('role'))]);
            return $movie->actors()->save($actor,['role' => ucwords(request('role'))]);

        }

        return $movie->actors()->save($actor,['role' => ucwords(request('role'))]);

    }
}
