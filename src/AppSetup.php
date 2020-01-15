<?php declare(strict_types=1);


namespace HomeCEU;


use HomeCEU\Connection\MysqlPDOConnection;

final class AppSetup
{
    const LOCAL_CONFIG_PATH = __DIR__ . '/../config/local/';

    public static function copyConfigFiles()
    {
        self::copyPhpUnitConfig();
        self::copyDbConfig();
        self::createDbSchema();
    }

    protected static function copyDbConfig()
    {
        $dbConfigSamplePath = __DIR__ . '/../config/sample/';

        if (!is_file(self::LOCAL_CONFIG_PATH . 'db_config.php')) {
            if (!is_dir(self::LOCAL_CONFIG_PATH)) {
                mkdir(self::LOCAL_CONFIG_PATH);
            }
            copy($dbConfigSamplePath . 'db_config.sample.php', self::LOCAL_CONFIG_PATH . 'db_config.php');
        }
    }

    protected static function copyPhpUnitConfig()
    {
        $phpUnitConfigPath     = __DIR__ . '/../phpunit.xml';
        $phpUnitDistConfigPath = __DIR__ . '/../phpunit.xml.dist';

        if (!is_file($phpUnitConfigPath)) {
            copy($phpUnitDistConfigPath, $phpUnitConfigPath);
        }
    }

    protected static function createDbSchema()
    {
        $sql = file_get_contents(__DIR__ . '/schema.sql');

        $config = include(self::LOCAL_CONFIG_PATH . 'db_config.php');
        $pdo    = MysqlPDOConnection::createFromConfig($config['mysql']);

        $pdo->exec($sql);
    }
}
