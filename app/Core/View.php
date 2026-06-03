<?php

declare(strict_types=1);

namespace App\Core;

use Smarty;

final class View
{
    private Smarty $smarty;

    public function __construct(string $templateDir, array $config)
    {
        // Создаём экземпляр Smarty и настраиваем папки
        // * TemplateDir — где лежат .tpl файлы
        // * CompileDir — куда Smarty записывает скомпилированные PHP-шаблоны
        // * CacheDir — кеш рендеринга (по умолчанию выключен в dev)
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir($templateDir);
        $this->smarty->setCompileDir($config['compile_dir']);
        $this->smarty->setCacheDir($config['cache_dir']);
        // Автоматическое экранирование HTML по умолчанию — безопаснее для вывода данных
        $this->smarty->escape_html = true;

        /**
         * Поведение кеша и компиляции
         * - setCompileCheck(true) — при изменении .tpl Smarty заново компилирует шаблон
         * - setCaching(OFF) — отключаем кеш рендеринга для быстрого цикла разработки
         *   (в продакшне можно включить кеш и использовать другой подход к bust-кешу).
         */
        $this->smarty->setCompileCheck(true);
        $this->smarty->setCaching(Smarty::CACHING_OFF);

        /**
         * Версионирование ассетов (css/js)
         * Логика: смотрим время модификации built-файлов в `public/assets` и берем максимальное
         * значение — оно используется как `asset_version`, который добавляется к URL,
         * чтобы принудить браузеры перескачать изменённые файлы.
         */
        $assetVersion = (string) time();
        $publicPath = $config['public_path'] ?? null;
        if ($publicPath) {
            $css = $publicPath . '/assets/css/app.css';
            $js = $publicPath . '/assets/js/app.js';
            $mtimes = [];
            if (file_exists($css)) {
                $mtimes[] = filemtime($css);
            }
            if (file_exists($js)) {
                $mtimes[] = filemtime($js);
            }
            if (!empty($mtimes)) {
                $assetVersion = (string) max($mtimes);
            }
        }

        $this->smarty->assign('asset_version', $assetVersion);
    }

    public function render(string $template, array $data = []): string
    {
        // Простая обёртка: присваиваем все переданные данные в Smarty,
        // затем возвращаем сгенерированный HTML как строку.
        foreach ($data as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        // fetch возвращает сгенерированный HTML (не выводит напрямую).
        return $this->smarty->fetch($template);
    }
}
