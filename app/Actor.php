<?php

namespace App;

use App\Model;

use Spatie\MediaLibrary\HasMedia;

use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;



class Actor extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['name','slug','biographie','photo'];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(200)
              ->height(300)
              ->nonQueued();

    }

    public function getPhoto()
    {
        return $this->getFirstMedia() ? $this->getFirstMediaUrl() : '/images/noImage.png';
    }

    public function getPhotoConverted()
    {
        return $this->getFirstMedia() ? $this->getFirstMedia()->getUrl('thumb') : '/images/noImage.png';
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function movies()
    {
        return $this->belongsToMany(Movie::class);
    }
}

