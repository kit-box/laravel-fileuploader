<?php

namespace KitBox\FileUploader;

use Illuminate\Support\ServiceProvider;
use KitBox\FileUploader\Commands\ClearFilesCommand;

class FileUploaderServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishables();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/file-uploader.php', 'file-uploader');

        $this->commands([
            ClearFilesCommand::class
        ]);
    }

    private function publishables(): void
    {
        $this->publishes([
            __DIR__ . '/../migrations/create_files_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_files_table.php'),
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/../config/file-uploader.php' => config_path('file-uploader.php'),
        ], 'config');
    }
}
