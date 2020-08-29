<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name','slug'],$appends=['movies_count'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function movies()
    {
        return $this->belongsToMany(Movie::class)->withTimestamps();
    }

    public function getMoviesCountAttribute()
    {
        return $this->movies()->count();
    }

}
