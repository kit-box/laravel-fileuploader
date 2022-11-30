<p><a target="_blank" href="https://github.com/kit-box"><img src="https://user-images.githubusercontent.com/118080144/201520966-830879c3-5e7a-4a80-8c96-b20a21de4a05.jpg" width="300" /></a></p>

# KitBox - Laravel File Uploader

[![Downloads](https://img.shields.io/packagist/dt/kit-box/laravel-fileuploader?style=for-the-badge)](https://packagist.org/packages/kit-box/laravel-fileuploader)
[![License](https://img.shields.io/github/license/kit-box/laravel-fileuploader?style=for-the-badge)](https://github.com/kit-box/laravel-fileuploader/blob/main/LICENSE.md)

A simple uploader for files associated with Models.

## Getting Started

### Dependencies

* PHP 8+
* Laravel 9+

### Installation

Run the following to include this via Composer

```shell
composer require kit-box/laravel-fileuploader
```

Next, you should publish the migrations.

```php
php artisan vendor:publish --provider="KitBox\FileUploader\FileUploaderServiceProvider" --tag="migrations"
```

Optionally, you can publish the config file.

```php
php artisan vendor:publish --provider="KitBox\FileUploader\FileUploaderServiceProvider" --tag="config"
```

Base config file:

```php
return [
    /*
    | Used to indicate on which disk the files will be saved. 
    | Always remember to specify an existing disk configured in the basic Laravel "filesystems.php" config file.
    |
    | Supported types: string
    */
    'disk' => env('FILESYSTEM_DISK', 'local'),

    /*
    | The default folder or path where files will be saved on disk. 
    | The parameter can also be left null or "/" if you want to save on the primary root of the disk.
    |
    | Supported types: null, string
    */
    'base_folder' => 'uploads',

    /*
    | Used to specify whether files can be deleted automatically when the reference model is deleted. 
    | In case you use the softDelete the files will not be deleted
    |
    | Supported types: boolean
    */
    'auto_delete' => true
];
```

### Usage

#### Adding Trait

First, add the Trait to the Model you want to associate files

```php
namespace App\Models;

use KitBox\FileUploader\Traits\HasFiles;

class Article extends Model
{
    use HasFiles;
}
```

#### Upload

Simple uploading files:

```php
use App\Models\Article;
use App\Http\Requests\ArticleRequest;

public function store(ArticleRequest $request){
    $new_article = Article::create($request->validated());

    // Just pass the field file name as string, 
    // It will performs multiple upload automatically if is multiple field
    $new_article->upload('photos');
}
```

Optionally you can pass additional parameters like:

```php
$new_article
    ->setFileLabel('archive_photos') //you can specify a label to retrieve them later
    ->upload(
        field: 'photos',
        folder: 'uploads/specific_folder', //you can specify the folder and go deeper in path if needed
        disk: 'public' //you can use specific disk if needed, always remember to register it in config/filesystem.php first
    );
```

#### Get Files

Retrieving files:

```php
use App\Models\Article;

public function show(Article $article){

    $article->getFiles(); //Getting all the files

}
```

Retrieving files by label:

```php
$article->getFiles('archive_photos'); //Just pass string label inside the method
```

Retrieving single file:

```php
$first_file = $article->getFile(); //Getting first file
$banner = $article->getFile('banner'); //Getting first specific labeled file
```

Retrieving files responses:

```php
$banner = $article->getFile('banner');
return $banner->response();
```

Or going for direct download:

```php
return $banner->download();
```

#### Delete Files

Deleting files:

```php
$article->deleteFiles(); //Deleting all files
$article->deleteFiles('archive_photos'); //Deleting labeled files
```

Deleting files on model delete:
Normally the files are automatically deleted upon deleting the model. If you need to keep them, put this method before deleting
```php
$article->preserveFiles()->delete();
```
You can disable autodelete via config/file-uploader.php

Note: Models that use softDelete will not delete files automatically

#### File delete command

You can perform this command to delete manually files

Delete all files:
```php
php artisan files:clear
```

Delete all model files:
```php
php artisan files:clear "App\Models\Article"
```

Delete all model files by label:
```php
php artisan files:clear "App\Models\Article" "archive_photos"
```

## Authors
[@simaelw](https://github.com/simaelw/simaelw)

## Version History

* 1.0.0
    * Initial Release

## License

This project is licensed under the MIT License - see the [License File](LICENSE.md) for details
