<?php

declare(strict_types=1);

// Точка входа приложения — роутинг и подстановка зависимостей.
// Файл минимально инициализирует соединение с БД, view и регистрирует маршруты.
use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Controllers\PostController;
use App\Core\Database;
use App\Core\Router;
use App\Core\View;

require dirname(__DIR__) . '/vendor/autoload.php';

try {
    $config = require dirname(__DIR__) . '/config/app.php';
    $database = require dirname(__DIR__) . '/config/database.php';

    $pdo = Database::connect($database);
    $view = new View($config['templates'], $config['smarty']);

    $router = new Router($view);
    $router->get('/', [new HomeController($view, $pdo), 'index']);
    $router->get('/category/{slug}', [new CategoryController($view, $pdo), 'show']);
    $router->get('/post/{slug}', [new PostController($view, $pdo), 'show']);

    $router->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');
} catch (Throwable $exception) {
    error_log($exception->getMessage());
    http_response_code(500);
    echo 'Ошибка сервера';
}
