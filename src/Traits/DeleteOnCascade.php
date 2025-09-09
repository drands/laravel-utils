<?php

namespace Drands\LaravelUtils\Traits;

/**
 * Trait DeleteOnCascade
 * 
 * @property Array $uploadFields
 */
trait DeleteOnCascade
{

    /*
    protected Array $deleteOnCascade = ['items'];
    */

    // events
    protected static function bootDeleteOnCascade(): void
    {
        // Delete items from each relationship
        static::deleting(function ($model) {
            foreach ($model->deleteOnCascade as $relation) {
                foreach ($model->$relation()->get() as $item) {
                    $item->delete();
                }
            }
        });

    }

}