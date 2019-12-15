<?php declare(strict_types=1);


namespace HomeCEU\Certificate\Tests;


use LightnCandy\Flags;
use LightnCandy\LightnCandy;
use PHPUnit\Framework\TestCase;

class TemplateRenderTest extends TestCase
{
    public function testLightncandy(): void
    {
        $php = LightnCandy::compile("Hello, {{ name }}!");

        /** @var callable $renderer */
        $renderer = LightnCandy::prepare($php);

        $name = 'Dan';
        $this->assertEquals("Hello, {$name}!", $renderer(['name' => $name]));
    }

    public function testLoop(): void
    {
        $names = ['test_1', 'test_2', 'test_3'];
        $template = '{{#each names}}{{this}}{{/each}}';

        $php = LightnCandy::compile($template, ['flags' => Flags::FLAG_HANDLEBARS]);
        $renderer = LightnCandy::prepare($php);

        $this->assertEquals(implode('', $names), $renderer(['names' => $names]));
    }
}
