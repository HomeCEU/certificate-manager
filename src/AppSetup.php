<?php declare(strict_types=1);


namespace HomeCEU;


final class AppSetup
{
    public static function copyConfigFiles()
    {
        self::copyDbConfig();
        self::copyPhpUnitConfig();
    }

    protected static function copyDbConfig()
    {
        $dbConfigPath = __DIR__ . '/../config/local/';
        $dbConfigSamplePath = __DIR__ . '/../config/sample/';

        if (!is_file($dbConfigPath . 'db_config.php')) {
            if (!is_dir($dbConfigPath)) {
                mkdir($dbConfigPath);
            }
            copy($dbConfigSamplePath . 'db_config.sample.php', $dbConfigPath . 'db_config.php');
        }
    }

    protected static function copyPhpUnitConfig()
    {
        $phpUnitConfigPath = __DIR__ . '/../phpunit.xml';
        $phpUnitDistConfigPath = __DIR__ . '/../phpunit.xml.dist';

        if (!is_file($phpUnitConfigPath)) {
            copy($phpUnitDistConfigPath, $phpUnitConfigPath);
        }
    }
}
