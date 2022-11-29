<p align="center"><a target="_blank" href="https://github.com/kit-box"><img src="https://user-images.githubusercontent.com/118080144/201520966-830879c3-5e7a-4a80-8c96-b20a21de4a05.jpg" / width="300"></a></p>

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

## Authors
....

## Version History

* 1.0.0
    * Initial Release

## License

This project is licensed under the [NAME HERE] License - see the LICENSE.md file for details