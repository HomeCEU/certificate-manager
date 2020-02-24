<?php declare(strict_types=1);


namespace HomeCEU\Tests\DocumentRender;


use HomeCEU\Render\Partial;
use HomeCEU\Render\Renderer;
use PHPUnit\Framework\TestCase;

class PTRenderTest extends TestCase
{
    private $dto = [
        'course'    => [
            'name'   => 'A test course',
            'hours'  => 5.5,
            'format' => 'Live',
        ],
        'student'   => [
            'firstName' => 'Student',
            'lastName'  => 'Lastname',
            'licenses'  => [
                'number_5e41ad3a0d149' => [
                    'state'  => 'TX',
                    'type'   => 'PT',
                    'number' => 'number_5e41ad3a0d149',
                ],
            ],
        ],
        'approvals' => [
            'pt_pta' => [
                [
                    'state'     => 'TX',
                    'category'  => 'Category 1',
                    'status'    => 'approved',
                    'code'      => '098765',
                    'statement' => 'An approval statement',
                    'hours'     => 4.25,
                ],
                [
                    'state'     => 'FL',
                    'category'  => 'Category 2',
                    'status'    => 'approval pending',
                    'code'      => '123456',
                    'statement' => 'A different approval statement',
                    'hours'     => 3.5,
                ],
            ],
        ],
    ];

    public function testPTSubTemplate(): void
    {
        $partial = new Partial(
            'pt_pta',
            file_get_contents(__DIR__ . '/../../temp_templates/accreditation_partials/pt_pta.template')
        );
        $certificate = Renderer::create();

        $certificate->setTemplate('{{> pt_pta }}');
        $certificate->addPartial($partial);

        $this->assertStringContainsString(
            "TX: Category 1 approved 098765 An approval statement; " .
            "FL: Category 2 approval pending 123456 A different approval statement", $certificate->render($this->dto)
        );
    }
}
