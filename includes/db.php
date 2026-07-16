<?php
declare(strict_types=1);

function rarsm_db(): ?PDO
{
    static $pdo = false;

    if ($pdo !== false) {
        return $pdo;
    }

    $config = [];
    $configPath = __DIR__ . '/config.php';

    if (is_file($configPath)) {
        $loaded = require $configPath;
        if (is_array($loaded)) {
            $config = $loaded;
        }
    }

    $dsn = (string) (($config['db']['dsn'] ?? '') ?: getenv('RARSM_DB_DSN') ?: '');
    $user = (string) (($config['db']['user'] ?? '') ?: getenv('RARSM_DB_USER') ?: '');
    $password = (string) (($config['db']['password'] ?? '') ?: getenv('RARSM_DB_PASSWORD') ?: '');

    if ($dsn === '') {
        $pdo = null;
        return $pdo;
    }

    try {
        $pdo = new PDO(
            $dsn,
            $user,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    } catch (Throwable $exception) {
        $pdo = null;
    }

    return $pdo;
}
