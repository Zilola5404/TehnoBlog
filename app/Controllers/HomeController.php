<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\Category;
use PDO;

final class HomeController
{
    /**
     * Контроллер главной страницы.
     * Возвращает рендер шаблона `home.tpl` с секциями категорий и последними постами.
     */
    public function __construct(
        private readonly View $view,
        private readonly PDO $pdo
    ) {}

    public function index(): string
    {
        $categoryModel = new Category($this->pdo);

        return $this->view->render('home.tpl', [
            'title' => 'ТехноБлог — статьи о разработке, дизайне и технологиях',
            'metaDescription' => 'Практический блог об ИТ, интерфейсах, продуктовых решениях, дизайне и развитии цифровых сервисов.',
            'sections' => $categoryModel->withRecentPosts(3),
        ]);
    }
}
