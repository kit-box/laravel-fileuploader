<p><a target="_blank" href="https://github.com/kit-box"><img src="https://user-images.githubusercontent.com/118080144/201520966-830879c3-5e7a-4a80-8c96-b20a21de4a05.jpg" / width="300"></a></p>

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
php artisan vendor:publish --provider="KitBox\FileUploader\FileUploaderServiceProvider" --tags="migrations"
```

Optionally, you can publish the config file.

```php
php artisan vendor:publish --provider="KitBox\FileUploader\FileUploaderServiceProvider" --tags="config"
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

Coming soon.

## Authors
[@simael](https://github.com/simaelw/simaelw)

## Version History

* 1.0.0
    * Initial Release

## License

This project is licensed under the MIT License - see the [License File](LICENSE.md) for details