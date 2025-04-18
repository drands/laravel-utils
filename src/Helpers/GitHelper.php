<?php

/**
 * Attempt to Retrieve Current Git Commit Hash in PHP.
 *
 * @return mixed
*/
if (!function_exists('getCurrentGitCommitHash')) {
    function getCurrentGitCommitHash()
    {
        //if REVISION file exists, return its content
        $path = base_path('REVISION');

        if (file_exists($path)) {
            return trim(file_get_contents($path));
        }

        //if .git directory exists, return the hash of the HEAD
        $path = base_path('.git/');

        if (! file_exists($path)) {
            return null;
        }

        $head = trim(substr(file_get_contents($path . 'HEAD'), 4));

        $hash = trim(file_get_contents(sprintf($path . $head)));

        return $hash;
    }
}