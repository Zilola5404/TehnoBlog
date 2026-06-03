<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\Post;
use PDO;

final class PostController
{
    /**
     * Контроллер показа отдельной статьи.
     * Загружает пост по slug, увеличивает счётчик просмотров и рендерит `post.tpl`.
     */
    public function __construct(
        private readonly View $view,
        private readonly PDO $pdo
    ) {}

    public function show(array $params): string
    {
        $postModel = new Post($this->pdo);
        $post = $postModel->findBySlug($params['slug']);

        if (!$post) {
            http_response_code(404);
            return $this->view->render('404.tpl', ['title' => 'Статья не найдена']);
        }

        $postModel->incrementViews((int) $post['id']);
        $post['views']++;

        return $this->view->render('post.tpl', [
            'title' => $post['title'] . ' — статья блога',
            'metaDescription' => $post['description'],
            'post' => $post,
            'categories' => $postModel->categories((int) $post['id']),
            'relatedPosts' => $postModel->related((int) $post['id'], 3),
        ]);
    }
}
