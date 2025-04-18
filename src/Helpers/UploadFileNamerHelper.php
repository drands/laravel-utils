<?php

use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

if (!function_exists('uploadFileNamer')) {
    function uploadFileNamer(TemporaryUploadedFile $file, $disk = 'public')
    {
        $originalName = $file->getClientOriginalName();
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);

        //if file exists, prepend with timestamp
        $fileName = $nameWithoutExtension . '.' . $extension;
        $i = 1;
        while (Storage::disk($disk)->exists($fileName)) {
            $fileName = pathinfo($nameWithoutExtension, PATHINFO_FILENAME) . '-' . $i . '.' . $extension;
            $i++;
        }

        return $fileName;
    }
}
