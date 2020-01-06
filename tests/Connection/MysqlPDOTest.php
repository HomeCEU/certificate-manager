<?php declare(strict_types=1);


namespace HomeCEU\Tests\Connection;


use HomeCEU\Connection\MysqlPDOConnection;
use PHPStan\Testing\TestCase;

class MysqlPDOTest extends TestCase
{
    public function testCreateFromConfiguration(): void
    {
        $config = include(__DIR__ . '/../../config/local/db_config.php');
        dump($config['mysql']);

        $pdo = MysqlPDOConnection::createFromConfig($config['mysql']);
        $this->assertInstanceOf(\PDO::class, $pdo);
    }
}
