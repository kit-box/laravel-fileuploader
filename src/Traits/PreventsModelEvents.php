<?php

namespace KitBox\FileUploader\Traits;

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
