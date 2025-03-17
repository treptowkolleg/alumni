<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class HashtagExtension extends AbstractExtension
{

    public function getFilters(): array
    {
        return [
            new TwigFilter('hashtag', [$this, 'hashtagFilter']),
        ];
    }

    // Der Filter, der die Hashtags im Text verarbeitet
    public function hashtagFilter($text): array|string|null
    {
        // Regex für Hashtags
        return preg_replace_callback('/#(\w+)/', function($matches) {
            // Verlinkt den Hashtag zu einer Seite (z. B. /hashtag/{Hashtag})
            return '<a href="#' . $matches[1] . '">#' . $matches[1] . '</a>';
        }, $text);
    }

}