<?php declare(strict_types=1);


namespace HomeCEU\Certificate;


use LightnCandy\Flags;
use LightnCandy\LightnCandy;

class Renderer
{
    private $template;

    public function setTemplate(string $template)
    {
        $this->template = LightnCandy::compile($template, ['flags' => Flags::FLAG_HANDLEBARS]);
    }

    public function render(array $data): ?string
    {
        $renderer = LightnCandy::prepare($this->template);
        return $renderer($data);
    }
}
