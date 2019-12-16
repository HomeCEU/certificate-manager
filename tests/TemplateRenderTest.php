<?php declare(strict_types=1);


namespace HomeCEU\Certificate\Tests;


use HomeCEU\Certificate\Renderer;
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
        $this->assertEquals("Hello, {$name}!", $this->renderer->render(['name' => $name]));
    }

    public function testLoop(): void
    {
        $names = ['test_1', 'test_2', 'test_3'];
        $template = '{{#each names}}{{this}}{{/each}}';

        $this->renderer->setTemplate($template);

        $this->assertEquals(implode('', $names), $this->renderer->render(['names' => $names]));
    }
}
