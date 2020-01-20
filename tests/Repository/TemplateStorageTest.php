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

    public function testCreate(): void
    {
        $savedTemplate = $this->repo->create(self::CONSTANT, self::NAME, self::BODY);

        $this->assertInstanceOf(Template::class, $savedTemplate);

        $this->assertNotNull($savedTemplate->id);
        $this->assertEquals(self::NAME, $savedTemplate->name);
        $this->assertEquals(self::BODY, $savedTemplate->body);
    }

    public function testSaveTemplate(): void
    {
        $template = $this->repo->create(self::NAME, self::CONSTANT, self::BODY);

        $this->repo->save($template);
        $foundTemplate = $this->repo->findById($template->id);

        $this->assertEquals($template->id, $foundTemplate->id);
        $this->assertEquals(self::BODY, $foundTemplate->body);

        $newBody = 'a new {{ body }}';
        $foundTemplate->body = $newBody;

        $this->repo->save($foundTemplate);

        $foundTemplate = $this->repo->findById($template->id);
        $this->assertEquals($newBody, $foundTemplate->body);
    }

    public function testTemplateNotFound(): void
    {
        $template = $this->repo->findById('not_a_real_id');
        $this->assertNull($template);
    }
}
