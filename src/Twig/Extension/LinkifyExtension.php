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
        $pattern = '~(?<!href=["\'])((https?://)?(www\.)?[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}[^\s<]*)~i';
        $replacement = '<a href="http${2}${3}$1" target="_blank" rel="noopener noreferrer">$1</a>';

        return preg_replace_callback($pattern, function($matches) {
            // Falls der Link mit www. beginnt, füge "http://" hinzu
            if (empty($matches[2])) {
                return '<a href="http://' . $matches[1] . '" target="_blank" rel="noopener noreferrer">' . $matches[1] . '</a>';
            }
            // Ansonsten behalte das bestehende Protokoll
            return '<a href="' . $matches[0] . '" target="_blank" rel="noopener noreferrer">' . $matches[0] . '</a>';
        }, $text);
    }
}