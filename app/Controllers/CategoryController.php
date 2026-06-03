<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\Category;
use App\Models\Post;
use App\Support\Url;
use PDO;

final class CategoryController
{
    private const PER_PAGE = 3;

    /**
     * Контроллер просмотра категории.
     * - Ищет категорию по slug, выводит список постов с пагинацией и сортировкой.
     */

    public function __construct(
        private readonly View $view,
        private readonly PDO $pdo
    ) {}

    public function show(array $params): string
    {
        $category = (new Category($this->pdo))->findBySlug($params['slug']);

        if (!$category) {
            http_response_code(404);
            return $this->view->render('404.tpl', ['title' => 'Категория не найдена']);
        }

        $sort = ($_GET['sort'] ?? 'date') === 'views' ? 'views' : 'date';
        $page = max(1, (int) ($_GET['page'] ?? 1));

        $postModel = new Post($this->pdo);
        $total = $postModel->countForCategory((int) $category['id']);
        $pages = max(1, (int) ceil($total / self::PER_PAGE));
        $page = min($page, $pages);
        $pageUrls = [];

        for ($i = 1; $i <= $pages; $i++) {
            $pageUrls[$i] = Url::currentWith(['page' => $i]);
        }

        return $this->view->render('category.tpl', [
            'title' => $category['name'] . ' — статьи и практические материалы',
            'metaDescription' => $category['description'],
            'category' => $category,
            'posts' => $postModel->forCategory((int) $category['id'], $sort, self::PER_PAGE, ($page - 1) * self::PER_PAGE),
            'sort' => $sort,
            'page' => $page,
            'pages' => $pages,
            'total' => $total,
            'dateUrl' => Url::currentWith(['sort' => 'date', 'page' => 1]),
            'viewsUrl' => Url::currentWith(['sort' => 'views', 'page' => 1]),
            'pageUrls' => $pageUrls,
            'prevUrl' => $page > 1 ? Url::currentWith(['page' => $page - 1]) : null,
            'nextUrl' => $page < $pages ? Url::currentWith(['page' => $page + 1]) : null,
        ]);
    }
}
