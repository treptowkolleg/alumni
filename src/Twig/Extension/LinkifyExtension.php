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
        return preg_replace_callback(
            '~(?<!href=["\'])(((https?://)|(www\.))[a-zA-Z0-9/?&=%._+-]*[a-zA-Z0-9/])~i',
            function ($matches) use ($text) {
                $fullMatch = $matches[0];

                // Extrahiere mögliche führende und abschließende Sonderzeichen
                // Die eigentliche URL sollte nicht mit ( beginnen oder mit ) enden, wenn diese zum Satz gehören
                $leading = '';
                $trailing = '';

                // Entferne ggf. führende öffnende Klammer, falls nicht Teil einer echten URL
                if (str_starts_with($fullMatch, '(')) {
                    $leading = '(';
                    $fullMatch = substr($fullMatch, 1);
                }

                // Entferne abschließende Satzzeichen/Klammern, die nicht zur URL gehören
                // Erlaubte URL-Zeichen am Ende: Buchstaben, Ziffern, Slash, Gleich, Prozent, Bindestrich, Punkt, Unterstrich
                // Alles andere wie ), ], !, ?, ,, ., ; am Ende wird als Trennzeichen betrachtet
                $trailingChars = '';
                while (strlen($fullMatch) > 0) {
                    $lastChar = substr($fullMatch, -1);
                    if (in_array($lastChar, ['.', ',', ';', '!', '?', ':', ')', ']', '"', '\''])) {
                        $trailingChars = $lastChar . $trailingChars;
                        $fullMatch = substr($fullMatch, 0, -1);
                    } else {
                        break;
                    }
                }
                $trailing = $trailingChars;

                // Stelle sicher, dass die URL nicht leer ist
                if ($fullMatch === '') {
                    return $leading . $matches[0] . $trailing; // Fallback: gib Original zurück
                }

                // Baue korrekte URL
                if (str_starts_with($fullMatch, 'www.')) {
                    $url = 'http://' . $fullMatch;
                } else {
                    $url = $fullMatch;
                }

                // Link-Text: entferne Protokoll
                $linkText = preg_replace('~https?://~', '', $fullMatch);

                // HTML-Escaping für Sicherheit (optional, aber empfohlen)
                $url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
                $linkText = htmlspecialchars($linkText, ENT_QUOTES, 'UTF-8');

                $finalLink = '<a href="' . $url . '" target="_blank" rel="noopener noreferrer">' . $linkText . '</a>';

                return $leading . $finalLink . $trailing;
            },
            $text
        );
    }
}