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
        $name = 'test_name';
        $rawTemplate = 'raw template';

        $savedTemplate = $this->repo->create($name, $rawTemplate);

        $this->assertInstanceOf(Template::class, $savedTemplate);

        $this->assertNotNull($savedTemplate->getId());
        $this->assertEquals($name, $savedTemplate->getName());
        $this->assertEquals($rawTemplate, $savedTemplate->getRawTemplate());
    }

    public function testSave(): void
    {
        $name = 'test_name';
        $rawTemplate = 'raw {{ template }}';

        $template = $this->repo->create($name, $rawTemplate);

        $this->repo->save($template);
        $foundTemplate = $this->repo->findById($template->getId());

        $this->assertEquals($template, $foundTemplate);
    }

    public function testTemplateNotFound(): void
    {
        $template = $this->repo->findById('not_a_real_id');
        $this->assertNull($template);
    }
}
