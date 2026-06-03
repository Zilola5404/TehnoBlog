<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /**
     * Маршрутизатор приложения — хранит GET-маршруты и выполняет диспетчеризацию.
     *
     * Простая реализация: поддерживает регистрацию маршрутов с параметрами
     * в виде `{slug}` и сопоставляет их регулярными выражениями.
     */
    private array $routes = [];

    public function __construct(private readonly View $view) {}

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][] = [$this->compile($path), $handler];
    }

    public function dispatch(string $method, string $path): void
    {
        foreach ($this->routes[$method] ?? [] as [$route, $handler]) {
            if (preg_match($route, $path, $matches) !== 1) {
                continue;
            }

            // Извлекаем именованные параметры маршрута (по имени группы)
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            // Вызываем обработчик и выводим результат (контроллер возвращает HTML)
            echo $handler($params);
            return;
        }

        http_response_code(404);
        echo $this->view->render('404.tpl', ['title' => 'Страница не найдена']);
    }

    private function compile(string $path): string
    {
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)}#', '(?P<$1>[a-z0-9-]+)', $path);

        return '#^' . $pattern . '$#';
    }
}
