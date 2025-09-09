<?php

namespace Drands\LaravelUtils\Traits;

use Illuminate\Support\Facades\Storage;

/**
 * Trait HasUploads
 * 
 * @property Array $uploadFields
 */
trait HasUploads
{

    protected Array $defaultUploadFields = [];
    /*
    protected Array $uploadFields = [
        //'logo',
        //'image' => 'public',
        //'multiple', # ['file1', 'file2']
    ];
    */

    public function getUploadFields(): Array
    {
        return $this->uploadFields ?? $this->defaultUploadFields;
    }

    // events
    protected static function bootHasUploads(): void
    {
        //remove files on delete
        static::deleting(function ($model) {
            foreach ($model->uploadFields as $key => $value) {
                $field = is_string($key) ? $key : $value;
                $disk = is_string($key) ? $value : 'public';
                $model->deleteFile($field, $disk);
            }
        });

        //remove files on update
        static::updating(function ($model) {
            foreach ($model->uploadFields as $field) {
                if ($model->isDirty($field)) {
                    $model->deleteFile($field, 'public');
                }
            }
        });
    }

    private function deleteFile($field, $disk)
    {
        $file = $this->getOriginal($field);

        //if is multiple
        if (is_array($file)) {
            foreach ($file as $f) {
                if ($f && Storage::disk($disk)->exists($f)) {
                    Storage::disk($disk)->delete($f);
                }
            }
            return;
        }

        if ($file && Storage::disk($disk)->exists($file)) {
            Storage::disk($disk)->delete($file);
        }
    }
}