<?php

namespace KitBox\FileUploader\Traits;

use Illuminate\Support\Collection;
use KitBox\FileUploader\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use KitBox\FileUploader\Helpers\FileUploader;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasFiles
{
    private bool $delete_with_files = true;
    private ?string $label = null;

    public static function bootHasFiles()
    {
        static::deleting(function (Model $model) {
            if ($model->deleteWithoutFiles()) {
                return;
            }

            if (config('file-uploader.auto_delete') === false) {
                return;
            }

            if (in_array(SoftDeletes::class, class_uses_recursive($model))) {
                if (!$model->forceDeleting) {
                    return;
                }
            }

            $model->files()->cursor()->each(fn (File $file) => $file->delete());
        });
    }

    protected function files(): MorphMany
    {
        return $this->morphMany(File::class, 'model');
    }

    private function deleteWithoutFiles(): bool
    {
        return $this->delete_with_files === false;
    }

    public function preserveFiles(): self
    {
        $this->delete_with_files = false;

        return $this;
    }

    public function setFileLabel(string $label): self
    {
        if (strlen($label) !== 0) {
            $this->label = $label;
        }

        return $this;
    }

    public function upload(string $field, ?string $folder = null, ?string $disk = null): Collection|File
    {
        $uploads = app(FileUploader::class)
            ->setModel($this->files())
            ->setLabel($this->label)
            ->setFolder($folder ?: config('file-uploader.base_folder'))
            ->setDisk($disk ?: config('file-uploader.disk'))
            ->upload($field);

        $this->label = null;

        return $uploads;
    }

    private function prepareQuery(?string $label = null): MorphMany
    {
        $query = $this->files();

        if ($label) {
            $query = $query->labeled($label);
        }

        return $query;
    }

    public function getFiles(?string $label = null): Collection
    {

        return $this->prepareQuery($label)->get();
    }

    public function getFile(?string $label = null): File
    {
        return $this->prepareQuery($label)->first();
    }

    public function deleteFiles(?string $label = null): bool
    {
        $this->prepareQuery($label)->cursor()->each(fn (File $file) => $file->delete());
        return true;
    }
}
