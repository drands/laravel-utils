<?php

use MediaEmbed\MediaEmbed;

if (!function_exists('media_embed')) {
    function media_embed($id, $host, $attributes = [], $params = []) {
        $MediaEmbed = app(MediaEmbed::class);

        $MediaObject = $MediaEmbed->parseId($id, $host);
        
        if (!$MediaObject) {
            return '';
        }
        
        if (!empty($attributes)) {
            $MediaObject->setAttribute($attributes);
        }

        if (!empty($params)) {
            $MediaObject->setParam($params);
        }

        return $MediaObject->getEmbedCode();
    }
}
