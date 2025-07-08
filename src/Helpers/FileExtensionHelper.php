<?php

/**
 * Utility functions for handling file extensions.
 *
 * @package LaravelUtils
 */
if (!function_exists('getFileExtension')) {
    function getFileExtension($filePath)
    {
        // Check if the file exists
        if (!file_exists($filePath)) {
            return null; // or throw an exception
        }

        // Use pathinfo to get the file extension
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        return $extension ? strtolower($extension) : null; // Return lowercase extension or null if no extension
    }
}

if (!function_exists('isValidFileExtension')) {
    function isValidFileExtension($filePath, $validExtensions)
    {
        $extension = getFileExtension($filePath);
        return in_array($extension, $validExtensions);
    }
}

if (!function_exists('isImageFile')) {
    function isImageFile($filePath)
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        return isValidFileExtension($filePath, $imageExtensions);
    }
}

if (!function_exists('isVideoFile')) {
    function isVideoFile($filePath)
    {
        $videoExtensions = ['mp4', 'avi', 'mov', 'mkv', 'flv', 'wmv'];
        return isValidFileExtension($filePath, $videoExtensions);
    }
}