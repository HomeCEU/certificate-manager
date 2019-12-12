<?php declare(strict_types=1);


namespace HomeCEU\Certificate\Tests;


use HomeCEU\Certificate\App;
use PHPUnit\Framework\TestCase;

class RunTest extends TestCase
{
    public function testRuns(): void
    {
        $x = 'hello';
        $this->assertTrue(App::works());
    }
}
