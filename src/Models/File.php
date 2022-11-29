<?php

namespace KitBox\FileUploader\Models;

use Http\Client\Exception\HttpException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use KitBox\FileUploader\Traits\PreventsModelEvents;

class File extends Model
{
    use PreventsModelEvents;

    protected static $prevents = ['updating'];

    protected $appends = ['full_path', 'path'];

    public static function booted()
    {
        static::deleted(function (Model $model) {
            if (in_array(SoftDeletes::class, class_uses_recursive($model))) {
                if (!$model->isForceDeleting()) {
                    return;
                }
            }

            Storage::disk($model->disk)->delete($model->path);
        });
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeLabeled($query, $label)
    {
        return $query->where('label', $label);
    }

    public function disk(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => array_key_exists($value, config('filesystems.disks')) ? $value : null
        );
    }

    public function fullPath(): Attribute
    {
        return Attribute::make(
            get: fn () => Storage::disk($this->disk)->path($this->path)
        );
    }

    public function path(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->folder ? ($this->folder . "/" . $this->name) : $this->name
        );
    }

    public function download()
    {
        $this->abortIfFileNotExists();

        return response()->download($this->full_path, $this->original_name);
    }

    public function response()
    {
        $this->abortIfFileNotExists();

        return response()->file($this->full_path);
    }

    private function abortIfFileNotExists()
    {
        if (!Storage::disk($this->disk)->exists($this->path)) {
            abort(404);
        }
    }
}
