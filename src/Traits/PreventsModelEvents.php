<?php

namespace KitBox\FileUploader\Traits;

use Illuminate\Database\Eloquent\Model;

trait PreventsModelEvents
{
    public static function bootPreventsModelEvents()
    {
        foreach (static::$prevents as $event) {
            static::{$event}(function (Model $model) {
                return false;
            });
        }
    }
}
