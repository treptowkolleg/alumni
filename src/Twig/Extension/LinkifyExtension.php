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

            // Entferne das letzte Satzzeichen aus der URL und dem Linktext
            $urlWithoutPunctuation = rtrim($url, '.,!?');
            $linkText = preg_replace('~https?://~', '', $matches[1]);
            $linkTextWithoutPunctuation = rtrim($linkText, '.,!?');

            // Erstelle den Link-Tag mit der URL ohne Satzzeichen
            $finalLink = '<a href="' . $urlWithoutPunctuation . '" target="_blank" rel="noopener noreferrer">' . $linkTextWithoutPunctuation . '</a>';

            // Wenn es ein Satzzeichen gab, hänge es nach dem Link-Tag an den Text
            if ($matches[4]) {
                return $finalLink . $matches[4];
            }

            // Wenn kein Satzzeichen, gebe nur den Link aus
            return $finalLink;
        }, $text);
    }
}