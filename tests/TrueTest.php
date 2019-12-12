<?php declare(strict_types=1);


namespace HomeCEU\Certificate\Tests;


use PHPStan\Testing\TestCase;

class TrueTest extends TestCase
{
    public function testTrue(): void
    {
        $this->assertTrue(true);
    }
}
