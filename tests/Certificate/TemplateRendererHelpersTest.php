<?php declare(strict_types=1);


namespace HomeCEU\Tests\Certificate;


use HomeCEU\Certificate\Helper;

class TemplateRendererHelpersTest extends RenderTestCase
{
    private $templateWithIfHelper = '{{if status "test" "passed"}}
                                     {{if status "test_two" "will not show"}}';

    private $templateWithUpperHelper = '{{upper status}}';

    public function testDefaultIfComparisonHelper(): void
    {
        $this->renderer->setTemplate($this->templateWithIfHelper);

        $data = ['status' => 'test'];
        $this->assertEquals('passed', $this->renderer->render($data));
    }

    public function testSettingHelpersKeepsDefaults(): void
    {
        $this->renderer->setHelpers([]);

        $this->testDefaultIfComparisonHelper();
    }

    public function testAddHelper(): void
    {
        $this->renderer->setTemplate($this->templateWithUpperHelper);
        $this->renderer->addHelper($this->createStrUpperHelper());

        $data = ['status' => 'test'];
        $this->assertEquals('TEST', $this->renderer->render($data));
    }

    public function testSetHelpers(): void
    {
        $this->renderer->setTemplate($this->templateWithUpperHelper);
        $this->renderer->setHelpers([$this->createStrUpperHelper()]);

        $data = ['status' => 'test'];
        $this->assertEquals('TEST', $this->renderer->render($data));
    }

    private function createStrUpperHelper(): Helper
    {
        return new Helper('upper', function ($str) {
            return strtoupper($str);
        });
    }
}
