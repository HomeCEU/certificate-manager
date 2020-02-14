<?php declare(strict_types=1);


namespace HomeCEU\Tests\Repository;


use HomeCEU\Repository\TemplateRepository;
use HomeCEU\Template\Template;

/**
 * Class TemplateStorageTest
 * @author Dan McAdams
 * @package HomeCEU\Tests\Integration
 *
 * @group functional
 */
class TemplateStorageTest extends RepositoryTestCase
{
    const NAME         = "test-name";
    const CONSTANT     = 'Test Name';
    const BODY = 'raw {{ template }}';

    protected $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new TemplateRepository($this->conn);
    }

    public function testSaveTemplate(): void
    {
        $template = $this->createTestTemplate();
        $this->repo->save($template);

        $foundTemplate = $this->repo->findById($template->id);
        $this->assertEquals($template->constant, $foundTemplate->constant);
    }

    public function testUpdateTemplate(): void
    {
        $template = $this->createTestTemplate();
        $this->repo->save($template);

        $template->constant = 'new_constant';
        $this->repo->save($template);

        $this->assertEquals('new_constant', $this->repo->findById($template->id)->constant);
    }

    public function testRemoveTemplate(): void
    {
        $template = $this->createTestTemplate();
        $this->repo->save($template);

        $this->repo->remove($template->id);

        $this->assertNull($this->repo->findById($template->id));
    }

    public function testTemplateNotFoundReturnNull(): void
    {
        $template = $this->repo->findById('not_a_real_id');
        $this->assertNull($template);
    }

    private function createTestTemplate(): Template
    {
        return $this->repo->create(self::NAME, self::CONSTANT, self::BODY);
    }
}
