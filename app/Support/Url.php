<?php

declare(strict_types=1);

namespace App\Support;

final class Url
{
    public static function currentWith(array $params): string
    {
        // Формируем текущий URI с заменой/добавлением параметров из $params.
        // Удобно для пагинации и сортировки — контроллеры используют этот хелпер.
        $query = array_merge($_GET, $params);
        $query = array_filter($query, static fn($value) => $value !== null && $value !== '');

        return strtok($_SERVER['REQUEST_URI'], '?') . ($query ? '?' . http_build_query($query) : '');
    }
}
