<?php declare(strict_types=1);


namespace HomeCEU\Render;


use HomeCEU\Render\Exception\NoTemplateProvidedException;
use LightnCandy\Flags;
use LightnCandy\LightnCandy;

class Renderer
{
    /** @var string */
    private $template;
    /** @var Partial[] */
    private $partials = [];
    /** @var Helper[] */
    private $helpers = [];
    /** @var int */
    private $flags = Flags::FLAG_HANDLEBARS;

    protected function __construct()
    {
        $this->resetPartials();
        $this->resetHelpers();
    }

    public static function create(): self
    {
        return new self();
    }

    public function setFlags(int $flags): void
    {
        $this->flags = $flags;
    }

    public function addPartial(Partial $partial): void
    {
        $this->partials[$partial->name] = $partial->template;
    }

    public function setPartials(array $partials): void
    {
        $this->resetPartials();
        foreach ($partials as $partial) {
            $this->addPartial($partial);
        }
    }

    public function addHelper(Helper $helper): void
    {
        $this->helpers[$helper->name] = $helper->func;
    }

    public function setHelpers(array $helpers): void
    {
        $this->resetHelpers();
        foreach ($helpers as $helper) {
            $this->addHelper($helper);
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

    public function renderCompiledTemplate(string $compiledTemplate, array $data): ?string
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
        return [
            'flags' => $this->flags | Flags::FLAG_ERROR_EXCEPTION,
            'helpers' => $this->helpers,
            'partials' => $this->partials
        ];
    }

    private function resetPartials(): void
    {
        $this->partials = [];
    }

    private function resetHelpers(): void
    {
        $this->helpers = [];
        $this->setDefaultHelpers();
    }

    private function setDefaultHelpers(): void
    {
        $ifComparisonHelper = Helpers::ifComparisonHelper();
        $this->helpers[$ifComparisonHelper->name] = $ifComparisonHelper->func;
    }
}
