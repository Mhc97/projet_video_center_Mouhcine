<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class VideoExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('youtube_id', [$this, 'extractYoutubeId']),
        ];
    }

    public function extractYoutubeId(?string $url): ?string
    {
        if (!$url) return null;
        
        // Format embed
        if (str_contains($url, '/embed/')) {
            preg_match('/\/embed\/([^?&]+)/', $url, $matches);
            return $matches[1] ?? null;
        }
        
        // Format watch?v=
        if (str_contains($url, 'watch?v=')) {
            preg_match('/watch\?v=([^&]+)/', $url, $matches);
            return $matches[1] ?? null;
        }
        
        // Format youtu.be/
        if (str_contains($url, 'youtu.be/')) {
            preg_match('/youtu\.be\/([^?&]+)/', $url, $matches);
            return $matches[1] ?? null;
        }
        
        return null;
    }
}