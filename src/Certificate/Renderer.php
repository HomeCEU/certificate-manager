<?php declare(strict_types=1);


namespace HomeCEU\Certificate;


use LightnCandy\Flags;
use LightnCandy\LightnCandy;

class Renderer
{
    private $template;
    private $partials = [];
    private $flags = Flags::FLAG_HANDLEBARS;

    public function setFlags(int $flags): void
    {
        $this->flags = $flags;
    }

    public function addPartial(string $name, string $partial): void
    {
        $this->partials[$name] = $partial;
    }

    public function setTemplate(string $template)
    {
        $this->template = $template;
    }

    public function render(array $data): ?string
    {
        $options = ['flags' => $this->flags];

        if (!empty($this->partials)) {
            $options = array_merge($options, ['partials' => $this->partials]);
        }
        $compiledTemplate = LightnCandy::compile($this->template, $options);
        $renderer = LightnCandy::prepare($compiledTemplate);

        return $renderer($data);
    }
}
