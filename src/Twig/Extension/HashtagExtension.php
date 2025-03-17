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
        $decodedText = html_entity_decode($text);

        // Regex für Hashtags, der auch Umlaute und andere Zeichen erfasst, aber Apostroph ausschließt
        return preg_replace_callback('/#([A-Za-z0-9ÄäÖöÜüß_-]+)/u', function($matches) {
            // Verlinkt den Hashtag zu einer Seite (z. B. /hashtag/{Hashtag})
            return '<a href="#' . urlencode($matches[1]) . '">#' . htmlspecialchars($matches[1]) . '</a>';
        }, $decodedText);
    }

}