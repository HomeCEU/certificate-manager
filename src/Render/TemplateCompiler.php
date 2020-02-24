<?php


namespace HomeCEU\Render;


use LightnCandy\LightnCandy;

class TemplateCompiler
{
    private $template;
    private $options = [];

    public static function create(): self
    {
        return new self();
    }

    public function withTemplate(string $template): self
    {
        $this->template = $template;
        return $this;
    }

    public function compile(): ?string
    {
        return LightnCandy::compile($this->template, $this->options);
    }
}