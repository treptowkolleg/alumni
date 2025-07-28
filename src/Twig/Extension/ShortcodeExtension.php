<?php

namespace App\Twig\Extension;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ShortcodeExtension extends AbstractExtension
{
    private CacheManager $cacheManager;

    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('shortcodes', [$this, 'replaceShortcodes'], ['is_safe' => ['html']]),
        ];
    }

    public function replaceShortcodes(string $text): string
    {
        return preg_replace_callback(
            '/\[img="([^"]+)"(?:,"([^"]*)")?(?:,(\d+))?(?:,(\d+))?]/',
            function ($matches) {
                $filename = htmlspecialchars($matches[1], ENT_QUOTES, 'UTF-8');
                $alt = isset($matches[2]) && $matches[2] !== '' ? htmlspecialchars($matches[2], ENT_QUOTES, 'UTF-8') : $filename;
                $width = isset($matches[3]) ? intval($matches[3]) : null;
                $height = isset($matches[4]) ? intval($matches[4]) : null;

                $originalPath = '/images/user_uploads/' . $filename;
                $filteredSrc = $this->cacheManager->getBrowserPath($originalPath, 'user_images');

                $attrs = [
                    'src="' . $filteredSrc . '"',
                    'alt="' . $alt . '"',
                ];


                $attrs[] = 'class="img-fluid img-thumbnail rounded-4"';


                return '<img ' . implode(' ', $attrs) . '><div class="text-muted small px-3 my-1">'.$alt.'</div>';
            },
            $text
        );
    }

}