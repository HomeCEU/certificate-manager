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

    public function addPartial(Partial $partial): void
    {
        $this->partials[$partial->name] = $partial->template;
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    public function render(array $data): ?string
    {
        return $this->renderCompiledTemplate($this->compileTemplate(), $data);
    }

    protected function compileTemplate()
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
            $options = array_merge($options, ['partials' => $this->partials]);
        }
        return $options;
    }
}
