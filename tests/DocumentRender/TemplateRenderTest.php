<?php declare(strict_types=1);


namespace HomeCEU\Tests\DocumentRender;


use HomeCEU\Render\Exception\NonPartialException;
use HomeCEU\Render\Exception\NoTemplateProvidedException;
use HomeCEU\Render\Helper;
use HomeCEU\Render\Partial;
use HomeCEU\Render\Renderer;
use HomeCEU\Render\RenderHelper;
use LightnCandy\Flags;
use PHPUnit\Framework\TestCase;

class TemplateRenderTest extends RenderTestCase
{
    public function testSimpleString(): void
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

    public function testLoopWithNamedValue(): void
    {
        $states = [['name' => 'Texas'], ['name' => 'Florida'], ['name' => 'New Mexico']];
        $template = '{{#each states as |state|}}{{state.name}}{{/each}}';

        $this->renderer->setTemplate($template);

        $this->assertEquals(implode('', array_column($states, 'name')), $this->render(['states' => $states]));
    }

    public function testExtractPartials(): void
    {
        $template = 'some other text {{> partial_one }} {{ not a partial }} {{> partial_two }} some text';
        $this->renderer->setTemplate($template);

        $matches = RenderHelper::extractExpectedPartialsFromTemplate($template);
        $this->assertEquals(['partial_one', 'partial_two'], $matches);
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

        $this->renderer->setTemplate($template);
        $this->renderer->addPartial(new Partial('partial_test', $partial));

        $name = 'Jules Winfield';
        $this->assertEquals($name, $this->render(['name' => $name]));
    }

    public function testSetPartials(): void
    {
        $template = '{{> partial_test_1 }} {{> partial_test_2}}';

        $this->renderer->setTemplate($template);
        $this->renderer->setPartials([
            new Partial('partial_test_1', '{{name}}'),
            new Partial('partial_test_2', '{{age}}')
        ]);
        $data = [
            'name' => 'Tester',
            'age'  => '99'
        ];
        $this->assertEquals("{$data['name']} {$data['age']}", $this->renderer->render($data));
    }

    public function testNoTemplateProvidedThrowException(): void
    {
        $this->expectException(NoTemplateProvidedException::class);
        $this->renderer->render([]);
    }

    public function testRenderPartialLoop(): void
    {
        $data = [
            'course'    => [
                'name' => 'Test Course'
            ],
            'student'   => [
                'name' => 'Test Student'
            ],
            'approvals' => [
                'pt' => ['states' => [['name' => 'Texas', 'code' => 'TX'], ['name' => 'Florida', 'code' => 'FL']]],
                'mt' => ['states' => [['name' => 'Ohio', 'code' => 'OH'], ['name' => 'New Mexico', 'code' => 'NM']]]
            ]
        ];
        $template = '{{course.name}} taken by: {{student.name}} {{>pt_partial}} {{>atc_partial}}';

        $ptPartial =
            "{{#with approvals.pt}}PT: {{#each states as |state|}}{{ state.name }}, {{ state.code }}; {{/each}}{{/with}}";
        $atcPartial =
            "{{#with approvals.atc}}ATC: {{#each states as |state|}}{{ state.name }}, {{ state.code }}; {{/each}}{{/with}}";

        $this->renderer->setTemplate($template);
        $this->renderer->addPartial(new Partial('pt_partial', $ptPartial));
        $this->renderer->addPartial(new Partial('atc_partial', $atcPartial));

        $expected = 'Test Course taken by: Test Student PT: Texas, TX; Florida, FL;';
        $this->assertEquals($expected, $this->renderer->render($data));
    }

    protected function render(array $data): ?string
    {
        return $this->renderer->render($data);
    }
}
