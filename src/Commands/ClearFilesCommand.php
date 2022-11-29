<?php

namespace KitBox\FileUploader\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use KitBox\FileUploader\Models\File;

class ClearFilesCommand extends Command
{
    protected $signature = 'files:clear {model?} {label?}';

    protected $description = 'Delete files command. You can query by model and by label.';

    public function handle()
    {
        $files = $this->queryFiles();

        $progress_bar = $this->output->createProgressBar($files->count());

        $files->each(function (File $file) use ($progress_bar) {
            $file->delete();
            $progress_bar->advance();
        });

        $progress_bar->finish();

        $this->newLine();

        $this->info('Files deleted.');
    }

    public function queryFiles(): Collection
    {
        $model = $this->argument('model');
        $label = $this->argument('label');

        $files = app(File::class);

        if (is_string($model)) {
            $files = $files->where('model_type', $model);
        }

        if (is_string($label)) {
            $files = $files->labeled($label);
        }

        return $files->get();
    }
}
