<?php declare(strict_types=1);


namespace HomeCEU\Certificate;


use LightnCandy\Flags;
use LightnCandy\LightnCandy;

class Renderer
{
    private $template;
    private $flags = Flags::FLAG_HANDLEBARSJS;

    public function setFlags(int $flags): void
    {
        $this->flags = $flags;
    }

    public function setTemplate(string $template)
    {
        $this->template = $template;
    }

    public function render(array $data): ?string
    {
        $compiledTemplate = LightnCandy::compile($this->template, ['flags' => $this->flags]);
        $renderer = LightnCandy::prepare($compiledTemplate);

        return $renderer($data);
    }
}
