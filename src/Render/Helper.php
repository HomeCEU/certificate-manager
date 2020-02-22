<?php declare(strict_types=1);


namespace HomeCEU\Render;


class Helper
{
    public $name;
    public $func;

    public function __construct(string $name, callable $func)
    {
        $this->name = $name;
        $this->func = $func;
    }
}
