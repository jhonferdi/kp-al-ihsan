<?php

namespace App\Traits;

use Str;

trait Slugs
{
    protected static function bootSlug()
    {
        static::creating(function ($model) {
            $model->slug = Str::snake(strtolower($model->name));
        });

        static::updating(function ($model) {
            $model->slug = Str::snake(strtolower($model->name));
        });
    }
}
