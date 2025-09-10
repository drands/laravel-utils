<?php

return [
    'label' => 'Clone Translations',
    'modal' => [
        'heading' => 'Clone translations for :label',
        'description' => 'This action will clone the translations from the selected language to the chosen languages for the selected fields. Existing translations in the target languages will be overwritten.',
        'actions' => [
            'clone' => [
                'label' => 'Clone',
            ],
        ],
    ],
    'notifications' => [
        'cloned' => [
            'title' => 'Translations Cloned',
        ],
    ],
    'form' => [
        'fromLocale' => [
            'label' => 'From translation',
        ],
        'toLocales' => [
            'label' => 'To translations',
        ],
        'fields' => [
            'label' => 'Fields to clone',
        ],
    ],
];
