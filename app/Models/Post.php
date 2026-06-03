<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class Post
{
    /**
     * Модель поста: выборки постов, подсчёты, связанные категории и логику похожих постов.
     */
    public function __construct(private readonly PDO $pdo) {}

    public function findBySlug(string $slug): ?array
    {
        // Находим пост по slug — возвращаем поля, необходимые для шаблона.
        $statement = $this->pdo->prepare(
            'SELECT id, slug, image, title, description, body, views, published_at
             FROM posts
             WHERE slug = :slug'
        );
        $statement->execute(['slug' => $slug]);
        $post = $statement->fetch();

        return $post ?: null;
    }

    public function forCategory(int $categoryId, string $sort, int $limit, int $offset): array
    {
        $orderBy = [
            'views' => 'p.views DESC, p.published_at DESC, p.id DESC',
            'date' => 'p.published_at DESC, p.id DESC',
        ][$sort] ?? 'p.published_at DESC, p.id DESC';

        $statement = $this->pdo->prepare(
            "SELECT p.id, p.slug, p.image, p.title, p.description, p.views, p.published_at
             FROM posts p
             INNER JOIN category_post cp ON cp.post_id = p.id
             WHERE cp.category_id = :category_id
             ORDER BY {$orderBy}
             LIMIT :limit OFFSET :offset"
        );
        $statement->bindValue('category_id', $categoryId, PDO::PARAM_INT);
        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->bindValue('offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function countForCategory(int $categoryId): int
    {
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*)
             FROM category_post
             WHERE category_id = :category_id'
        );
        $statement->execute(['category_id' => $categoryId]);

        return (int) $statement->fetchColumn();
    }

    public function categories(int $postId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT c.id, c.slug, c.name
             FROM categories c
             INNER JOIN category_post cp ON cp.category_id = c.id
             WHERE cp.post_id = :post_id
             ORDER BY c.name'
        );
        $statement->execute(['post_id' => $postId]);

        return $statement->fetchAll();
    }

    public function related(int $postId, int $limit): array
    {
        // общих категорий тут достаточно, поиск не нужен
        $statement = $this->pdo->prepare(
            'SELECT p.id, p.slug, p.image, p.title, p.description, p.views, p.published_at
             FROM (
                 SELECT cp.post_id, COUNT(*) AS shared_categories
                 FROM category_post cp
                 WHERE cp.category_id IN (
                     SELECT category_id FROM category_post WHERE post_id = :source_post_id
                 )
                 AND cp.post_id <> :excluded_post_id
                 GROUP BY cp.post_id
             ) related
             INNER JOIN posts p ON p.id = related.post_id
             ORDER BY related.shared_categories DESC, p.published_at DESC, p.id DESC
             LIMIT :limit'
        );
        $statement->bindValue('source_post_id', $postId, PDO::PARAM_INT);
        $statement->bindValue('excluded_post_id', $postId, PDO::PARAM_INT);
        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        $posts = $statement->fetchAll();
        $remaining = $limit - count($posts);

        if ($remaining <= 0) {
            return $posts;
        }

        $excludeIds = array_column($posts, 'id');
        $excludeIds[] = $postId;
        $placeholders = implode(',', array_fill(0, count($excludeIds), '?'));

        $fallback = $this->pdo->prepare(
            "SELECT id, slug, image, title, description, views, published_at
             FROM posts
             WHERE id NOT IN ({$placeholders})
             ORDER BY published_at DESC, id DESC
             LIMIT ?"
        );

        foreach ($excludeIds as $index => $id) {
            $fallback->bindValue($index + 1, (int) $id, PDO::PARAM_INT);
        }
        $fallback->bindValue(count($excludeIds) + 1, $remaining, PDO::PARAM_INT);
        $fallback->execute();

        return array_merge($posts, $fallback->fetchAll());
    }

    public function incrementViews(int $postId): void
    {
        $statement = $this->pdo->prepare(
            'UPDATE posts SET views = views + 1 WHERE id = :id'
        );
        $statement->execute(['id' => $postId]);
    }
}
