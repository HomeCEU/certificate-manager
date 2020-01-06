<?php declare(strict_types=1);


namespace HomeCEU\Template;


class Template
{
    private $id;
    private $name;
    private $template;

    public function __construct(string $id, string $name, string $template)
    {
        $this->id = $id;
        $this->name = $name;
        $this->template = $template;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRawTemplate(): string
    {
        return $this->template;
    }
}
