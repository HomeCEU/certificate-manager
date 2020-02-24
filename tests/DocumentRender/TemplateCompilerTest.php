<?php


namespace HomeCEU\Tests\DocumentRender;


use HomeCEU\Render\Helpers;
use HomeCEU\Render\TemplateCompiler as Compiler;

class TemplateCompilerTest extends RenderTestCase
{
    const TEMP_FILE = __DIR__ . '/temp.template';
    private $compiler;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        if (is_file(self::TEMP_FILE)) {
            unlink(self::TEMP_FILE);
        }
    }

    public function testCompileTemplate(): void
    {
        $template = "{{ placeholder }}";
        $data = ['placeholder' => 'password'];

        $cTemplate = Compiler::create()
            ->withTemplate($template)
            ->withHelpers([Helpers::ifComparisonHelper()])
            ->compile();

        $rendered = $this->renderer->renderCompiledTemplate($cTemplate, $data);
        $this->assertEquals($data['placeholder'], $rendered);
    }
}