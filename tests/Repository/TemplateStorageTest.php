<?php declare(strict_types=1);


namespace HomeCEU\Tests\Repository;


use HomeCEU\Connection\MysqlPDOConnection;
use HomeCEU\Template\Template;
use HomeCEU\Template\Repository as TemplateRepository;
use PHPUnit\Framework\TestCase;

/**
 * Class TemplateStorageTest
 * @author Dan McAdams
 * @package HomeCEU\Tests\Integration
 *
 * @group functional
 */
class TemplateStorageTest extends TestCase
{
    const NAME         = "test-name";
    const CONSTANT     = 'Test Name';
    const BODY = 'raw {{ template }}';
    private $repo;
    private $conn;

    protected function setUp(): void
    {
        $config = include(__DIR__ . '/../../config/local/db_config.php');

        $this->conn = MysqlPDOConnection::createFromConfig($config['mysql']);
        $this->repo = new TemplateRepository($this->conn);

        $this->conn->beginTransaction();
    }

    protected function tearDown(): void
    {
        if ($this->conn->inTransaction()) {
            $this->conn->rollBack();
        }
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
