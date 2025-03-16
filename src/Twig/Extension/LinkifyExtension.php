<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class LinkifyExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('linkify', [$this, 'linkify'], ['is_safe' => ['html']]),
        ];
    }

    public function linkify($text): array|string|null
    {
        $pattern = '~(?<!href=["\'])((https?://|www\.)[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}[^\s<]*)~i';
        $replacement = '<a href="http$2$1" target="_blank" rel="noopener noreferrer">$1</a>';

        return preg_replace($pattern, $replacement, $text);
    }
}