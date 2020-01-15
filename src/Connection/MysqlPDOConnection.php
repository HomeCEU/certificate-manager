<?php declare(strict_types=1);


namespace HomeCEU\Connection;


class MysqlPDOConnection
{
    public static function createFromConfig(array $config): \PDO
    {
        return new \PDO(
            "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']}",
            $config['user'],
            $config['password']
        );
    }
}
