<?php

namespace Drands\LaravelUtils\Traits;

use Illuminate\Support\Facades\Storage;

trait HasBuilder {

    /*
    protected Array $builders = ['body'];
    */

    protected static function bootHasBuilder(): void
    {
        static::saving(function ($model) {
            foreach ($model->builders as $builder) {
                if ($model->isDirty($builder)) {
                    //if field is translatable
                    if (in_array($builder, $model->translatable)) {
                        $oldContentAll = $model->getOriginal($builder) ?? [];
                        foreach ($oldContentAll as $locale => $oldContent) {
                            $newContent = $model->getTranslation($builder, $locale);
                            $model->deleteOldFiles($oldContent, $newContent);
                        }
                    } else {
                        $oldContent = $model->getOriginal($builder);
                        $newContent = $model->$builder;
                        $model->deleteOldFiles($oldContent, $newContent);
                    }
                }
            }
        });

        static::deleting(function ($model) {
            // delete media files
            foreach ($model->builders as $builder) {
                //if field is translatable
                if (in_array($builder, $model->translatable)) {
                    $contentAll = $model->getTranslations($builder);
                    foreach ($contentAll as $content) {
                        $model->deleteOldFiles($content, []);
                    }
                } else {
                    $model->deleteOldFiles($model->$builder, []);
                }
            }
        });
    }

    private function deleteOldFiles($oldContent, $newContent)
    {
        $oldMediaFiles = $this->extractMediaFiles($oldContent);
        $newMediaFiles = $this->extractMediaFiles($newContent);
        $deletedMediaFiles = array_diff($oldMediaFiles, $newMediaFiles);
        foreach ($deletedMediaFiles as $file) {
            if ($file && Storage::disk('public')->exists($file)) {
                Storage::disk('public')->delete($file);
            }
        }
    }

    private function extractMediaFiles($content)
    {
        $mediaFiles = [];

        foreach ($content as $block) {
            $mediaFiles[] = $this->processBlock($block);
        }

        return array_filter($mediaFiles); //remove nulls
    }

    private function processBlock(array $block): ?string
    {
        if ($this->isRow($block)) {
            foreach ($block['data']['row'] as $rowField) {
                $this->processBlock($rowField);
            }
        } elseif ($this->isGroup($block)) {
            foreach ($block['data']['group'] as $groupField) {
                $this->processBlock($groupField);
            }
        }

        if ($field = $this->isFile($block['data'])) {
            return $block['data'][$field] ?? null;
        }

        return null;
    }

    private function isRow(array $field): bool
    {
        return $field['type'] === 'Row';
    }

    private function isGroup(array $field): bool
    {
        return $field['type'] === 'Group';
    }

    private function isFile(array $field): bool|string
    {
        foreach ($field as $key => $value) {
            if (
                is_string($value)
                && !str_starts_with($value, '//')
                && !str_starts_with($value, 'http')
                && preg_match('#.+/.+\..+#', $value) === 1
            ) {
                return $key;
            }
        }
        return false;
    }
}