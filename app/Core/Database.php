<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

final class Database
{
    /**
     * Класс-обёртка для подключения к базе данных через PDO.
     *
     * Используется в `public/index.php` и в скриптах сидирования.
     * Метод `connect` возвращает готовый объект PDO с настройками ошибок
     * и режимом получения ассоциативных массивов по умолчанию.
     */
    public static function connect(array $config): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        return new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
}
