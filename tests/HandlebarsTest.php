<?php declare(strict_types=1);


namespace HomeCEU\Certificate\Tests;


use LightnCandy\LightnCandy;
use LightnCandy\Runtime;
use PHPStan\Testing\TestCase;

/**
 * Class HandlebarsTest
 * A simple test to ensure our handlebars implementation will work
 * This test should be removed at some point
 *
 * @author  Dan McAdams
 * @package HomeCEU\Certificate\Tests
 */
class HandlebarsTest extends TestCase
{
    public function testLightncandy(): void
    {
        $php = LightnCandy::compile("Hello, {{ name }}!");

        /** @var callable $renderer */
        $renderer = LightnCandy::prepare($php);

        $name = 'Dan';
        $this->assertEquals("Hello, {$name}!", $renderer(['name' => $name]));
    }
}
