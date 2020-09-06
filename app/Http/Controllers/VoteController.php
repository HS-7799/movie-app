<?php

namespace App\Http\Controllers;

use App\Movie;
use App\Comment;

use App\Events\VoteEvent;
use Illuminate\Http\Request;

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

        broadcast(new VoteEvent($entityId))->toOthers();

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
