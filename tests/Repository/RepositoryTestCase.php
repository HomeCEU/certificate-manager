<?php


namespace HomeCEU\Tests\Repository;


use HomeCEU\Connection\MysqlPDOConnection;
use PHPStan\Testing\TestCase;

class RepositoryTestCase extends TestCase
{
    protected $conn;

    protected function setUp(): void
    {
        $config = include(__DIR__ . '/../../config/local/db_config.php');

        $this->conn = MysqlPDOConnection::createFromConfig($config['mysql']);
        $this->conn->beginTransaction();
    }

    protected function tearDown(): void
    {
        if ($this->conn->inTransaction()) {
            $this->conn->rollBack();
        }
    }
}