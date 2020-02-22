<?php declare(strict_types=1);


namespace HomeCEU\Tests\DocumentRender;


use HomeCEU\Render\Renderer;
use PHPUnit\Framework\TestCase;

class RenderTestCase extends TestCase
{
    protected $renderer;

    protected function setUp(): void
    {
        $this->renderer = new Renderer();
    }
}
