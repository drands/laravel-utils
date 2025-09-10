# Drands Laravel Utils

## Installation

```bash
composer require drands/laravel-utils
```

## Utils

### DeleteOnCascade Trait
This trait can be used to automatically delete related models when a parent model is deleted. It leverages Laravel's model events to listen for deletions and cascade the delete operation to related models.

#### Usage
To use this trait, you need to include it in your Eloquent model:

```php
use Drands\LaravelUtils\Traits\DeleteOnCascade;

class Post extends Model
{
    use DeleteOnCascade;

    protected $deleteOnCascade = ['mediaItems'];
}
```

### HasBuilder Trait
This trait provides functionality to manage media files associated with a model that uses a builder pattern. It ensures that media files are properly deleted when the model is deleted.

#### Usage
To use this trait, you need to include it in your Eloquent model:

```php
use Drands\LaravelUtils\Traits\HasBuilder;

class Post extends Model
{
    use HasBuilder;

    protected $builders = ['body'];
}
```

### HasUploads Trait
This trait provides functionality to manage file uploads associated with a model. It ensures that uploaded files are properly deleted when the model is deleted.

#### Usage
To use this trait, you need to include it in your Eloquent model:

```php
use Drands\LaravelUtils\Traits\HasUploads;

class Post extends Model
{
    use HasUploads;

    protected $uploadFields = ['file'];
}
```

### CloneTranslations (Filament Action)
This utility provides a Filament action to clone translations from one language to another. It allows you to select the source language, target languages, and specific fields to clone.

#### Usage
To use this utility, you need to include the action in your Filament resource:

```php
use Drands\LaravelUtils\Filament\Actions\CloneTranslations;

public static function getActions(): array
{
    return [
        CloneTranslations::make()
            ->builderFields(['body']), // Specify the builder fields
    ];
}
```

### Helpers

#### getFileExtension($filePath)
This helper function retrieves the file extension from a given file path.

#### isValidFileExtension($filePath, Array $validExtensions)
This helper function checks if the file extension of the given file path is valid based on the provided list of valid extensions.

#### isImageFile($filePath)
This helper function checks if the given file path is an image file based on its extension.

#### isVideoFile($filePath)
This helper function checks if the given file path is a video file based on its extension.

#### getCurrentGitCommitHash()
This helper function retrieves the current Git commit hash of the project.

#### media_embed($id, $host, $attributes = [], $params = [])
This helper function generates an HTML embed code for a media file.

#### money_format($number, $showZeros = true, $decimals = 2, $showCurrency = true)
This helper function formats a number as a monetary value.

#### setting($key, $default = null)
This helper function retrieves the value of a specific setting.

#### uploadFileNamer(TemporaryUploadedFile $file, $disk = 'public')
This helper function generates a unique name for an uploaded file.

#### pageShow($slug = '')
```php
Route::get('/about', pageShow('about'))->name('about');
```
This helper function allows you to create a route for the "show" page of a given resource.

#### pageIndex($slug = '')
```php
Route::get('/about', pageIndex('about'))->name('about');
```
This helper function allows you to create a route for the "index" page of a given resource.

#### imageUrl($filename = '')
This helper function generates a URL for a given image file if it exists, otherwise it returns a default placeholder image URL.
