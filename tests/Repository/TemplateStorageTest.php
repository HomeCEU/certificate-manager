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
        $constant = "test-name";
        $name = 'Test Name';
        $body = 'raw template';

        $savedTemplate = $this->repo->create($constant, $name, $body);

        $this->assertInstanceOf(Template::class, $savedTemplate);

        $this->assertNotNull($savedTemplate->id);
        $this->assertEquals($name, $savedTemplate->name);
        $this->assertEquals($body, $savedTemplate->body);
    }

    public function testSaveNewTemplate(): void
    {
        $constant = "test-name";
        $name = 'Test Name';
        $body = 'raw {{ template }}';

        $template = $this->repo->create($constant, $name, $body);

        $this->repo->save($template);
        $foundTemplate = $this->repo->findById($template->id);

        $this->assertEquals($template, $foundTemplate);
    }

    public function testTemplateNotFound(): void
    {
        $template = $this->repo->findById('not_a_real_id');
        $this->assertNull($template);
    }
}
