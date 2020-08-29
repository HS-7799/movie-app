<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model as baseModel;

class Model extends baseModel
{
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            $model->{$model->getKeyName()} = Str::uuid();
        });
    }
}
