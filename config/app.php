<?php

declare(strict_types=1);

return [
    'templates' => dirname(__DIR__) . '/templates',
    'smarty' => [
        'compile_dir' => dirname(__DIR__) . '/var/templates_c',
        'cache_dir' => dirname(__DIR__) . '/var/cache',
    ],
    // Путь к публичным ассетам (используется для вычисления версии ассетов)
    'public_path' => dirname(__DIR__) . '/public',
];
