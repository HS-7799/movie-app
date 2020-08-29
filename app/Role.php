<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [],$with = ['abilities'],$appends=['user_count'];

    public function abilities()
    {
        return $this->belongsToMany(Ability::class)->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function allowTo(Ability $ability)
    {
        return $this->abilities()->sync($ability,false);
    }

    public function getUserCountAttribute()
    {
        return $this->users()->count();
    }
}
