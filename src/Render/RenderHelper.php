<?php declare(strict_types=1);


namespace HomeCEU\Render;


class RenderHelper
{
    public static function extractExpectedPartialsFromTemplate(string $template): array
    {
        preg_match_all('/{{>([^\}}]+)}}/', $template, $matches);
        return !empty($matches[1]) ? array_map('trim', $matches[1]) : [];
    }
}
