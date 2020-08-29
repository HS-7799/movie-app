<?php

namespace App;

use App\Model;
use Spatie\MediaLibrary\HasMedia;

use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Movie extends Model implements HasMedia
{

    use InteractsWithMedia;

    protected $guarded = [],$with=['categories'];



    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();

    }

    public function votes()
    {
        return $this->morphMany(Vote::class,'voteable');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('comment_id')->orderBy('created_at','ASC');
    }

    public function getPosterIndex()
    {
        return $this->getFirstMedia('posters') ? $this->getFirstMedia('posters')->getUrl('poster-index') : '/images/noImage.png';
    }
    public function getPosterLivewire()
    {
        return $this->getFirstMedia('posters') ? $this->getFirstMedia('posters')->getUrl('poster-livewire') : '/images/noImage.png';
    }
    public function getPosterShow()
    {
        return $this->getFirstMedia('posters') ? $this->getFirstMedia('posters')->getUrl('poster-show') : '/images/noImage.png';

    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('poster-show')
              ->width(335)
              ->height(500)
              ->nonQueued();
        $this->addMediaConversion('poster-index')
              ->width(225)
              ->height(320)
              ->nonQueued();
        $this->addMediaConversion('poster-livewire')
              ->width(50)
              ->height(70)
              ->nonQueued();
    }



}
