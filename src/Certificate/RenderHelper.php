<?php declare(strict_types=1);


namespace HomeCEU\Certificate;


class RenderHelper
{
    public static function extractExpectedPartialsFrom(string $template): array
    {
        preg_match_all('/{{>([^\}}]+)}}/', $template, $matches);
        return !empty($matches[1]) ? array_map('trim', $matches[1]) : [];
    }
}
