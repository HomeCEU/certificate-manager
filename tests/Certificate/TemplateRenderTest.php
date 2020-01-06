<?php declare(strict_types=1);


namespace HomeCEU\Tests\Certificate;


use HomeCEU\Certificate\Exception\NonPartialException;
use HomeCEU\Certificate\Exception\NoTemplateProvidedException;
use HomeCEU\Certificate\Partial;
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
        $names    = ['test_1', 'test_2', 'test_3'];
        $template = '{{#each names}}{{this}}{{/each}}';

        $this->certificate->setTemplate($template);

        $this->assertEquals(implode('', $names), $this->render(['names' => $names]));
    }

    public function testLoopWithNamedValue(): void
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

        $matches = RenderHelper::extractExpectedPartialsFromTemplate($template);
        $this->assertEquals(['partial_one', 'partial_two'], $matches);
    }

    public function testSetFlags(): void
    {
        $names    = ['test_1', 'test_2', 'test_3'];
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
        $this->certificate->addPartial(new Partial('partial_test', $partial));

        $name = 'Jules Winfield';
        $this->assertEquals($name, $this->render(['name' => $name]));
    }

    public function testSetPartials(): void
    {
        $template = '{{> partial_test_1 }} {{> partial_test_2}}';

        $this->certificate->setTemplate($template);
        $this->certificate->setPartials([
                new Partial('partial_test_1', '{{name}}'),
                new Partial('partial_test_2', '{{age}}')
        ]);
        $data = [
            'name' => 'Tester',
            'age'  => '99'
        ];
        $this->assertEquals("{$data['name']} {$data['age']}", $this->certificate->render($data));
    }

    public function testNoTemplateProvidedThrowException(): void
    {
        $this->expectException(NoTemplateProvidedException::class);
        $this->certificate->render([]);
    }

    public function testSetPartialsWithArrayOfNonPartialsThrowsException(): void
    {
        $this->expectException(NonPartialException::class);
        $this->certificate->setPartials(['not a partial instance']);
    }

    public function testRenderPartialLoop(): void
    {
        $data     = [
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

        $ptPartial  =
            "{{#with approvals.pt}}PT: {{#each states as |state|}}{{ state.name }}, {{ state.code }}; {{/each}}{{/with}}";
        $atcPartial =
            "{{#with approvals.atc}}ATC: {{#each states as |state|}}{{ state.name }}, {{ state.code }}; {{/each}}{{/with}}";

        $this->certificate->setTemplate($template);
        $this->certificate->addPartial(new Partial('pt_partial', $ptPartial));
        $this->certificate->addPartial(new Partial('atc_partial', $atcPartial));

        $expected = 'Test Course taken by: Test Student PT: Texas, TX; Florida, FL;';
        $this->assertEquals($expected, $this->certificate->render($data));
    }

    protected function render(array $data): ?string
    {
        return $this->certificate->render($data);
    }
}
