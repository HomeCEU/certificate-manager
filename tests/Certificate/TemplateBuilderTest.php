<?php declare(strict_types=1);


namespace HomeCEU\Tests\Certificate;


use HomeCEU\Template\Template;
use HomeCEU\Template\TemplateBuilder;
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
        $this->assertInstanceOf(\DateTime::class, $template->createdAt);
        $this->assertInstanceOf(\DateTime::class, $template->updatedOn);
    }
}
