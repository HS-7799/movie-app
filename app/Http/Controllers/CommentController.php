<?php

namespace App\Http\Controllers;

use App\Movie;
use App\Comment;
use Illuminate\Http\Request;
use App\Http\Requests\comment\AddCommentRequest;
use App\Http\Requests\comment\DeleteCommentRequest;


class CommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->only(['store','update','destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Movie $movie)
    {
        if(request()->wantsJson())
        {
            return $movie->comments()->paginate(3);
        }
        else
        {
            return redirect()->to('/');
        }
    }

    public function show(Comment $comment)
    {
        return $comment->replies()->paginate(2);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddCommentRequest $request,Movie $movie)
    {
        $comment =  $movie->comments()->create([
            'user_id' => auth()->id(),
            'body' => request('body'),
            'comment_id' => request('comment_id')
        ])->fresh();

        return $comment;
    }





    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $comment->update([
            'body' => request('body')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete_comment',$comment);
        return $comment->delete();
    }
}
