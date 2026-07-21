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

function rarsm_db_driver(): string
{
    $pdo = rarsm_db();
    if (!$pdo instanceof PDO) {
        return '';
    }

    try {
        return strtolower((string) $pdo->getAttribute(PDO::ATTR_DRIVER_NAME));
    } catch (Throwable $exception) {
        return '';
    }
}

function rarsm_db_is_pgsql(): bool
{
    return rarsm_db_driver() === 'pgsql';
}

function rarsm_db_is_mysql(): bool
{
    return rarsm_db_driver() === 'mysql';
}

function rarsm_db_insert_and_get_id(PDO $pdo, string $sql, array $parameters = []): ?int
{
    try {
        if (rarsm_db_is_pgsql()) {
            $statement = $pdo->prepare(rtrim($sql) . ' RETURNING id');
            $statement->execute($parameters);
            $insertedId = $statement->fetchColumn();

            return $insertedId === false ? null : (int) $insertedId;
        }

        $statement = $pdo->prepare($sql);
        $statement->execute($parameters);
        $insertedId = $pdo->lastInsertId();

        return $insertedId === false || $insertedId === '' ? null : (int) $insertedId;
    } catch (Throwable $exception) {
        return null;
    }
}

function rarsm_db_has_table(string $table): bool
{
    static $cache = [];

    $normalizedTable = strtolower(trim($table));
    if ($normalizedTable === '') {
        return false;
    }

    if (array_key_exists($normalizedTable, $cache)) {
        return $cache[$normalizedTable];
    }

    $pdo = rarsm_db();
    if (!$pdo instanceof PDO) {
        $cache[$normalizedTable] = false;
        return false;
    }

    try {
        $sql = 'SELECT 1
                FROM information_schema.tables
                WHERE table_name = :table';

        if (rarsm_db_is_pgsql()) {
            $sql .= ' AND table_schema = current_schema()';
        } else {
            $sql .= ' AND table_schema = DATABASE()';
        }

        $sql .= ' LIMIT 1';

        $statement = $pdo->prepare($sql);
        $statement->execute([
            ':table' => $normalizedTable,
        ]);

        $cache[$normalizedTable] = (bool) $statement->fetchColumn();
    } catch (Throwable $exception) {
        $cache[$normalizedTable] = false;
    }

    return $cache[$normalizedTable];
}

function rarsm_db_has_column(string $table, string $column): bool
{
    static $cache = [];

    $normalizedTable = strtolower(trim($table));
    $normalizedColumn = strtolower(trim($column));

    if ($normalizedTable === '' || $normalizedColumn === '') {
        return false;
    }

    $cacheKey = $normalizedTable . '.' . $normalizedColumn;
    if (array_key_exists($cacheKey, $cache)) {
        return $cache[$cacheKey];
    }

    $pdo = rarsm_db();
    if (!$pdo instanceof PDO) {
        $cache[$cacheKey] = false;
        return false;
    }

    try {
        $sql = 'SELECT 1
                FROM information_schema.columns
                WHERE table_name = :table
                  AND column_name = :column';

        if (rarsm_db_is_pgsql()) {
            $sql .= ' AND table_schema = current_schema()';
        } else {
            $sql .= ' AND table_schema = DATABASE()';
        }

        $sql .= ' LIMIT 1';

        $statement = $pdo->prepare($sql);
        $statement->execute([
            ':table' => $normalizedTable,
            ':column' => $normalizedColumn,
        ]);

        $cache[$cacheKey] = (bool) $statement->fetchColumn();
    } catch (Throwable $exception) {
        $cache[$cacheKey] = false;
    }

    return $cache[$cacheKey];
}
