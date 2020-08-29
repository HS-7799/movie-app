<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

use App\Movie;

class VoteController extends Controller
{

    public function store(Request $request,$entityId)
    {
        $entity = Movie::find($entityId);
        if(!$entity)
        {
            $entity = Comment::find($entityId);
        }
        $vote = $entity->votes()->where('user_id',auth()->id())->first();

        if($vote)
        {
            return $vote->update([
                'type' => request('type')
            ]);
        }

        return $entity->votes()->create([
            'user_id' => auth()->id(),
            'type' => request('type')
        ]);

    }

    public function destroy($entityId)
    {
        $entity = Movie::find($entityId);

        if(!$entity)
        {
            $entity = Comment::find($entityId);
        }

        $vote = $entity->votes()->where('user_id',auth()->id())->first();

        return $vote->delete();
    }
}
