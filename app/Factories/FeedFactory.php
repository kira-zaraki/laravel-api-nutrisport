<?php

namespace App\Factories;

use Exception;

class FeedFactory
{

    public static function make($type)
    {

        return match ($type) {
            'json' => new JsonFeedGenerator(),
            'xml' => new XmlFeedGenerator(),
            default => throw new Exception('Feed type not supported')
        };

    }

}