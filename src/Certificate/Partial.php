<?php declare(strict_types=1);


namespace HomeCEU\Certificate;


class Partial
{
    public $name;
    public $template;

    public function __construct(string $name, string $template)
    {
        $this->name = $name;
        $this->template = $template;
    }
}
