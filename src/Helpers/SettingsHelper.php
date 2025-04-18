<?php

use App\Settings\GeneralSettings;

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        //if $key has a dot, we assume it's a nested key
        if (str_contains($key, '.')) {
            $keys = explode('.', $key);
            $parentKey = array_shift($keys);
            $parent = app(GeneralSettings::class)->{$parentKey};

            foreach ($keys as $nestedKey) {
                if (!isset($parent[$nestedKey])) {
                    return $default;
                }
                $parent = $parent[$nestedKey];
            }
            return $parent;
        }

        try {
            return app(GeneralSettings::class)->{$key} ?? $default;
        } catch (Exception $e) {
            return $default;
        }
    }
}

