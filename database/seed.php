<?php

declare(strict_types=1);

use App\Core\Database;

require dirname(__DIR__) . '/vendor/autoload.php';

// Скрипт для заполнения базы тестовыми категориями и постами.
// Используется локально при разработке, очищает таблицы и создаёт набор статей.
// ВАЖНО: запуск делает TRUNCATE таблиц.

$pdo = Database::connect(require dirname(__DIR__) . '/config/database.php');

$categories = [
    [
        'slug' => 'razrabotka',
        'name' => 'Разработка',
        'description' => 'Практические статьи о PHP, MySQL, архитектуре небольших проектов, производительности и поддержке веб-сервисов.',
    ],
    [
        'slug' => 'produkt',
        'name' => 'Продукт',
        'description' => 'Материалы о продуктовой стратегии, приоритетах, аналитике, пользовательской ценности и развитии цифровых сервисов.',
    ],
    [
        'slug' => 'dizain',
        'name' => 'Дизайн',
        'description' => 'Статьи об интерфейсах, UX-текстах, визуальной системе, адаптивном дизайне и понятных сценариях для пользователей.',
    ],
    [
        'slug' => 'biznes',
        'name' => 'Бизнес',
        'description' => 'Короткие разборы про процессы, рост проектов, работу с ограничениями, метрики и управление небольшими командами.',
    ],
    [
        'slug' => 'komanda',
        'name' => 'Команда',
        'description' => 'Практика коммуникации, code review, найма, планирования и здоровых рабочих привычек в продуктовой команде.',
    ],
];

$posts = [
    [
        'slug' => 'release-without-panic',
        'title' => 'Как выпустить релиз без паники',
        'description' => 'Чек-лист перед выкладкой помогает снизить риск ошибок, сохранить темп команды и быстрее откатить проблему.',
        'image' => '/assets/img/photo1.jpg',
        'categories' => ['Разработка', 'Продукт'],
    ],
    [
        'slug' => 'useful-technical-debt',
        'title' => 'Технический долг, который не мешает расти',
        'description' => 'Разбираем, когда временное решение оправдано и как не превратить его в постоянную проблему проекта.',
        'image' => '/assets/img/photo2.jpg',
        'categories' => ['Разработка', 'Бизнес'],
    ],
    [
        'slug' => 'dashboard-that-works',
        'title' => 'Дашборд, который действительно читают',
        'description' => 'Хороший дашборд показывает ключевые метрики, помогает принять решение и не перегружает пользователя.',
        'image' => '/assets/img/photo3.jpg',
        'categories' => ['Дизайн', 'Продукт'],
    ],
    [
        'slug' => 'roadmap-review',
        'title' => 'Как проводить короткий разбор roadmap',
        'description' => 'Еженедельный обзор roadmap помогает синхронизировать цели, риски, сроки и ожидания команды.',
        'image' => '/assets/img/photo4.jpg',
        'categories' => ['Бизнес', 'Продукт'],
    ],
    [
        'slug' => 'code-review-product',
        'title' => 'Code review как часть качества продукта',
        'description' => 'На ревью важно проверять не только стиль кода, но и смысл задачи, поведение интерфейса и риски для пользователя.',
        'image' => '/assets/img/photo5.jpg',
        'categories' => ['Разработка', 'Команда'],
    ],
    [
        'slug' => 'empty-state',
        'title' => 'Пустое состояние в интерфейсе',
        'description' => 'Пустой экран может объяснить ситуацию, подсказать следующий шаг и уменьшить количество вопросов в поддержку.',
        'image' => '/assets/img/photo6.jpg',
        'categories' => ['Дизайн', 'Продукт'],
    ],
    [
        'slug' => 'metrics-without-show',
        'title' => 'Метрики без лишнего шума',
        'description' => 'Полезная метрика влияет на решение, а не просто красиво выглядит в отчёте или презентации.',
        'image' => '/assets/img/photo7.jpg',
        'categories' => ['Бизнес', 'Команда'],
    ],
    [
        'slug' => 'boring-php',
        'title' => 'Почему простой PHP удобен для небольшого блога',
        'description' => 'Прямой PDO, понятные шаблоны и минимум магии позволяют быстро поддерживать проект без лишних зависимостей.',
        'image' => '/assets/img/photo8.jpg',
        'categories' => ['Разработка'],
    ],
    [
        'slug' => 'small-team-momentum',
        'title' => 'Как маленькой команде сохранять темп',
        'description' => 'Темп держится на ясной ответственности, короткой обратной связи и решениях, которые не зависают неделями.',
        'image' => '/assets/img/photo9.jpg',
        'categories' => ['Команда', 'Бизнес'],
    ],
    [
        'slug' => 'content-categories',
        'title' => 'Как категории помогают читателю найти статью',
        'description' => 'Категории должны отражать интерес пользователя, а не внутреннюю структуру компании или команды.',
        'image' => '/assets/img/photo10.jpg',
        'categories' => ['Дизайн', 'Продукт'],
    ],
    [
        'slug' => 'simple-pagination',
        'title' => 'Пагинация для блога и каталога статей',
        'description' => 'Понятная пагинация помогает ориентироваться в списке материалов и не терять выбранную сортировку.',
        'image' => '/assets/img/photo11.jpg',
        'categories' => ['Дизайн', 'Разработка'],
    ],
    [
        'slug' => 'weekly-product-note',
        'title' => 'Еженедельная продуктовая заметка',
        'description' => 'Один короткий текст о целях, фактах и рисках часто заменяет длинный статус-митинг.',
        'image' => '/assets/img/photo12.jpg',
        'categories' => ['Продукт', 'Команда'],
    ],
    [
        'slug' => 'indexes-that-help',
        'title' => 'Индексы MySQL для страниц блога',
        'description' => 'Индекс должен повторять реальные фильтры и сортировки: категорию, дату публикации и просмотры.',
        'image' => '/assets/img/photo13.jpg',
        'categories' => ['Разработка', 'Бизнес'],
    ],
    [
        'slug' => 'interface-copy',
        'title' => 'Текст в интерфейсе, который помогает действовать',
        'description' => 'Кнопки, подписи и пустые состояния должны говорить языком пользователя и объяснять следующий шаг.',
        'image' => '/assets/img/photo14.jpg',
        'categories' => ['Дизайн', 'Команда'],
    ],
    [
        'slug' => 'work-constraints',
        'title' => 'Как работать с ограничениями проекта',
        'description' => 'Сроки, бюджет и команда помогают выбрать реалистичное решение, если учитывать их до начала разработки.',
        'image' => '/assets/img/photo15.jpg',
        'categories' => ['Бизнес', 'Продукт'],
    ],
];

$body = static function (string $title): string {
    return implode("\n\n", [
        "{$title}. В этой статье разбираем практический подход, который можно применить в небольшом веб-проекте, блоге или продуктовой команде.",
        'Главная идея простая: сначала нужно понять цель пользователя, затем выбрать минимальное решение и проверить его на реальных данных. Так команда быстрее видит ошибки и не тратит время на лишние функции.',
        'Для SEO важны понятный заголовок, точное описание, связанная категория и текст, который отвечает на конкретный запрос. Для разработки важны предсказуемые данные, простые запросы и интерфейс, который не ломается на мобильных экранах.',
        'Такой подход помогает развивать сервис постепенно: улучшать контент, измерять просмотры, находить популярные темы и показывать пользователю похожие материалы без сложной системы рекомендаций.',
    ]);
};

$sanitizeBody = static function (string $value): string {
    return trim(strip_tags($value));
};

$slugify = static function (string $value): string {
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';

    return trim($value, '-');
};

try {
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    $pdo->exec('TRUNCATE TABLE category_post');
    $pdo->exec('TRUNCATE TABLE posts');
    $pdo->exec('TRUNCATE TABLE categories');
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

    $pdo->beginTransaction();

    $insertCategory = $pdo->prepare(
        'INSERT INTO categories (slug, name, description) VALUES (:slug, :name, :description)'
    );

    $categoryIds = [];
    foreach ($categories as $category) {
        $insertCategory->execute([
            'slug' => $category['slug'] ?? $slugify($category['name']),
            'name' => $category['name'],
            'description' => $category['description'],
        ]);
        $categoryIds[$category['name']] = (int) $pdo->lastInsertId();
    }

    $insertPost = $pdo->prepare(
        'INSERT INTO posts (slug, image, title, description, body, views, published_at)
         VALUES (:slug, :image, :title, :description, :body, :views, :published_at)'
    );
    $attachCategory = $pdo->prepare(
        'INSERT INTO category_post (category_id, post_id) VALUES (:category_id, :post_id)'
    );

    $publishedAt = new DateTimeImmutable('-15 days');

    foreach ($posts as $index => $post) {
        $title = $post['title'];
        $insertPost->execute([
            'slug' => $post['slug'],
            'image' => $post['image'],
            'title' => $title,
            'description' => $post['description'],
            'body' => $sanitizeBody($body($title)),
            'views' => random_int(18, 950),
            'published_at' => $publishedAt->modify("+{$index} days")->format('Y-m-d H:i:s'),
        ]);

        $postId = (int) $pdo->lastInsertId();

        foreach ($post['categories'] as $categoryName) {
            $attachCategory->execute([
                'category_id' => $categoryIds[$categoryName],
                'post_id' => $postId,
            ]);
        }
    }

    $pdo->commit();
    echo "Seeded categories and posts.\n";
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    throw $exception;
}
