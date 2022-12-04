<?php

namespace KitBox\FileUploader\Helpers;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use KitBox\FileUploader\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class FileUploader
{
    private string $disk;
    private ?string $folder = null;
    private ?string $label = null;
    private ?MorphMany $model = null;

    public function upload(string $field): Collection|File
    {
        if ($this->model === null) {
            throw new \ErrorException("Model not provided");
        }

        if (!request()->hasFile($field)) {
            throw new \InvalidArgumentException("Invalid field {$field} provided");
        }

        $files = request()->file($field);

        return DB::transaction(function () use ($files) {
            if (!is_array($files)) {
                return $this->store($files);
            }

            $files = array_values($files);

            return collect(array_map(fn ($file) => $this->store($file), $files));
        });
    }

    private function store(UploadedFile $file): File
    {
        $filename = $this->generateFileName($file);

        $file->storeAs($this->folder, $filename, $this->disk);

        if (!Storage::disk($this->disk)->exists($this->folder . '/' . $filename)) {
            throw new \ErrorException("Something wrong with upload");
        }

        return $this->model->forceCreate([
            'original_name' => $file->getClientOriginalName(),
            'name' => $filename,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'label' => $this->label,
            'folder' => $this->folder,
            'disk' => $this->disk
        ]);
    }

    private function generateFileName(UploadedFile $file): string
    {
        $name = Str::uuid() . '.' . $file->getClientOriginalExtension();

        if (Storage::disk($this->disk)->exists($this->folder . '/' . $name)) {
            return $this->generateFileName($file);
        }

        return $name;
    }

    public function setDisk(string $disk): self
    {
        if (!array_key_exists($disk, config('filesystems.disks'))) {
            throw new \InvalidArgumentException("The disk {$disk} is not registered as a valid disk");
        };

        $this->disk = $disk;

        return $this;
    }

    public function setFolder(?string $folder): self
    {
        if (strpbrk($folder, "\\?%*:|\"<>") !== FALSE) {
            throw new \InvalidArgumentException("The folder given {$folder} is not valid");
        };

        $this->folder = $folder;

        return $this;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function setModel(MorphMany $model): self
    {
        $this->model = $model;

        return $this;
    }
}
