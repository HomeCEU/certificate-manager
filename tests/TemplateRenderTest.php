<?php declare(strict_types=1);


namespace HomeCEU\Certificate\Tests;


use HomeCEU\Certificate\Renderer;
use LightnCandy\Flags;
use LightnCandy\LightnCandy;
use PHPUnit\Framework\TestCase;

class TemplateRenderTest extends TestCase
{
    private $renderer;

    protected function setUp(): void
    {
        $this->renderer = new Renderer();
    }

    public function testLightncandy(): void
    {
        $this->renderer->setTemplate("Hello, {{ name }}!");

        $name = 'Dan';
        $this->assertEquals("Hello, {$name}!", $this->render(['name' => $name]));
    }

    public function testLoop(): void
    {
        $names = ['test_1', 'test_2', 'test_3'];
        $template = '{{#each names}}{{this}}{{/each}}';

        $this->renderer->setTemplate($template);

        $this->assertEquals(implode('', $names), $this->render(['names' => $names]));
    }

    public function testSetFlags(): void
    {
        $names = ['test_1', 'test_2', 'test_3'];
        $template = '{{#each names}}{{this}}{{/each}}';

        $this->renderer->setTemplate($template);
        $this->renderer->setFlags(Flags::FLAG_BESTPERFORMANCE);

        $this->assertEquals('', $this->render(['names' => $names]));

        $this->renderer->setFlags(Flags::FLAG_HANDLEBARSJS);
        $this->assertEquals(implode('', $names), $this->render(['names' => $names]));
    }

    public function testRenderPartial(): void
    {
        $template = '{{> partial_test }}';
        $partial = '{{name}}';

        $php = LightnCandy::compile($template, [
            'partials' => ['partial_test' => $partial]
        ]);

        $renderer = LightnCandy::prepare($php);
        $this->assertEquals('dan', $renderer(['name' => 'dan']));
    }

    protected function render(array $data): ?string
    {
        return $this->renderer->render($data);
    }
}
