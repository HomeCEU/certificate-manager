<?php declare(strict_types=1);


namespace HomeCEU\Tests\Repository;


use PHPUnit\Framework\TestCase;

class DTOStorageTest extends TestCase
{
    private $dto;

    protected function setUp(): void
    {
        $this->dto = json_encode($this->getDTOArray());
    }

    public function testSomething(): void
    {
        $this->assertEquals($this->getDTOArray(), $this->dto);
    }

    private function getDTOArray(): array
    {
        return [
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
    }
}
