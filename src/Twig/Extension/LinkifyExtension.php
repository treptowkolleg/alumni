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
        return preg_replace_callback('~(?<!href=["\'])((https?://)?(www\.)?[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}[^\s<]*)([.,!?])?~i', function($matches) {
            // Falls der Link mit www. beginnt, füge "http://" hinzu
            if (empty($matches[2])) {
                $url = 'http://' . $matches[1];
            } else {
                $url = $matches[0];
            }

            // Entferne den http:// oder https:// Teil aus dem Text
            $linkText = preg_replace('~https?://~', '', $matches[1]);

            // Entferne das letzte Satzzeichen aus dem Linktext, falls es vorhanden ist
            $linkTextWithoutPunctuation = rtrim($linkText, '.,!?');

            // Gib den Link ohne das Satzzeichen aus, aber behalte es im href
            return '<a href="' . $url . '" target="_blank" rel="noopener noreferrer">' . $linkTextWithoutPunctuation . '</a>' . ($matches[4] ? $matches[4] : '');
        }, $text);
    }
}