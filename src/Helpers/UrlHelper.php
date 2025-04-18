<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('pageShow')) {
    function pageShow($slug = '')
    {
        return function () use ($slug) {
            return app()->call('App\Http\Controllers\PageController@show', ['slug' => $slug]);
        };
    }
}

if (!function_exists('pageIndex')) {
    function pageIndex($slug = '')
    {
        return function () use ($slug) {
            return app()->call('App\Http\Controllers\PageController@index', ['slug' => $slug]);
        };
    }
}

if (!function_exists('imageUrl')) {
    function imageUrl($filename = '')
    {
        return $filename ? Storage::url($filename) : asset('img/image-not-found.webp');
    }
}
