<?php declare(strict_types=1);


namespace HomeCEU\Connection;


use \PDO;

class MysqlPDOConnection
{
    public static function createFromConfig(array $config): PDO
    {
        $pdo = new PDO(
            "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']}",
            $config['user'],
            $config['password']
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }
}
