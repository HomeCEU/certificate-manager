<?php declare(strict_types=1);


namespace HomeCEU\Tests\DocumentRender;


use HomeCEU\Render\Template;
use HomeCEU\Render\TemplateBuilder;
use PHPUnit\Framework\TestCase;

class TemplateBuilderTest extends TestCase
{
    public function testCreateTemplate(): void
    {
        $template = TemplateBuilder::create()
                                   ->withName('Some Name')
                                   ->withConstant('some-constant')
                                   ->withBody('{{ template-body }}')
                                   ->withId('madeUpID')
                                   ->build();

        $this->assertInstanceOf(Template::class, $template);
        $this->assertInstanceOf(\DateTime::class, $template->createdOn);
        $this->assertInstanceOf(\DateTime::class, $template->updatedOn);
    }
}
