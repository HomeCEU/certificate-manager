<?php declare(strict_types=1);


namespace HomeCEU\Certificate;


class TemplateHelpers
{
    public static function getIfComparisonHelper(): Helper
    {
        return new Helper('if', function ($arg1, $arg2, $return) {
            return $arg1 == $arg2 ? $return : '';
        });
    }
}
