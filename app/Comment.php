<?php

namespace App;

use App\Model;


class Comment extends Model
{

    protected $guarded = [],$appends = ['userName','repliesCount'],$with=['votes'];


    // user who added the comment
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class)->whereNotNull('comment_id')->orderBy('created_at','ASC');
    }

    public function votes()
    {
        return $this->morphMany(Vote::class,'voteable');
    }

    public function getUserNameAttribute()
    {
        return $this->user->name;
    }
    public function getRepliesCountAttribute()
    {
        return $this->replies->count();
    }

}
