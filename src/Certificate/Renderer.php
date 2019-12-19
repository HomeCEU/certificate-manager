<?php declare(strict_types=1);


namespace HomeCEU\Certificate;


use HomeCEU\Certificate\Exception\NonPartialException;
use HomeCEU\Certificate\Exception\NoTemplateProvidedException;
use LightnCandy\Flags;
use LightnCandy\LightnCandy;

class Renderer
{
    /** @var string */
    private $template;
    /** @var Partial[] */
    private $partials = [];
    /** @var int */
    private $flags = Flags::FLAG_HANDLEBARS;

    public function setFlags(int $flags): void
    {
        $this->flags = $flags;
    }

    public function addPartial(Partial $partial): void
    {
        $this->partials[] = $partial;
    }

    public function setPartials(array $partials): void
    {
        $this->clearPartials();

        foreach ($partials as $partial) {
            if (!($partial instanceof Partial)) {
                throw new NonPartialException("You must provide an array of Partial(s)");
            }
            $this->addPartial($partial);
        }
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    public function render(array $data): ?string
    {
        if (empty($this->template)) {
            throw new NoTemplateProvidedException('Cannot render template, no template was provided.');
        }
        return $this->renderCompiledTemplate($this->compileTemplate(), $data);
    }

    private function compileTemplate()
    {
        return LightnCandy::compile($this->template, $this->buildOptions());
    }

    private function renderCompiledTemplate(string $compiledTemplate, array $data): ?string
    {
        $file = $this->saveCompiledTemplateToTempFile($compiledTemplate);

        $fn = include($file);
        unlink($file);

        return trim($fn($data));
    }

    private function saveCompiledTemplateToTempFile(string $compiledTemplate): string
    {
        $fileName = tempnam(sys_get_temp_dir(), 'cert_');
        file_put_contents($fileName, "<?php {$compiledTemplate}");

        return $fileName;
    }

    protected function buildOptions(): array
    {
        $options = ['flags' => $this->flags | Flags::FLAG_ERROR_EXCEPTION];

        if (!empty($this->partials)) {
            $options = array_merge($options, $this->buildPartialsOption());
        }
        return $options;
    }

    private function buildPartialsOption()
    {
        $partials = [];
        foreach ($this->partials as $partial) {
            $partials[$partial->name] = $partial->template;
        }
        return ['partials' => $partials];
    }

    private function clearPartials(): void
    {
        $this->partials = [];
    }
}
