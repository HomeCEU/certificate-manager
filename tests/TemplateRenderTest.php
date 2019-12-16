<?php declare(strict_types=1);


namespace HomeCEU\Certificate\Tests;


use HomeCEU\Certificate\Renderer;
use HomeCEU\Certificate\RenderHelper;
use LightnCandy\Flags;
use PHPUnit\Framework\TestCase;

class TemplateRenderTest extends TestCase
{
    private $certificate;

    protected function setUp(): void
    {
        $this->certificate = new Renderer();
    }

    public function testSimpleString(): void
    {
        $this->certificate->setTemplate("Hello, {{ name }}!");

        $name = 'Dan';
        $this->assertEquals("Hello, {$name}!", $this->render(['name' => $name]));
    }

    public function testLoop(): void
    {
        $names = ['test_1', 'test_2', 'test_3'];
        $template = '{{#each names}}{{this}}{{/each}}';

        $this->certificate->setTemplate($template);

        $this->assertEquals(implode('', $names), $this->render(['names' => $names]));
    }

    public function testNestedLoop(): void
    {
        $states   = [['name' => 'Texas'], ['name' => 'Florida'], ['name' => 'New Mexico']];
        $template = '{{#each states as |state|}}{{state.name}}{{/each}}';

        $this->certificate->setTemplate($template);

        $this->assertEquals(implode('', array_column($states, 'name')), $this->render(['states' => $states]));
    }

    public function testExtractPartials(): void
    {
        $template = 'some other text {{> partial_one }} {{ not a partial }} {{> partial_two }} some text';
        $this->certificate->setTemplate($template);

        $matches = RenderHelper::extractExpectedPartialsFrom($template);
        $this->assertEquals(['partial_one', 'partial_two'], $matches);
    }

    public function testSetFlags(): void
    {
        $names = ['test_1', 'test_2', 'test_3'];
        $template = '{{#each names}}{{this}}{{/each}}';

        $this->certificate->setTemplate($template);
        $this->certificate->setFlags(Flags::FLAG_BESTPERFORMANCE);

        $this->assertEquals('', $this->render(['names' => $names]));

        $this->certificate->setFlags(Flags::FLAG_HANDLEBARSJS);
        $this->assertEquals(implode('', $names), $this->render(['names' => $names]));
    }

    public function testRenderPartial(): void
    {
        $template = '{{> partial_test }}';
        $partial  = '{{name}}';

        $this->certificate->setTemplate($template);
        $this->certificate->addPartial('partial_test', $partial);

        $name = 'Jules Winfield';
        $this->assertEquals($name, $this->render(['name' => $name]));
    }

    protected function render(array $data): ?string
    {
        return $this->certificate->render($data);
    }
}
