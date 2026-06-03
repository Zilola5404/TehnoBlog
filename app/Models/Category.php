<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class Category
{
    /**
     * Модель категории: работа с таблицей `categories` и выборка последних постов для секций.
     */
    public function __construct(private readonly PDO $pdo) {}

    public function findBySlug(string $slug): ?array
    {
        // Ищем категорию по человеко-понятному `slug` (напр., "razrabotka").
        $statement = $this->pdo->prepare(
            'SELECT id, slug, name, description
             FROM categories
             WHERE slug = :slug'
        );
        $statement->execute(['slug' => $slug]);
        $category = $statement->fetch();

        return $category ?: null;
    }

    public function withRecentPosts(int $limit): array
    {
        $statement = $this->pdo->prepare(
            'SELECT c.id AS category_id, c.slug AS category_slug, c.name AS category_name,
                    c.description AS category_description, ranked.id AS post_id,
                    ranked.slug AS post_slug, ranked.image, ranked.title,
                    ranked.description AS post_description, ranked.views, ranked.published_at
             FROM categories c
             INNER JOIN (
                 SELECT posts_ranked.*
                 FROM (
                     SELECT cp.category_id, p.id, p.slug, p.image, p.title, p.description,
                            p.views, p.published_at,
                            ROW_NUMBER() OVER (
                                PARTITION BY cp.category_id
                                ORDER BY p.published_at DESC, p.id DESC
                            ) AS post_rank
                     FROM category_post cp
                     INNER JOIN posts p ON p.id = cp.post_id
                 ) posts_ranked
                 WHERE posts_ranked.post_rank <= :limit
             ) ranked ON ranked.category_id = c.id
             ORDER BY c.name, ranked.published_at DESC, ranked.id DESC'
        );
        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        $sections = [];
        foreach ($statement->fetchAll() as $row) {
            $categoryId = (int) $row['category_id'];

            if (!isset($sections[$categoryId])) {
                $sections[$categoryId] = [
                    'id' => $categoryId,
                    'slug' => $row['category_slug'],
                    'name' => $row['category_name'],
                    'description' => $row['category_description'],
                    'posts' => [],
                ];
            }

            $sections[$categoryId]['posts'][] = [
                'id' => (int) $row['post_id'],
                'slug' => $row['post_slug'],
                'image' => $row['image'],
                'title' => $row['title'],
                'description' => $row['post_description'],
                'views' => (int) $row['views'],
                'published_at' => $row['published_at'],
            ];
        }

        return array_values($sections);
    }
}
